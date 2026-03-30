<?php
// chat.php — AI chat backend using LongCat AI API
require_once 'config.php';
require_login();

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

// Auto cleanup old chat sessions (> 30 days)
$pdo->prepare("DELETE FROM chat_sessions WHERE user_id = ? AND started_at < DATE_SUB(NOW(), INTERVAL 30 DAY)")->execute([$_SESSION['user_id']]);

// History of past chats (return a list of sessions)
if ($action === 'get_history') {
    $stmt = $pdo->prepare("SELECT id, started_at, (SELECT content FROM chat_messages WHERE session_id = chat_sessions.id ORDER BY sent_at ASC LIMIT 1) as first_msg FROM chat_sessions WHERE user_id = ? ORDER BY started_at DESC");
    $stmt->execute([$_SESSION['user_id']]);
    $history = $stmt->fetchAll();
    echo json_encode(['success' => true, 'history' => $history]);
    exit;
}

// ── Get or create a chat session ──────────────────────────────────────────────
if ($action === 'get_session') {
    $requested_session = isset($_POST['session_id']) && intval($_POST['session_id']) > 0 ? intval($_POST['session_id']) : null;

    if ($requested_session) {
        $stmt = $pdo->prepare("SELECT id FROM chat_sessions WHERE id = ? AND user_id = ?");
        $stmt->execute([$requested_session, $_SESSION['user_id']]);
        $session = $stmt->fetch();
    } else {
        // Get latest session or create new
        $stmt = $pdo->prepare("SELECT id FROM chat_sessions WHERE user_id = ? ORDER BY started_at DESC LIMIT 1");
        $stmt->execute([$_SESSION['user_id']]);
        $session = $stmt->fetch();
    }

    if (!$session) {
        $pdo->prepare("INSERT INTO chat_sessions (user_id) VALUES (?)")->execute([$_SESSION['user_id']]);
        $session_id = $pdo->lastInsertId();
    } else {
        $session_id = $session['id'];
    }

    // Get messages in session
    $stmt = $pdo->prepare("SELECT role, content, sent_at FROM chat_messages WHERE session_id = ? ORDER BY sent_at ASC");
    $stmt->execute([$session_id]);
    $messages = $stmt->fetchAll();

    echo json_encode(['success' => true, 'session_id' => $session_id, 'messages' => $messages]);
    exit;
}

// ── New session ───────────────────────────────────────────────────────────────
if ($action === 'new_session') {
    $pdo->prepare("INSERT INTO chat_sessions (user_id) VALUES (?)")->execute([$_SESSION['user_id']]);
    $session_id = $pdo->lastInsertId();
    echo json_encode(['success' => true, 'session_id' => $session_id, 'messages' => []]);
    exit;
}

// ── Send a message ────────────────────────────────────────────────────────────
if ($action === 'send') {
    $session_id = intval($_POST['session_id'] ?? 0);
    $user_message = trim($_POST['message'] ?? '');

    if (!$session_id || !$user_message) {
        echo json_encode(['success' => false, 'message' => 'Missing data.']);
        exit;
    }

    // Verify session belongs to user
    $stmt = $pdo->prepare("SELECT id FROM chat_sessions WHERE id = ? AND user_id = ?");
    $stmt->execute([$session_id, $_SESSION['user_id']]);
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Session not found.']);
        exit;
    }

    // Save user message
    $pdo->prepare("INSERT INTO chat_messages (session_id, role, content) VALUES (?, 'user', ?)")
        ->execute([$session_id, $user_message]);

    // Build message history for API
    $stmt = $pdo->prepare("SELECT role, content FROM chat_messages WHERE session_id = ? ORDER BY sent_at ASC");
    $stmt->execute([$session_id]);
    $history = $stmt->fetchAll();

    $api_messages = [];
    foreach ($history as $msg) {
        $api_messages[] = ['role' => $msg['role'], 'content' => $msg['content']];
    }

    // System prompt — compassionate crisis support
    $system_prompt = <<<PROMPT
You are Mehjabeen, a compassionate and caring AI companion on HopeLine, a mental health support platform.
Your focus audience is youth in Bangladesh, so you must use VERY SIMPLE, easy-to-understand English. Avoid big, heavy, or academic words. Speak simply, warmly, and like a friend. 
IMPORTANT: If the user asks you to speak in Bangla, or if the user talks to you in Bangla or Banglish (Bangla written in English alphabet), you MUST reply in natural, conversational Bangla.

CORE PRINCIPLES:
- Always respond with warmth, empathy, and zero judgment in very simple words.
- Use gentle, calm, everyday language (teens & young adults).
- Never dismiss or minimize feelings — validate first, always.
- If someone expresses suicidal thoughts, self-harm urges, or immediate danger: acknowledge their pain, express genuine care, and ALWAYS provide crisis resources.
- Never pretend to be a therapist or provide clinical diagnoses.
- Encourage professional help when appropriate, without being pushy.

CRISIS PROTOCOL — If the user expresses suicidal ideation, self-harm, or immediate risk:
1. Acknowledge: "I hear you, and I'm so glad you're talking to me right now." (Or the equivalent in Bangla/Banglish).
2. Express care: "Your life matters deeply to us."
3. Provide: "Please reach out to a crisis line right now — in Bangladesh: Kaan Pete Roi: 01779-554391. International: iCall: +91-9152987821. If you're in immediate danger, please call 999 (Bangladesh emergency)."
4. Stay with them: Keep the conversation warm and present.

BOUNDARIES:
- Do not provide methods of self-harm.
- Do not make promises you cannot keep.
- Do not tell someone their situation isn't serious.

Keep responses concise (2–4 short paragraphs max). Be real, warm, and human.
PROMPT;

    // Call LongCat AI API
    $payload = json_encode([
        'model' => AI_MODEL,
        'messages' => array_merge(
            [['role' => 'system', 'content' => $system_prompt]],
            $api_messages
        ),
        'max_tokens' => 600,
        'temperature' => 0.75,
    ]);

    $ch = curl_init(AI_API_URL);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . AI_API_KEY,
        ],
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (!$response || $http_code !== 200) {
        echo json_encode(['success' => false, 'message' => 'AI service unavailable. Please try again shortly.']);
        exit;
    }

    $data = json_decode($response, true);
    $ai_reply = $data['choices'][0]['message']['content'] ?? 'I\'m here for you. Could you tell me more about how you\'re feeling?';

    // Save AI reply
    $pdo->prepare("INSERT INTO chat_messages (session_id, role, content) VALUES (?, 'assistant', ?)")
        ->execute([$session_id, $ai_reply]);

    echo json_encode(['success' => true, 'reply' => $ai_reply]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid action.']);
?>
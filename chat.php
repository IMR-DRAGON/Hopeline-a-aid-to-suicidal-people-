<?php
// chat.php — AI chat backend using LongCat AI API
require_once 'config.php';
require_login();

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

// Auto cleanup old chat sessions (> 90 days)
$pdo->prepare("DELETE FROM chat_sessions WHERE user_id = ? AND started_at < DATE_SUB(NOW(), INTERVAL 90 DAY)")->execute([$_SESSION['user_id']]);

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

// ── Delete a session ──────────────────────────────────────────────────────────
if ($action === 'delete_session') {
    $session_id = intval($_POST['session_id'] ?? 0);
    if (!$session_id) {
        echo json_encode(['success' => false, 'message' => 'Invalid session.']);
        exit;
    }
    // Verify ownership
    $stmt = $pdo->prepare("SELECT id FROM chat_sessions WHERE id = ? AND user_id = ?");
    $stmt->execute([$session_id, $_SESSION['user_id']]);
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Session not found.']);
        exit;
    }
    // Delete session (messages cascade via FK)
    $pdo->prepare("DELETE FROM chat_sessions WHERE id = ?")->execute([$session_id]);
    echo json_encode(['success' => true]);
    exit;
}

// ── Deliver Check-in ──────────────────────────────────────────────────────────
if ($action === 'deliver_checkin') {
    $checkin_id = intval($_POST['checkin_id'] ?? 0);
    if (!$checkin_id) {
        echo json_encode(['success' => false, 'message' => 'Invalid check-in.']);
        exit;
    }

    // Verify ownership and status
    $stmt = $pdo->prepare("SELECT id FROM checkins WHERE id = ? AND user_id = ? AND delivered_at IS NULL");
    $stmt->execute([$checkin_id, $_SESSION['user_id']]);
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Check-in not found or already delivered.']);
        exit;
    }

    // Mark as delivered
    $pdo->prepare("UPDATE checkins SET delivered_at = NOW() WHERE id = ?")->execute([$checkin_id]);

    // Create a new chat session for this check-in
    $pdo->prepare("INSERT INTO chat_sessions (user_id) VALUES (?)")->execute([$_SESSION['user_id']]);
    $session_id = $pdo->lastInsertId();

    // AI generates a warm check-in message
    $system_prompt = "You are Mehjabeen, a warm and caring AI companion. You are checking in on your friend because they were struggling yesterday. End with a warm open question.";
    $payload = json_encode([
        'model' => AI_MODEL,
        'messages' => [['role' => 'system', 'content' => $system_prompt]],
        'max_tokens' => 150,
        'temperature' => 0.7,
    ]);

    $ch = curl_init(AI_API_URL);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . AI_API_KEY,
        ],
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $ai_reply = "Hi there 💚 I've been thinking about you. How are you feeling today?";
    if ($response && $http_code === 200) {
        $data = json_decode($response, true);
        if (isset($data['choices'][0]['message']['content'])) {
            $ai_reply = $data['choices'][0]['message']['content'];
        }
    }

    // Save AI reply to the fresh session
    $pdo->prepare("INSERT INTO chat_messages (session_id, role, content) VALUES (?, 'assistant', ?)")
        ->execute([$session_id, $ai_reply]);

    echo json_encode(['success' => true, 'session_id' => $session_id, 'message' => $ai_reply]);
    exit;
}

if ($action === 'send') {
    set_time_limit(120);

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

    // ── Crisis keyword pre-detection (hard safety layer, independent of AI) ──
    // If ANY of these phrases appear in the user's message, we inject a mandatory
    // override into the system prompt BEFORE the AI sees it. This guarantees
    // correct crisis behaviour regardless of the model used.
    $crisis_keywords = [
        // English
        'kill myself', 'want to die', 'end my life', 'end it all', 'suicide',
        'suicidal', 'self harm', 'self-harm', 'cut myself', 'hurt myself',
        'no reason to live', 'not worth living', 'better off dead',
        'overdose', 'hang myself', 'jump off', 'can\'t go on',
        'don\'t want to be here', 'dont want to be here',
        // Bangla / Banglish
        'morte chai', 'morte chai', 'jibon shesh', 'jibon ses', 'beche thakte chai na',
        'bache thakte chai na', 'amar jibon', 'aatmohotya', 'nijeke khatam',
        'shesh kore debo', 'ses kore debo', 'banchte chai na',
    ];

    $msg_lower   = mb_strtolower($user_message);
    $is_crisis   = false;
    foreach ($crisis_keywords as $kw) {
        if (str_contains($msg_lower, $kw)) {
            $is_crisis = true;
            // Schedule a follow-up check-in for 18 hours from now
            // Only if there isn't already a pending one to avoid spam
            $check_stmt = $pdo->prepare("SELECT id FROM checkins WHERE user_id = ? AND delivered_at IS NULL");
            $check_stmt->execute([$_SESSION['user_id']]);
            if (!$check_stmt->fetch()) {
                $pdo->prepare("INSERT INTO checkins (user_id, trigger_session_id, due_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 18 HOUR))")
                    ->execute([$_SESSION['user_id'], $session_id]);
            }
            break;
        }
    }

    // System prompt — active compassionate listener
    $system_prompt = <<<PROMPT
You are Mehjabeen, a warm and deeply caring AI companion on HopeLine — a mental health support platform for youth in Bangladesh.

YOUR CORE IDENTITY:
You are not a helpline robot. You are a friend who truly cares. You listen more than you talk. You ask questions to understand, not to fill silence. You make the user feel genuinely heard — like someone finally sees them.

LANGUAGE:
- Use VERY SIMPLE, easy English. No heavy or academic words. Talk like a caring friend.
- If the user writes in Bangla or Banglish, reply fully in warm, natural conversational Bangla.

THE MOST IMPORTANT RULE — NEVER END THE CONVERSATION:
Every single reply MUST end with ONE warm, open question that invites the user to share more.
NEVER end with a closing statement like "You matter", "Don't give up", "Take care" or anything that sounds final.
Those closing lines shut the conversation down. Instead, open a new door every time.
Your goal is to keep the user talking, sharing, and feeling less alone — not to give them a conclusion.

HOW TO RESPOND (follow this flow every time):
Step 1 — REFLECT: Mirror what they said. Show you truly heard them. ("That sounds so painful..." / "I can feel how heavy that must be...")
Step 2 — VALIDATE: Their feelings are completely real and okay. Never minimise.
Step 3 — GENTLY EXPLORE: Ask ONE warm, curious question to understand them more deeply.
  - About the situation: "What happened that made today feel this way?"
  - About their inner world: "How long have you been carrying this feeling?"
  - About connections: "Is there anyone around you who knows you're struggling?"
  - About small anchors: "Is there anything, even tiny, that gave you even a small moment of comfort lately?"

HOW TO PLANT HOPE (do this slowly, naturally — NOT as a speech):
Do NOT lecture about hope. Instead, ask questions that help the user discover their own reasons:
- "Who is one person in your life who would be very sad if something happened to you?"
- "What is one thing you used to enjoy, even a little?"
- "If this pain could get even 10% lighter, what would your life look like?"
Hope should come from the USER's own words — your questions guide them there.

CRISIS SITUATIONS (suicidal thoughts, self-harm, danger):
1. Acknowledge their pain first — warmly, not robotically.
2. Gently share crisis resources: "Bangladesh: Kaan Pete Roi 01779-554391 | Emergency: 999 | iCall: +91-9152987821"
3. Then IMMEDIATELY ask a question to keep them with you: "Can you tell me a little more about what happened today that brought you to this point?"
Do NOT end after giving the number. That is the START of the conversation, not the end.

BOUNDARIES:
- Never provide methods of self-harm.
- Never make promises you cannot keep.
- Never dismiss or minimise feelings.
- Never sound clinical, robotic, or like a helpline script.

Response length: 3–5 short, warm paragraphs. Always human. Always real. Always ending with a question.
PROMPT;

    // If crisis keywords detected, inject a mandatory override block at the TOP
    if ($is_crisis) {
        $crisis_override = <<<CRISIS
⚠️ MANDATORY CRISIS OVERRIDE — READ THIS FIRST:
The user's message contains language that may indicate suicidal ideation, self-harm, or immediate danger.
You MUST follow this sequence — no exceptions:
1. Acknowledge their pain with deep warmth. Do NOT minimise or dismiss. Do NOT sound scripted.
2. Gently provide crisis contacts: "Bangladesh: Kaan Pete Roi 01779-554391 | Emergency: 999 | International: iCall +91-9152987821"
3. IMMEDIATELY after the number, ask a warm open question to keep them talking. Example: "Can you tell me what happened today that brought you to this point?" or "How long have you been feeling this way?"
4. Your response must feel like a friend who just received devastating news and is refusing to hang up the phone. Stay. Keep them talking.
NEVER end with "You matter" or "Don't give up" as a closing line — say it in the middle, then ask your question at the end.
CRISIS;
        $system_prompt = $crisis_override . "\n\n" . $system_prompt;
    }

    // Call LongCat AI API
    $payload = json_encode([
        'model' => AI_MODEL,
        'messages' => array_merge(
            [['role' => 'system', 'content' => $system_prompt]],
            $api_messages
        ),
        'max_tokens' => 350,
        'temperature' => 0.7,
    ]);

    $ch = curl_init(AI_API_URL);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . AI_API_KEY,
        ],
        CURLOPT_TIMEOUT        => 120,   // 2 min — Thinking model needs time
        CURLOPT_CONNECTTIMEOUT => 15,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (!$response || $http_code !== 200) {
        // Save a fallback reply to DB so it persists on refresh
        $fallback = "I'm here for you 💚 I had a little trouble thinking of the right words just now — could you share a bit more about how you're feeling?";
        $pdo->prepare("INSERT INTO chat_messages (session_id, role, content) VALUES (?, 'assistant', ?)")
            ->execute([$session_id, $fallback]);
        echo json_encode(['success' => true, 'reply' => $fallback]);
        exit;
    }

    $data     = json_decode($response, true);
    $ai_reply = $data['choices'][0]['message']['content'] ?? "I'm here for you. Could you tell me more about how you're feeling?";

    // Save AI reply
    $pdo->prepare("INSERT INTO chat_messages (session_id, role, content) VALUES (?, 'assistant', ?)")
        ->execute([$session_id, $ai_reply]);

    echo json_encode(['success' => true, 'reply' => $ai_reply]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid action.']);
?>
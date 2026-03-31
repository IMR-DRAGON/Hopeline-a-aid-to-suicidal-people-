<?php
// journal.php — Mood journal backend
require_once 'config.php';
require_login();

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// ── Save a journal entry ──────────────────────────────────────────────────────
if ($action === 'save') {
    $mood_score   = intval($_POST['mood_score'] ?? 0);
    $mood_label   = trim($_POST['mood_label'] ?? '');
    $content      = trim($_POST['content'] ?? '');
    $is_anonymous = intval($_POST['is_anonymous'] ?? 0);

    if ($mood_score < 1 || $mood_score > 5) {
        echo json_encode(['success' => false, 'message' => 'Please select a mood.']);
        exit;
    }

    $pdo->prepare("INSERT INTO journal_entries (user_id, mood_score, mood_label, content, is_anonymous) VALUES (?, ?, ?, ?, ?)")
        ->execute([$_SESSION['user_id'], $mood_score, $mood_label, $content, $is_anonymous]);

    echo json_encode(['success' => true, 'message' => 'Entry saved 💚']);
    exit;
}

// ── Get entries for the logged-in user ────────────────────────────────────────
if ($action === 'list') {
    $stmt = $pdo->prepare("SELECT id, mood_score, mood_label, content, is_anonymous, created_at FROM journal_entries WHERE user_id = ? ORDER BY created_at DESC LIMIT 30");
    $stmt->execute([$_SESSION['user_id']]);
    $entries = $stmt->fetchAll();
    echo json_encode(['success' => true, 'entries' => $entries]);
    exit;
}

// ── Get mood trend data for chart ─────────────────────────────────────────────
if ($action === 'trend') {
    $stmt = $pdo->prepare("SELECT DATE(created_at) as day, AVG(mood_score) as avg_mood FROM journal_entries WHERE user_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 14 DAY) GROUP BY DATE(created_at) ORDER BY day ASC");
    $stmt->execute([$_SESSION['user_id']]);
    $trend = $stmt->fetchAll();
    echo json_encode(['success' => true, 'trend' => $trend]);
    exit;
}

// ── Delete an entry ───────────────────────────────────────────────────────────
if ($action === 'delete') {
    $id = intval($_POST['id'] ?? 0);
    $pdo->prepare("DELETE FROM journal_entries WHERE id = ? AND user_id = ?")->execute([$id, $_SESSION['user_id']]);
    echo json_encode(['success' => true]);
    exit;
}

// ── Get AI-powered insights ──────────────────────────────────────────────────
if ($action === 'get_insights') {
    // 1. Fetch last 15 entries for context
    $stmt = $pdo->prepare("SELECT mood_score, mood_label, content, created_at FROM journal_entries WHERE user_id = ? ORDER BY created_at DESC LIMIT 15");
    $stmt->execute([$_SESSION['user_id']]);
    $entries = $stmt->fetchAll();

    if (empty($entries)) {
        echo json_encode(['success' => true, 'insight' => "I don't have any journal entries yet. Once you share how you're feeling for a few days, I'll be able to help you see patterns in your mood! 💚"]);
        exit;
    }

    // 2. Prepare entries for AI
    $history_text = "";
    foreach (array_reverse($entries) as $e) {
        $history_text .= "Date: {$e['created_at']}, Mood: {$e['mood_label']} (Score: {$e['mood_score']}), Note: {$e['content']}\n";
    }

    // 3. AI System Prompt
    $system_prompt = "You are Mehjabeen, an empathetic AI assistant for HopeLine. Your goal is to analyze a user's recent mood journal and provide short, kind, and helpful emotional insights.
    - Identify positive patterns (e.g., 'You feel better after talking to friends').
    - Notice recurring triggers or low points (e.g., 'Mondays seem a bit harder for you').
    - Always be incredibly gentle and supportive.
    - Keep it short: 2-3 sentences max.
    - Use very simple, warm English (suitable for youth in Bangladesh).
    - If the user is mostly sad, offer a gentle grounding encouragement.
    - If the user is improving, celebrate their progress.";

    // 4. Call AI API
    $payload = json_encode([
        'model' => AI_MODEL,
        'messages' => [
            ['role' => 'system', 'content' => $system_prompt],
            ['role' => 'user', 'content' => "Here is my mood history for the past week. Please give me a short, friendly insight about it:\n\n" . $history_text]
        ],
        'max_tokens' => 200,
        'temperature' => 0.7,
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
        echo json_encode(['success' => false, 'message' => 'Insight service unavailable.']);
        exit;
    }

    $data = json_decode($response, true);
    $insight = $data['choices'][0]['message']['content'] ?? "You're doing great just by taking the time to track your feelings. Keep going! 💚";

    echo json_encode(['success' => true, 'insight' => $insight]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid action.']);
?>

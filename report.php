<?php
// report.php — Weekly Well-being Report & Activity Tracker
require_once 'config.php';
require_login();

header('Content-Type: application/json');

// Auto-create table if not exists (Lazy Load)
$pdo->exec("CREATE TABLE IF NOT EXISTS user_activities (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    user_id INT NOT NULL, 
    activity_type VARCHAR(50) NOT NULL, 
    activity_value INT DEFAULT 1, 
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP, 
    INDEX(user_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)");

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// ── Log an activity ───────────────────────────────────────────────────────────
if ($action === 'log_activity') {
    $type  = $_POST['type']  ?? '';
    $value = intval($_POST['value'] ?? 1);
    
    if ($type) {
        $pdo->prepare("INSERT INTO user_activities (user_id, activity_type, activity_value) VALUES (?, ?, ?)")
            ->execute([$_SESSION['user_id'], $type, $value]);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Missing activity type']);
    }
    exit;
}

// ── Get Weekly Report ─────────────────────────────────────────────────────────
if ($action === 'get_report') {
    $uid = $_SESSION['user_id'];
    
    // 1. Mood Stats (Last 7 Days)
    $stmt = $pdo->prepare("SELECT AVG(mood_score) as avg_mood, COUNT(*) as count FROM journal_entries WHERE user_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
    $stmt->execute([$uid]);
    $mood = $stmt->fetch();
    
    // 2. Journal Entry Content for AI analysis
    $stmt = $pdo->prepare("SELECT mood_label, content, created_at FROM journal_entries WHERE user_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) ORDER BY created_at DESC LIMIT 10");
    $stmt->execute([$uid]);
    $entries = $stmt->fetchAll();
    
    // 3. Forum Engagement
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM forum_posts WHERE user_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
    $stmt->execute([$uid]);
    $posts = $stmt->fetchColumn();
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM forum_replies WHERE user_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
    $stmt->execute([$uid]);
    $replies = $stmt->fetchColumn();
    
    // 4. Support Received (Hearts)
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM forum_reactions 
        WHERE (post_id IN (SELECT id FROM forum_posts WHERE user_id = ?) 
           OR  reply_id IN (SELECT id FROM forum_replies WHERE user_id = ?))
        AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    ");
    $stmt->execute([$uid, $uid]);
    $hearts = $stmt->fetchColumn();
    
    // 5. Grounding/Breathing Sessions
    $stmt = $pdo->prepare("SELECT SUM(activity_value) FROM user_activities WHERE user_id = ? AND activity_type = 'breathing_cycle_complete' AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
    $stmt->execute([$uid]);
    $breathing_cycles = intval($stmt->fetchColumn() ?? 0);
    
    // 6. AI Insights (Summary)
    $history_text = "";
    foreach (array_reverse($entries) as $e) {
        $history_text .= "{$e['created_at']}: {$e['mood_label']} - {$e['content']}\n";
    }

    $system_prompt = "You are Mehjabeen, an AI assistant for HopeLine. Your goal is to analyze a user's week of well-being.
    - Provide a warm, 2-paragraph summary. 
    - Mention their mood average, forum support, and breathing sessions.
    - If they had a hard week, be extra empathetic. 
    - If they improved, celebrate it.
    - Use very simple, friendly English for youth in Bangladesh.";

    $ai_summary = "You're doing amazing just by showing up for yourself. I'm looking at your week now!";
    
    // Call AI only if we have at least 1 entry
    if ($history_text) {
        $payload = json_encode([
            'model' => AI_MODEL,
            'messages' => [
                ['role' => 'system', 'content' => $system_prompt],
                ['role' => 'user', 'content' => "Summary for the last 7 days:\nMood Average: {$mood['avg_mood']}/5\nPosts: {$posts}, Replies: {$replies}\nHearts Received: {$hearts}\nBreathing Cycles: {$breathing_cycles}\nJournal Entries:\n" . $history_text]
            ],
            'max_tokens' => 400,
            'temperature' => 0.7,
        ]);

        $ch = curl_init(AI_API_URL);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Authorization: Bearer ' . AI_API_KEY],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $response = curl_exec($ch);
        $data = json_decode($response, true);
        $ai_summary = $data['choices'][0]['message']['content'] ?? "Your week shows real strength. I'm so proud of how you're using these tools to care for yourself 💚";
    } else {
        $ai_summary = "I'd love to give you a full summary, but you haven't journaled in the last 7 days. Try to check in tomorrow — every thought you share matters! 💚";
    }
    
    echo json_encode([
        'success' => true,
        'stats' => [
            'avg_mood' => round($mood['avg_mood'], 1),
            'journal_count' => $mood['count'],
            'posts' => $posts,
            'replies' => $replies,
            'hearts' => $hearts,
            'breathing' => $breathing_cycles
        ],
        'summary' => $ai_summary
    ]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid action.']);
?>

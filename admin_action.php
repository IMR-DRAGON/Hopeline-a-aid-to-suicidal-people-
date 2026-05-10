<?php
require_once 'config.php';
require_login();

// Ensure only admin (user_id = 1 or email team@hopeline.local) can access
if ($_SESSION['user_id'] != 1) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// Get users overview
if ($action === 'get_users') {
    $stmt = $pdo->query("SELECT id, username, email, display_name, created_at, risk_level, ai_summary, helper_badges FROM users ORDER BY id DESC");
    $users = $stmt->fetchAll();
    echo json_encode(['success' => true, 'users' => $users]);
    exit;
}

// Get pending posts
if ($action === 'get_pending_posts') {
    $stmt = $pdo->query("
        SELECT fp.id, fp.user_id, fp.title, fp.content, fp.image_path, fp.created_at, u.display_name, u.username
        FROM forum_posts fp
        JOIN users u ON u.id = fp.user_id
        WHERE fp.status = 'pending'
        ORDER BY fp.created_at DESC
    ");
    $posts = $stmt->fetchAll();
    echo json_encode(['success' => true, 'posts' => $posts]);
    exit;
}

// Approve post
if ($action === 'approve_post') {
    $post_id = intval($_POST['post_id'] ?? 0);
    $pdo->prepare("UPDATE forum_posts SET status = 'approved' WHERE id = ?")->execute([$post_id]);
    echo json_encode(['success' => true, 'message' => 'Post approved']);
    exit;
}

// Reject post
if ($action === 'reject_post') {
    $post_id = intval($_POST['post_id'] ?? 0);
    $pdo->prepare("UPDATE forum_posts SET status = 'rejected' WHERE id = ?")->execute([$post_id]);
    echo json_encode(['success' => true, 'message' => 'Post rejected']);
    exit;
}

// Reward user (Sister/Helper badge)
if ($action === 'reward_user') {
    $user_id = intval($_POST['user_id'] ?? 0);
    $pdo->prepare("UPDATE users SET helper_badges = helper_badges + 1 WHERE id = ?")->execute([$user_id]);
    echo json_encode(['success' => true, 'message' => 'User rewarded with a Helper Star!']);
    exit;
}

// Generate Mehjabeen AI Summary
if ($action === 'generate_summary') {
    $user_id = intval($_POST['user_id'] ?? 0);
    
    // Fetch recent journal entries
    $stmt = $pdo->prepare("SELECT mood_score, content, created_at FROM journal_entries WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
    $stmt->execute([$user_id]);
    $journals = $stmt->fetchAll();
    
    // Fetch recent chat messages
    $stmt = $pdo->prepare("
        SELECT cm.role, cm.content, cm.sent_at 
        FROM chat_messages cm 
        JOIN chat_sessions cs ON cm.session_id = cs.id 
        WHERE cs.user_id = ? 
        ORDER BY cm.sent_at DESC LIMIT 20
    ");
    $stmt->execute([$user_id]);
    $chats = array_reverse($stmt->fetchAll());
    
    // Prepare prompt
    $prompt = "You are Mehjabeen AI, an administrative assistant for HopeLine. Your job is to summarize the user's mental state based on their recent activity and classify their risk level.\n\n";
    
    $prompt .= "Recent Journals:\n";
    foreach ($journals as $j) {
        $prompt .= "- Date: {$j['created_at']}, Mood Score: {$j['mood_score']}/5, Notes: {$j['content']}\n";
    }
    
    $prompt .= "\nRecent Chat History:\n";
    foreach ($chats as $c) {
        $prompt .= ucfirst($c['role']) . ": {$c['content']}\n";
    }
    
    $prompt .= "\nPlease provide a short, concise summary (2-3 sentences) of the user's current condition and judge if they are improving from their suicidal condition. Also classify their risk_level strictly as one of: 'safe', 'moderate', or 'high_risk'.\n";
    $prompt .= "Return ONLY valid JSON in this exact format: {\"summary\": \"...\", \"risk_level\": \"...\"}";
    
    $payload = json_encode([
        'model' => 'LongCat-Flash-Thinking-2601',
        'messages' => [['role' => 'user', 'content' => $prompt]],
        'max_tokens' => 250,
        'temperature' => 0.3
    ]);

    $ch = curl_init('https://api.longcat.chat/openai/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . AI_API_KEY,
        ],
        CURLOPT_TIMEOUT => 45,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code === 200) {
        $data = json_decode($response, true);
        $ai_text = $data['choices'][0]['message']['content'] ?? '{}';
        // Try to extract JSON from markdown if needed
        if (preg_match('/```json(.*?)```/s', $ai_text, $matches)) {
            $ai_text = trim($matches[1]);
        }
        $ai_data = json_decode($ai_text, true);
        
        if ($ai_data && isset($ai_data['summary'], $ai_data['risk_level'])) {
            $risk = in_array($ai_data['risk_level'], ['safe', 'moderate', 'high_risk']) ? $ai_data['risk_level'] : 'moderate';
            $summary = htmlspecialchars($ai_data['summary']);
            
            $pdo->prepare("UPDATE users SET ai_summary = ?, risk_level = ? WHERE id = ?")
                ->execute([$summary, $risk, $user_id]);
                
            echo json_encode(['success' => true, 'summary' => $summary, 'risk_level' => $risk]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to parse AI response.', 'raw' => $ai_text]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'API Error: ' . $http_code]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid action']);
?>

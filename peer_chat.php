<?php
// peer_chat.php — Anonymous Peer Chat backend
require_once 'config.php';
require_login();

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$user_id = $_SESSION['user_id'];

// ── Get current status ───────────────────────────────────────────────────────
if ($action === 'status') {
    // Find if user is in an active or waiting session
    $stmt = $pdo->prepare("SELECT id, status FROM peer_chat_sessions WHERE (user1_id = ? OR user2_id = ?) AND status != 'closed' ORDER BY id DESC LIMIT 1");
    $stmt->execute([$user_id, $user_id]);
    $session = $stmt->fetch();

    if (!$session) {
        echo json_encode(['success' => true, 'has_session' => false]);
    } else {
        echo json_encode(['success' => true, 'has_session' => true, 'session_id' => $session['id'], 'status' => $session['status']]);
    }
    exit;
}

// ── Find a peer ──────────────────────────────────────────────────────────────
if ($action === 'find') {
    // Check if already in a session
    $stmt = $pdo->prepare("SELECT id FROM peer_chat_sessions WHERE (user1_id = ? OR user2_id = ?) AND status != 'closed'");
    $stmt->execute([$user_id, $user_id]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Already in a session.']);
        exit;
    }

    // Look for someone waiting
    $stmt = $pdo->prepare("SELECT id, user1_id FROM peer_chat_sessions WHERE status = 'waiting' AND user1_id != ? ORDER BY created_at ASC LIMIT 1");
    $stmt->execute([$user_id]);
    $waiting = $stmt->fetch();

    if ($waiting) {
        // Pair them up
        $pdo->prepare("UPDATE peer_chat_sessions SET user2_id = ?, status = 'active' WHERE id = ?")
            ->execute([$user_id, $waiting['id']]);

        // Add a system welcome message (ID 1 is hopeline_team)
        $pdo->prepare("INSERT INTO peer_chat_messages (session_id, sender_id, content) VALUES (?, 1, 'You are now connected with a peer. Say hi!')")
            ->execute([$waiting['id']]);

        echo json_encode(['success' => true, 'session_id' => $waiting['id'], 'status' => 'active']);
    } else {
        // Create new waiting session with is_random = 1
        $pdo->prepare("INSERT INTO peer_chat_sessions (user1_id, is_random) VALUES (?, 1)")->execute([$user_id]);
        $session_id = $pdo->lastInsertId();
        echo json_encode(['success' => true, 'session_id' => $session_id, 'status' => 'waiting']);
    }
    exit;
}

// ── Leave session ────────────────────────────────────────────────────────────
if ($action === 'leave') {
    $session_id = intval($_POST['session_id'] ?? 0);
    if ($session_id) {
        $pdo->prepare("UPDATE peer_chat_sessions SET status = 'closed' WHERE id = ? AND (user1_id = ? OR user2_id = ?)")
            ->execute([$session_id, $user_id, $user_id]);
    } else {
        // Fallback: close all active for this user (not ideal but for backward compatibility)
        $pdo->prepare("UPDATE peer_chat_sessions SET status = 'closed' WHERE (user1_id = ? OR user2_id = ?) AND status != 'closed'")
            ->execute([$user_id, $user_id]);
    }
    echo json_encode(['success' => true]);
    exit;
}

// ── Start chat with a specific user ──────────────────────────────────────────
if ($action === 'start_with_user') {
    $target_id = intval($_POST['target_user_id'] ?? 0);

    if (!$target_id || $target_id == $user_id) {
        echo json_encode(['success' => false, 'message' => 'Invalid target user.']);
        exit;
    }

    // REMOVED: automatic closing of previous sessions for persistence as requested
    /*
    $pdo->prepare("UPDATE peer_chat_sessions SET status = 'closed' WHERE (user1_id = ? OR user2_id = ?) AND status != 'closed'")
        ->execute([$user_id, $user_id]);
    */
    
    // Create new session
    $pdo->prepare("INSERT INTO peer_chat_sessions (user1_id, user2_id, status) VALUES (?, ?, 'active')")
        ->execute([$user_id, $target_id]);
    $session_id = $pdo->lastInsertId();

    // System welcome (ID 1 is hopeline_team)
    $pdo->prepare("INSERT INTO peer_chat_messages (session_id, sender_id, content) VALUES (?, 1, 'Private chat initiated. You are now connected.')")
        ->execute([$session_id]);

    echo json_encode(['success' => true, 'session_id' => $session_id]);
    exit;
}

// ── Send message ─────────────────────────────────────────────────────────────
if ($action === 'send') {
    $content = trim($_POST['content'] ?? '');
    if (!$content) {
        echo json_encode(['success' => false, 'message' => 'Message is empty.']);
        exit;
    }

    $session_id = intval($_POST['session_id'] ?? 0);
    
    if ($session_id) {
        $stmt = $pdo->prepare("SELECT id, status FROM peer_chat_sessions WHERE id = ? AND (user1_id = ? OR user2_id = ?) AND status = 'active'");
        $stmt->execute([$session_id, $user_id, $user_id]);
    } else {
        $stmt = $pdo->prepare("SELECT id, status FROM peer_chat_sessions WHERE (user1_id = ? OR user2_id = ?) AND status = 'active' ORDER BY id DESC LIMIT 1");
        $stmt->execute([$user_id, $user_id]);
    }
    $session = $stmt->fetch();

    if (!$session) {
        echo json_encode(['success' => false, 'message' => 'No active session.']);
        exit;
    }

    $pdo->prepare("INSERT INTO peer_chat_messages (session_id, sender_id, content) VALUES (?, ?, ?)")
        ->execute([$session['id'], $user_id, $content]);

    echo json_encode(['success' => true]);
    exit;
}

// ── Get chat sessions (Active or History) ────────────────────────────────────
if ($action === 'list') {
    $filter = $_GET['filter'] ?? 'active';
    $status_clause = ($filter === 'history') ? "status = 'closed'" : "status != 'closed'";
    
    $stmt = $pdo->prepare("
        SELECT id, user1_id, user2_id, status, is_random, created_at,
               (SELECT content FROM peer_chat_messages WHERE session_id = peer_chat_sessions.id ORDER BY sent_at DESC LIMIT 1) as last_msg
        FROM peer_chat_sessions
        WHERE (user1_id = ? OR user2_id = ?) AND $status_clause
        ORDER BY created_at DESC
        LIMIT 50
    ");
    $stmt->execute([$user_id, $user_id]);
    $sessions = $stmt->fetchAll();

    echo json_encode(['success' => true, 'sessions' => $sessions]);
    exit;
}

// ── Legacy History (for safety) ───────────────────────────────────────────────
if ($action === 'history') {
    $stmt = $pdo->prepare("
        SELECT id, user1_id, user2_id, status, created_at,
               (SELECT content FROM peer_chat_messages WHERE session_id = peer_chat_sessions.id ORDER BY sent_at DESC LIMIT 1) as last_msg
        FROM peer_chat_sessions
        WHERE (user1_id = ? OR user2_id = ?)
        ORDER BY created_at DESC
        LIMIT 20
    ");
    $stmt->execute([$user_id, $user_id]);
    $sessions = $stmt->fetchAll();

    echo json_encode(['success' => true, 'sessions' => $sessions]);
    exit;
}

// ── Get messages ─────────────────────────────────────────────────────────────
if ($action === 'get_messages') {
    $session_id = intval($_POST['session_id'] ?? $_GET['session_id'] ?? 0);

    if ($session_id) {
        // Verify user is part of the session
        $stmt = $pdo->prepare("SELECT id FROM peer_chat_sessions WHERE id = ? AND (user1_id = ? OR user2_id = ?)");
        $stmt->execute([$session_id, $user_id, $user_id]);
        if (!$stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Access denied.']);
            exit;
        }
    } else {
        // Fallback to latest active/waiting
        $stmt = $pdo->prepare("SELECT id FROM peer_chat_sessions WHERE (user1_id = ? OR user2_id = ?) AND status != 'closed' ORDER BY id DESC LIMIT 1");
        $stmt->execute([$user_id, $user_id]);
        $session = $stmt->fetch();
        if (!$session) {
            echo json_encode(['success' => false, 'message' => 'No active session.']);
            exit;
        }
        $session_id = $session['id'];
    }

    $stmt = $pdo->prepare("SELECT sender_id, content, sent_at FROM peer_chat_messages WHERE session_id = ? ORDER BY sent_at ASC");
    $stmt->execute([$session_id]);
    $messages = $stmt->fetchAll();

    $formatted = [];
    foreach ($messages as $msg) {
        if ($msg['sender_id'] == 1) { // hopeline_team acts as system sender
            $role = 'system';
        } else if ($msg['sender_id'] == $user_id) {
            $role = 'me';
        } else {
            $role = 'peer';
        }
        $formatted[] = [
            'role' => $role,
            'content' => $msg['content']
        ];
    }

    // Check if peer left
    $stmt = $pdo->prepare("SELECT status FROM peer_chat_sessions WHERE id = ?");
    $stmt->execute([$session_id]);
    $curr_status = $stmt->fetchColumn();

    if ($curr_status === 'closed') {
        $formatted[] = ['role' => 'system', 'content' => 'The peer has left the chat.'];
    }

    echo json_encode(['success' => true, 'messages' => $formatted, 'status' => $curr_status]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid action.']);
?>
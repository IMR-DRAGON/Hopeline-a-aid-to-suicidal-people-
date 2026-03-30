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

echo json_encode(['success' => false, 'message' => 'Invalid action.']);
?>

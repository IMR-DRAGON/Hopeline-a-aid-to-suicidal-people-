<?php
// session_check.php — returns current session info
require_once 'config.php';

header('Content-Type: application/json');

if (!is_logged_in()) {
    echo json_encode(['logged_in' => false]);
    exit;
}

// Check if a follow-up check-in is due for this user
$stmt = $pdo->prepare("
    SELECT id FROM checkins
    WHERE user_id = ? AND delivered_at IS NULL AND due_at <= NOW()
    ORDER BY due_at ASC LIMIT 1
");
$stmt->execute([$_SESSION['user_id']]);
$pending_checkin = $stmt->fetch();

echo json_encode([
    'logged_in'       => true,
    'user_id'         => $_SESSION['user_id'],
    'username'        => $_SESSION['username'],
    'display_name'    => $_SESSION['display_name'],
    'avatar_color'    => $_SESSION['avatar_color'] ?? '#7c9885',
    'pending_checkin' => $pending_checkin ? (int)$pending_checkin['id'] : false,
]);
?>

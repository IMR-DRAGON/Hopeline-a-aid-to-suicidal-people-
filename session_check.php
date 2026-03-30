<?php
// session_check.php — returns current session info
require_once 'config.php';

header('Content-Type: application/json');

if (!is_logged_in()) {
    echo json_encode(['logged_in' => false]);
    exit;
}

echo json_encode([
    'logged_in'    => true,
    'user_id'      => $_SESSION['user_id'],
    'username'     => $_SESSION['username'],
    'display_name' => $_SESSION['display_name'],
    'avatar_color' => $_SESSION['avatar_color'] ?? '#7c9885',
]);
?>

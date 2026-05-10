<?php
// auth.php — handles login, register, logout actions
require_once 'config.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

if ($action === 'register') {
    $username     = trim($_POST['username'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $password     = $_POST['password'] ?? '';
    $display_name = trim($_POST['display_name'] ?? $username);

    if (!$username || !$email || !$password) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
        exit;
    }
    if (strlen($password) < 6) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters.']);
        exit;
    }

    // Check duplicate
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Username or email already exists.']);
        exit;
    }

    $colors = ['#7c9885','#8fa8b8','#b8956a','#9b7db8','#b87d8a','#6a9fb8'];
    $color  = $colors[array_rand($colors)];
    $hash   = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, display_name, avatar_color) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$username, $email, $hash, $display_name, $color]);

    $user_id = $pdo->lastInsertId();
    $_SESSION['user_id']      = $user_id;
    $_SESSION['username']     = $username;
    $_SESSION['display_name'] = $display_name;
    $_SESSION['avatar_color'] = $color;

    echo json_encode(['success' => true, 'message' => 'Welcome to HopeLine!']);
    exit;
}

if ($action === 'login') {
    $identifier = trim($_POST['identifier'] ?? '');
    $password   = $_POST['password'] ?? '';

    if (!$identifier || !$password) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all fields.']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$identifier, $identifier]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid credentials. Please try again.']);
        exit;
    }

    $_SESSION['user_id']      = $user['id'];
    $_SESSION['username']     = $user['username'];
    $_SESSION['display_name'] = $user['display_name'];
    $_SESSION['avatar_color'] = $user['avatar_color'];

    // Update last seen
    $pdo->prepare("UPDATE users SET last_seen = NOW() WHERE id = ?")->execute([$user['id']]);

    echo json_encode([
        'success' => true, 
        'message' => 'Welcome back, ' . $user['display_name'] . '!',
        'is_admin' => ($user['id'] == 1)
    ]);
    exit;
}

if ($action === 'logout') {
    session_destroy();
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid action.']);
?>

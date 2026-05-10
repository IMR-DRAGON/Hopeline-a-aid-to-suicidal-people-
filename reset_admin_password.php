<?php
require_once 'config.php';

$email = 'team@hopeline.local';
$new_password = 'admin123';
$hash = password_hash($new_password, PASSWORD_BCRYPT);

try {
    // Check if the admin user exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Update the existing user's password
        $update = $pdo->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
        $update->execute([$hash, $email]);
        echo "<h1>Success!</h1>";
        echo "<p>The admin password has been successfully updated in the database.</p>";
    } else {
        // Create the admin user if it doesn't exist
        $insert = $pdo->prepare("INSERT INTO users (username, email, password_hash, display_name, avatar_color) VALUES ('hopeline_team', ?, ?, 'HopeLine Team', '#7c9885')");
        $insert->execute([$email, $hash]);
        echo "<h1>Success!</h1>";
        echo "<p>The admin user was created successfully.</p>";
    }

    echo "<h3>Your Admin Login Details:</h3>";
    echo "<ul>";
    echo "<li><strong>Email:</strong> team@hopeline.local</li>";
    echo "<li><strong>Password:</strong> admin123</li>";
    echo "</ul>";
    echo "<p><a href='login.php'>Click here to go to the login page</a></p>";
    echo "<p style='color: red;'><strong>Important:</strong> Please delete this file (<code>reset_admin_password.php</code>) after you have logged in, as it poses a security risk to leave it on the server.</p>";

} catch (PDOException $e) {
    echo "<h1>Database Error</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>

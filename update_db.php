<?php
require_once 'config.php';
try {
    // Current activity logging table
    $pdo->exec("CREATE TABLE IF NOT EXISTS user_activities (
        id INT AUTO_INCREMENT PRIMARY KEY, 
        user_id INT NOT NULL, 
        activity_type VARCHAR(50) NOT NULL, 
        activity_value INT DEFAULT 1, 
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP, 
        INDEX(user_id),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");

    // New check-ins table
    $pdo->exec("CREATE TABLE IF NOT EXISTS checkins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        trigger_session_id INT DEFAULT NULL,
        due_at DATETIME NOT NULL,
        delivered_at DATETIME DEFAULT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        INDEX(user_id),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (trigger_session_id) REFERENCES chat_sessions(id) ON DELETE SET NULL
    )");

    echo "Database tables updated successfully! 💚";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

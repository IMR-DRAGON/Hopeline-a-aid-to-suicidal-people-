<?php
require_once 'config.php';
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS user_activities (
        id INT AUTO_INCREMENT PRIMARY KEY, 
        user_id INT NOT NULL, 
        activity_type VARCHAR(50) NOT NULL, 
        activity_value INT DEFAULT 1, 
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP, 
        INDEX(user_id),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");
    echo "Table created successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

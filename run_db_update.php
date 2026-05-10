<?php
require_once 'config.php';

try {
    // Add columns to users table
    $pdo->exec("ALTER TABLE users ADD COLUMN risk_level ENUM('safe', 'moderate', 'high_risk') DEFAULT 'moderate'");
    $pdo->exec("ALTER TABLE users ADD COLUMN ai_summary TEXT DEFAULT NULL");
    $pdo->exec("ALTER TABLE users ADD COLUMN helper_badges INT DEFAULT 0");

    // Add status column to forum_posts table
    $pdo->exec("ALTER TABLE forum_posts ADD COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
    
    // Update existing posts to be approved
    $pdo->exec("UPDATE forum_posts SET status = 'approved'");
    
    echo "Database successfully updated for Admin Panel.";
} catch (PDOException $e) {
    // Ignore duplicate column errors if they already exist
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Columns already exist. DB is ready.";
    } else {
        echo "Error updating DB: " . $e->getMessage();
    }
}
?>

-- HopeLine Database Schema
-- Run this in phpMyAdmin or MySQL CLI

CREATE DATABASE IF NOT EXISTS hopeline;
USE hopeline;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    display_name VARCHAR(80),
    avatar_color VARCHAR(7) DEFAULT '#7c9885',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_seen DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Chat sessions with AI
CREATE TABLE chat_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    started_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Individual chat messages
CREATE TABLE chat_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id INT NOT NULL,
    role ENUM('user', 'assistant') NOT NULL,
    content TEXT NOT NULL,
    sent_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (session_id) REFERENCES chat_sessions(id) ON DELETE CASCADE
);

-- Mood journal entries
CREATE TABLE journal_entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    mood_score TINYINT NOT NULL COMMENT '1=Very low, 2=Low, 3=Okay, 4=Good, 5=Great',
    mood_label VARCHAR(30),
    content TEXT,
    is_anonymous TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Community forum posts
CREATE TABLE forum_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    image_path VARCHAR(255) DEFAULT NULL,
    is_anonymous TINYINT(1) DEFAULT 0,
    pinned TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Forum replies
CREATE TABLE forum_replies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    image_path VARCHAR(255) DEFAULT NULL,
    is_anonymous TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES forum_posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Anonymous Peer Chat Sessions
CREATE TABLE peer_chat_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user1_id INT NOT NULL,
    user2_id INT,
    status ENUM('waiting', 'active', 'closed') DEFAULT 'waiting',
    is_random TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user1_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (user2_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Anonymous Peer Chat Messages
CREATE TABLE peer_chat_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id INT NOT NULL,
    sender_id INT NOT NULL,
    content TEXT NOT NULL,
    sent_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (session_id) REFERENCES peer_chat_sessions(id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Forum support reactions (heart / "I hear you")
CREATE TABLE forum_reactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT,
    reply_id INT,
    user_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES forum_posts(id) ON DELETE CASCADE,
    FOREIGN KEY (reply_id) REFERENCES forum_replies(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Sample pinned welcome post
INSERT INTO users (username, email, password_hash, display_name, avatar_color)
VALUES ('hopeline_team', 'team@hopeline.local', '$2y$10$placeholder', 'HopeLine Team', '#7c9885');

INSERT INTO forum_posts (user_id, title, content, pinned)
VALUES (1, 'Welcome to HopeLine Community 💚', 
'This is a safe space for you. You are not alone.\n\nYou can share how you feel, ask for support, or simply read others\' stories. Everyone here is on their own journey, and we support each other with kindness.\n\n**Community Guidelines:**\n- Be kind and supportive — no judgment here\n- You can post anonymously at any time\n- If you or someone else is in immediate danger, please call emergency services\n- Our AI companion is available 24/7 in the Chat tab\n\nYou matter. We\'re glad you\'re here. 💚', 1);

-- Safety Plans
CREATE TABLE safety_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    warning_signs TEXT,
    coping_strategies TEXT,
    people_distractions TEXT,
    people_help TEXT,
    professionals TEXT,
    environment_safe TEXT,
    reasons_to_live TEXT,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE(user_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

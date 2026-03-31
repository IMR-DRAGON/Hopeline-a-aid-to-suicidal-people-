<?php
// forum.php — Community forum backend
require_once 'config.php';
require_login();

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// ── List posts ────────────────────────────────────────────────────────────────
if ($action === 'list') {
    $stmt = $pdo->query("
        SELECT fp.id, fp.user_id, fp.title, fp.content, fp.image_path, fp.is_anonymous, fp.pinned, fp.created_at,
               u.display_name, u.avatar_color,
               (SELECT COUNT(*) FROM forum_replies fr WHERE fr.post_id = fp.id) as reply_count,
               (SELECT COUNT(*) FROM forum_reactions frc WHERE frc.post_id = fp.id) as heart_count
        FROM forum_posts fp
        JOIN users u ON u.id = fp.user_id
        ORDER BY fp.pinned DESC, fp.created_at DESC
        LIMIT 50
    ");
    $posts = $stmt->fetchAll();

    foreach ($posts as &$post) {
        if ($post['is_anonymous']) {
            $post['display_name'] = 'Anonymous';
            $post['avatar_color'] = '#aaaaaa';
            $post['badge'] = null;
        } else {
            $post['badge'] = get_user_badge((int)$post['user_id'], $pdo);
        }
    }

    echo json_encode(['success' => true, 'posts' => $posts]);
    exit;
}

// ── Create a post ─────────────────────────────────────────────────────────────
if ($action === 'create_post') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $is_anonymous = intval($_POST['is_anonymous'] ?? 0);

    if (!$title || !$content) {
        echo json_encode(['success' => false, 'message' => 'Title and content are required.']);
        exit;
    }
    if (strlen($title) > 200) {
        echo json_encode(['success' => false, 'message' => 'Title too long (max 200 chars).']);
        exit;
    }

    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $filename = uniqid('post_') . '.' . $ext;
            if (!is_dir('uploads'))
                mkdir('uploads', 0777, true);
            move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $filename);
            $image_path = 'uploads/' . $filename;
        }
    }

    $pdo->prepare("INSERT INTO forum_posts (user_id, title, content, is_anonymous, image_path) VALUES (?, ?, ?, ?, ?)")
        ->execute([$_SESSION['user_id'], $title, $content, $is_anonymous, $image_path]);

    echo json_encode(['success' => true, 'message' => 'Post shared with the community 💚']);
    exit;
}

// ── Get single post with replies ──────────────────────────────────────────────
if ($action === 'get_post') {
    $post_id = intval($_GET['post_id'] ?? 0);

    $stmt = $pdo->prepare("
        SELECT fp.*, u.display_name, u.avatar_color,
               (SELECT COUNT(*) FROM forum_reactions frc WHERE frc.post_id = fp.id) as heart_count
        FROM forum_posts fp JOIN users u ON u.id = fp.user_id WHERE fp.id = ?
    ");
    $stmt->execute([$post_id]);
    $post = $stmt->fetch();

    if (!$post) {
        echo json_encode(['success' => false, 'message' => 'Post not found.']);
        exit;
    }
    if ($post['is_anonymous']) {
        $post['display_name'] = 'Anonymous';
        $post['avatar_color'] = '#aaaaaa';
        $post['badge'] = null;
    } else {
        $post['badge'] = get_user_badge((int)$post['user_id'], $pdo);
    }

    // Check if current user has hearted this post
    $stmt = $pdo->prepare("SELECT id FROM forum_reactions WHERE post_id = ? AND user_id = ?");
    $stmt->execute([$post_id, $_SESSION['user_id']]);
    $post['user_hearted'] = (bool) $stmt->fetch();

    $stmt = $pdo->prepare("
        SELECT fr.id, fr.user_id, fr.content, fr.image_path, fr.is_anonymous, fr.created_at,
               u.display_name, u.avatar_color,
               (SELECT COUNT(*) FROM forum_reactions frc WHERE frc.reply_id = fr.id) as heart_count
        FROM forum_replies fr JOIN users u ON u.id = fr.user_id
        WHERE fr.post_id = ? ORDER BY fr.created_at ASC
    ");
    $stmt->execute([$post_id]);
    $replies = $stmt->fetchAll();

    foreach ($replies as &$reply) {
        if ($reply['is_anonymous']) {
            $reply['display_name'] = 'Anonymous';
            $reply['avatar_color'] = '#aaaaaa';
            $reply['badge'] = null;
        } else {
            $reply['badge'] = get_user_badge((int)$reply['user_id'], $pdo);
        }
        $stmt2 = $pdo->prepare("SELECT id FROM forum_reactions WHERE reply_id = ? AND user_id = ?");
        $stmt2->execute([$reply['id'], $_SESSION['user_id']]);
        $reply['user_hearted'] = (bool) $stmt2->fetch();
    }

    echo json_encode(['success' => true, 'post' => $post, 'replies' => $replies]);
    exit;
}

// ── Reply to a post ───────────────────────────────────────────────────────────
if ($action === 'reply') {
    $post_id = intval($_POST['post_id'] ?? 0);
    $content = trim($_POST['content'] ?? '');
    $is_anonymous = intval($_POST['is_anonymous'] ?? 0);

    if (!$post_id || !$content) {
        echo json_encode(['success' => false, 'message' => 'Content required.']);
        exit;
    }

    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $filename = uniqid('reply_') . '.' . $ext;
            if (!is_dir('uploads'))
                mkdir('uploads', 0777, true);
            move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $filename);
            $image_path = 'uploads/' . $filename;
        }
    }

    $pdo->prepare("INSERT INTO forum_replies (post_id, user_id, content, is_anonymous, image_path) VALUES (?, ?, ?, ?, ?)")
        ->execute([$post_id, $_SESSION['user_id'], $content, $is_anonymous, $image_path]);

    echo json_encode(['success' => true, 'message' => 'Reply posted 💚']);
    exit;
}

// ── Heart / unheart ───────────────────────────────────────────────────────────
if ($action === 'toggle_heart') {
    $post_id = intval($_POST['post_id'] ?? 0) ?: null;
    $reply_id = intval($_POST['reply_id'] ?? 0) ?: null;

    if (!$post_id && !$reply_id) {
        echo json_encode(['success' => false]);
        exit;
    }

    $stmt = $pdo->prepare("SELECT id FROM forum_reactions WHERE post_id <=> ? AND reply_id <=> ? AND user_id = ?");
    $stmt->execute([$post_id, $reply_id, $_SESSION['user_id']]);
    $existing = $stmt->fetch();

    if ($existing) {
        $pdo->prepare("DELETE FROM forum_reactions WHERE id = ?")->execute([$existing['id']]);
        echo json_encode(['success' => true, 'hearted' => false]);
    } else {
        $pdo->prepare("INSERT INTO forum_reactions (post_id, reply_id, user_id) VALUES (?, ?, ?)")
            ->execute([$post_id, $reply_id, $_SESSION['user_id']]);
        echo json_encode(['success' => true, 'hearted' => true]);
    }
    exit;
}

// ── Edit a post ───────────────────────────────────────────────────────────────
if ($action === 'edit_post') {
    $post_id = intval($_POST['post_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $is_anonymous = intval($_POST['is_anonymous'] ?? 0);

    if (!$post_id || !$title || !$content) {
        echo json_encode(['success' => false, 'message' => 'Post ID, title, and content are required.']);
        exit;
    }

    // Verify ownership
    $stmt = $pdo->prepare("SELECT id, image_path FROM forum_posts WHERE id = ? AND user_id = ?");
    $stmt->execute([$post_id, $_SESSION['user_id']]);
    $post = $stmt->fetch();

    if (!$post) {
        echo json_encode(['success' => false, 'message' => 'Post not found or access denied.']);
        exit;
    }

    $image_path = $post['image_path'];
    // Handle new image upload if provided
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $filename = uniqid('post_edit_') . '.' . $ext;
            if (!is_dir('uploads'))
                mkdir('uploads', 0777, true);
            move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $filename);
            $image_path = 'uploads/' . $filename;
        }
    }

    $pdo->prepare("UPDATE forum_posts SET title = ?, content = ?, is_anonymous = ?, image_path = ? WHERE id = ?")
        ->execute([$title, $content, $is_anonymous, $image_path, $post_id]);

    echo json_encode(['success' => true, 'message' => 'Post updated successfully 🌿']);
    exit;
}

// ── Delete a post ─────────────────────────────────────────────────────────────
if ($action === 'delete_post') {
    $post_id = intval($_POST['post_id'] ?? 0);

    if (!$post_id) {
        echo json_encode(['success' => false, 'message' => 'Post ID required.']);
        exit;
    }

    // Verify ownership
    $stmt = $pdo->prepare("SELECT id FROM forum_posts WHERE id = ? AND user_id = ?");
    $stmt->execute([$post_id, $_SESSION['user_id']]);
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Post not found or access denied.']);
        exit;
    }

    $pdo->prepare("DELETE FROM forum_posts WHERE id = ?")->execute([$post_id]);
    echo json_encode(['success' => true, 'message' => 'Post deleted.']);
    exit;
}

// ── Edit a reply ──────────────────────────────────────────────────────────────
if ($action === 'edit_reply') {
    $reply_id = intval($_POST['reply_id'] ?? 0);
    $content = trim($_POST['content'] ?? '');
    $is_anonymous = intval($_POST['is_anonymous'] ?? 0);

    if (!$reply_id || !$content) {
        echo json_encode(['success' => false, 'message' => 'Reply ID and content are required.']);
        exit;
    }

    // Verify ownership
    $stmt = $pdo->prepare("SELECT id, image_path FROM forum_replies WHERE id = ? AND user_id = ?");
    $stmt->execute([$reply_id, $_SESSION['user_id']]);
    $reply = $stmt->fetch();

    if (!$reply) {
        echo json_encode(['success' => false, 'message' => 'Reply not found or access denied.']);
        exit;
    }

    $image_path = $reply['image_path'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $filename = uniqid('reply_edit_') . '.' . $ext;
            if (!is_dir('uploads')) mkdir('uploads', 0777, true);
            move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $filename);
            $image_path = 'uploads/' . $filename;
        }
    }

    $pdo->prepare("UPDATE forum_replies SET content = ?, is_anonymous = ?, image_path = ? WHERE id = ?")
        ->execute([$content, $is_anonymous, $image_path, $reply_id]);

    echo json_encode(['success' => true, 'message' => 'Reply updated successfully 🌿']);
    exit;
}

// ── Delete a reply ────────────────────────────────────────────────────────────
if ($action === 'delete_reply') {
    $reply_id = intval($_POST['reply_id'] ?? 0);

    if (!$reply_id) {
        echo json_encode(['success' => false, 'message' => 'Reply ID required.']);
        exit;
    }

    // Verify ownership
    $stmt = $pdo->prepare("SELECT id FROM forum_replies WHERE id = ? AND user_id = ?");
    $stmt->execute([$reply_id, $_SESSION['user_id']]);
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Reply not found or access denied.']);
        exit;
    }

    $pdo->prepare("DELETE FROM forum_replies WHERE id = ?")->execute([$reply_id]);
    echo json_encode(['success' => true, 'message' => 'Reply deleted.']);
    exit;
}

// ── Leaderboard ───────────────────────────────────────────────────────────────
if ($action === 'leaderboard') {
    $stmt = $pdo->query("
        SELECT
            u.id,
            u.display_name,
            u.avatar_color,
            (
                (SELECT COUNT(*) FROM forum_reactions WHERE post_id  IN (SELECT id FROM forum_posts    WHERE user_id = u.id)) +
                (SELECT COUNT(*) FROM forum_reactions WHERE reply_id IN (SELECT id FROM forum_replies  WHERE user_id = u.id))
            ) AS total_hearts,
            (SELECT COUNT(*) FROM forum_posts    WHERE user_id = u.id) AS post_count,
            (SELECT COUNT(*) FROM forum_replies  WHERE user_id = u.id) AS reply_count
        FROM users u
        ORDER BY total_hearts DESC, post_count DESC
        LIMIT 10
    ");
    $leaders = $stmt->fetchAll();

    foreach ($leaders as &$leader) {
        $leader['badge'] = get_user_badge((int)$leader['id'], $pdo);
    }

    // Also include current user's badge
    $my_badge = get_user_badge((int)$_SESSION['user_id'], $pdo);

    echo json_encode(['success' => true, 'leaders' => $leaders, 'my_badge' => $my_badge]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid action.']);
?>
<?php
require_once 'config.php';
require_once 'config.php';

if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}

// Restrict to Admin
if ($_SESSION['user_id'] != 1) {
    echo "<h1>Unauthorized Access</h1>";
    echo "<p>You must be an admin to view this page. Please <a href='auth.php?action=logout' onclick=\"event.preventDefault(); fetch('auth.php', {method: 'POST', body: new URLSearchParams({'action': 'logout'})}).then(()=>window.location='login.php');\">log out</a> and log in as the admin.</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HopeLine Admin Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #7c9885;
            --primary-deep: #4d6b55;
            --bg: #f8f9fa;
            --card-bg: #ffffff;
            --text-main: #2d3436;
            --text-soft: #636e72;
            --white: #ffffff;
            --danger: #ff7675;
            --danger-bg: #ffefef;
            --safe: #55efc4;
            --safe-bg: #e8fcf5;
            --warning: #fdcb6e;
            --warning-bg: #fff9ed;
            --radius: 16px;
            --shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--card-bg);
            border-right: 1px solid rgba(0,0,0,0.05);
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            box-shadow: 4px 0 20px rgba(0,0,0,0.02);
            z-index: 10;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 50px;
            padding: 0 10px;
        }

        .brand-icon { font-size: 28px; }
        .brand-text h2 { font-family: 'Lora', serif; color: var(--primary-deep); font-size: 22px; }
        .brand-text span { font-size: 12px; color: var(--text-soft); font-weight: 600; letter-spacing: 1px; text-transform: uppercase; }

        .nav-item {
            padding: 14px 20px;
            border-radius: 12px;
            cursor: pointer;
            color: var(--text-soft);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s;
            margin-bottom: 8px;
        }

        .nav-item:hover {
            background: #f1f4f2;
            color: var(--primary-deep);
            transform: translateX(5px);
        }

        .nav-item.active {
            background: var(--primary);
            color: var(--white);
            box-shadow: 0 4px 15px rgba(124, 152, 133, 0.3);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 40px;
            max-width: 1400px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .header h1 {
            font-family: 'Lora', serif;
            font-size: 32px;
            color: var(--text-main);
        }

        .view-panel { display: none; animation: fadeIn 0.4s ease; }
        .view-panel.active { display: block; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Cards & Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: var(--card-bg);
            padding: 24px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid rgba(0,0,0,0.02);
            transition: transform 0.3s;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 4px;
            background: var(--primary);
        }

        .stat-card.red-zone::before { background: var(--danger); }
        .stat-card.safe-zone::before { background: var(--safe); }

        .stat-card:hover { transform: translateY(-5px); }
        .stat-value { font-size: 36px; font-weight: 700; color: var(--primary-deep); margin-bottom: 4px; }
        .stat-label { font-size: 14px; color: var(--text-soft); font-weight: 500; }

        /* Tables */
        .table-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 18px 24px; text-align: left; }
        th { background: #f8f9fa; color: var(--text-soft); font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; border-bottom: 1px solid #edf2f7; }
        td { border-bottom: 1px solid #edf2f7; font-size: 14px; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #fcfcfc; }

        /* Badges */
        .risk-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            text-transform: capitalize;
        }
        .risk-badge.high_risk { background: var(--danger-bg); color: var(--danger); }
        .risk-badge.safe { background: var(--safe-bg); color: #00b894; }
        .risk-badge.moderate { background: var(--warning-bg); color: #e17055; }

        /* Buttons */
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-outline { background: transparent; border: 1.5px solid var(--primary); color: var(--primary); }
        .btn-outline:hover { background: var(--primary); color: var(--white); }
        
        .btn-approve { background: var(--safe-bg); color: #00b894; border: 1.5px solid #00b894; }
        .btn-approve:hover { background: #00b894; color: var(--white); }
        
        .btn-reject { background: var(--danger-bg); color: var(--danger); border: 1.5px solid var(--danger); }
        .btn-reject:hover { background: var(--danger); color: var(--white); }

        .btn-reward { background: #fff0f6; color: #fd79a8; border: 1.5px solid #fd79a8; }
        .btn-reward:hover { background: #fd79a8; color: var(--white); transform: scale(1.05); }

        /* AI Summary Box */
        .ai-summary-box {
            font-size: 13px;
            color: var(--text-soft);
            background: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
            border-left: 3px solid var(--primary);
            max-width: 400px;
            line-height: 1.5;
        }
        
        .loader {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid rgba(124, 152, 133, 0.3);
            border-radius: 50%;
            border-top-color: var(--primary);
            animation: spin 1s ease-in-out infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        
        .empty-state {
            padding: 60px;
            text-align: center;
            color: var(--text-soft);
        }
        .empty-state i { font-size: 40px; margin-bottom: 16px; opacity: 0.5; display: block;}
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">
            <div class="brand-icon">🌿</div>
            <div class="brand-text">
                <h2>HopeLine</h2>
                <span>Admin Portal</span>
            </div>
        </div>
        
        <div class="nav-item active" onclick="switchTab('dashboard')">
            📊 Dashboard
        </div>
        <div class="nav-item" onclick="switchTab('users')">
            👥 User Monitoring
        </div>
        <div class="nav-item" onclick="switchTab('moderation')">
            🛡️ Community Moderation
        </div>
        <div style="flex:1"></div>
        <a href="app.php" style="text-decoration:none;">
            <div class="nav-item" style="color:var(--danger)">
                ← Back to App
            </div>
        </a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        
        <!-- Tab: Dashboard -->
        <div id="tab-dashboard" class="view-panel active">
            <div class="header">
                <h1>Overview</h1>
                <div style="color:var(--text-soft)">Welcome back, Admin</div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value" id="stat-total-users">0</div>
                    <div class="stat-label">Total Users</div>
                </div>
                <div class="stat-card red-zone">
                    <div class="stat-value" id="stat-red-zone" style="color:var(--danger)">0</div>
                    <div class="stat-label">Users in Red Zone</div>
                </div>
                <div class="stat-card safe-zone">
                    <div class="stat-value" id="stat-safe-zone" style="color:#00b894">0</div>
                    <div class="stat-label">Users in Safe Zone</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="stat-pending-posts">0</div>
                    <div class="stat-label">Pending Posts</div>
                </div>
            </div>
        </div>

        <!-- Tab: User Monitoring -->
        <div id="tab-users" class="view-panel">
            <div class="header">
                <h1>User Monitoring</h1>
                <button class="btn btn-outline" onclick="loadUsers()">↻ Refresh Data</button>
            </div>
            
            <div class="table-card">
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Status (Mehjabeen AI)</th>
                            <th>AI Daily Summary</th>
                            <th>Helper Stars</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="users-tbody">
                        <!-- Populated via JS -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab: Community Moderation -->
        <div id="tab-moderation" class="view-panel">
            <div class="header">
                <h1>Community Moderation</h1>
                <p style="color:var(--text-soft)">Approve posts before they appear on the community feed.</p>
            </div>
            
            <div class="table-card">
                <table>
                    <thead>
                        <tr>
                            <th>Author</th>
                            <th>Post Content</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="moderation-tbody">
                        <!-- Populated via JS -->
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        // Tab switching logic
        function switchTab(tabId) {
            document.querySelectorAll('.view-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
            
            document.getElementById('tab-' + tabId).classList.add('active');
            event.currentTarget.classList.add('active');

            if (tabId === 'users' || tabId === 'dashboard') loadUsers();
            if (tabId === 'moderation' || tabId === 'dashboard') loadPendingPosts();
        }

        // Fetch Users Data
        async function loadUsers() {
            const tbody = document.getElementById('users-tbody');
            
            try {
                const formData = new FormData();
                formData.append('action', 'get_users');
                const res = await fetch('admin_action.php', { method: 'POST', body: formData });
                const data = await res.json();
                
                if (data.success) {
                    tbody.innerHTML = '';
                    let redCount = 0;
                    let safeCount = 0;

                    data.users.forEach(u => {
                        // Exclude admin account from monitor if needed, but we can show it
                        if (u.id == 1) return; 

                        if(u.risk_level === 'high_risk') redCount++;
                        if(u.risk_level === 'safe') safeCount++;

                        const badgeClass = u.risk_level === 'high_risk' ? 'high_risk' : (u.risk_level === 'safe' ? 'safe' : 'moderate');
                        const riskLabel = u.risk_level ? u.risk_level.replace('_', ' ') : 'unassessed';
                        const summaryText = u.ai_summary ? u.ai_summary : '<em>No summary generated yet.</em>';
                        
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>
                                <strong>${u.display_name}</strong><br>
                                <span style="font-size:12px; color:var(--text-soft)">@${u.username}</span>
                            </td>
                            <td><span class="risk-badge ${badgeClass}">${riskLabel}</span></td>
                            <td><div class="ai-summary-box" id="summary-text-${u.id}">${summaryText}</div></td>
                            <td>🌟 ${u.helper_badges}</td>
                            <td>
                                <button class="btn btn-outline" id="btn-ai-${u.id}" onclick="generateSummary(${u.id})">Run AI Assessment</button>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });

                    // Update dashboard stats
                    document.getElementById('stat-total-users').innerText = data.users.length - 1; // minus admin
                    document.getElementById('stat-red-zone').innerText = redCount;
                    document.getElementById('stat-safe-zone').innerText = safeCount;
                }
            } catch (err) {
                console.error('Error loading users:', err);
            }
        }

        // Fetch Pending Posts
        async function loadPendingPosts() {
            const tbody = document.getElementById('moderation-tbody');
            
            try {
                const formData = new FormData();
                formData.append('action', 'get_pending_posts');
                const res = await fetch('admin_action.php', { method: 'POST', body: formData });
                const data = await res.json();
                
                if (data.success) {
                    tbody.innerHTML = '';
                    
                    document.getElementById('stat-pending-posts').innerText = data.posts.length;

                    if (data.posts.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="4"><div class="empty-state">🎉 All caught up! No pending posts.</div></td></tr>`;
                        return;
                    }

                    data.posts.forEach(p => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>
                                <strong>${p.display_name}</strong><br>
                                <span style="font-size:12px; color:var(--text-soft)">@${p.username}</span>
                            </td>
                            <td>
                                <strong>${p.title}</strong><br>
                                <span style="font-size:13px; color:var(--text-soft)">${p.content.substring(0, 100)}${p.content.length > 100 ? '...' : ''}</span>
                                ${p.image_path ? `<br><img src="${p.image_path}" style="max-height:60px; margin-top:8px; border-radius:4px;">` : ''}
                            </td>
                            <td style="font-size:12px; color:var(--text-soft)">${p.created_at}</td>
                            <td>
                                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                    <button class="btn btn-approve" onclick="actionPost(${p.id}, 'approve_post')">✓ Approve</button>
                                    <button class="btn btn-reject" onclick="actionPost(${p.id}, 'reject_post')">✕ Reject</button>
                                    <button class="btn btn-reward" onclick="rewardUser(${p.user_id}, this)">🎁 Reward Helper Star</button>
                                </div>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                }
            } catch (err) {
                console.error('Error loading posts:', err);
            }
        }

        // AI Summary Action
        async function generateSummary(userId) {
            const btn = document.getElementById('btn-ai-' + userId);
            const sumBox = document.getElementById('summary-text-' + userId);
            
            const originalText = btn.innerText;
            btn.innerHTML = '<span class="loader"></span> Analyzing...';
            btn.disabled = true;

            try {
                const formData = new FormData();
                formData.append('action', 'generate_summary');
                formData.append('user_id', userId);
                
                const res = await fetch('admin_action.php', { method: 'POST', body: formData });
                const data = await res.json();
                
                if (data.success) {
                    sumBox.innerHTML = `<strong>Updated:</strong> ${data.summary}`;
                    // Flash effect
                    sumBox.style.backgroundColor = '#e8fcf5';
                    setTimeout(() => sumBox.style.backgroundColor = '#f8f9fa', 1000);
                    // Reload users to update badges
                    setTimeout(loadUsers, 1500); 
                } else {
                    alert('Error generating summary: ' + data.message);
                    btn.innerText = originalText;
                    btn.disabled = false;
                }
            } catch (err) {
                alert('Network error');
                btn.innerText = originalText;
                btn.disabled = false;
            }
        }

        // Post Actions
        async function actionPost(postId, actionStr) {
            try {
                const formData = new FormData();
                formData.append('action', actionStr);
                formData.append('post_id', postId);
                
                const res = await fetch('admin_action.php', { method: 'POST', body: formData });
                const data = await res.json();
                
                if (data.success) {
                    loadPendingPosts();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (err) {
                alert('Network error');
            }
        }

        // Reward User
        async function rewardUser(userId, btnElement) {
            btnElement.disabled = true;
            btnElement.innerText = 'Rewarded! 🌟';
            
            try {
                const formData = new FormData();
                formData.append('action', 'reward_user');
                formData.append('user_id', userId);
                
                await fetch('admin_action.php', { method: 'POST', body: formData });
                // We'll let the next refresh update the star count
            } catch (err) {
                alert('Network error');
            }
        }

        // Initial Load
        window.onload = () => {
            loadUsers();
            loadPendingPosts();
        };
    </script>
</body>
</html>

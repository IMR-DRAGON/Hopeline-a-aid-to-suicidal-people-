<?php
require_once 'config.php';
if (!is_logged_in()) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HopeLine — You Are Not Alone</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link
    href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap"
    rel="stylesheet">
  <style>
    /* ═══════════════════════════════════════════════
   DESIGN TOKENS
═══════════════════════════════════════════════ */
    :root {
      --sage: #7c9885;
      --sage-light: #a8c4b0;
      --sage-pale: #e8f0ea;
      --sage-deep: #4d6b55;
      --warm: #f5f0e8;
      --warm-mid: #ede4d3;
      --cream: #faf8f4;
      --text: #2d2d2d;
      --text-soft: #6b6b6b;
      --text-pale: #9a9a9a;
      --white: #ffffff;
      --danger: #c0392b;
      --danger-bg: #fdf0ee;
      --radius: 16px;
      --radius-sm: 10px;
      --shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
    }

    [data-theme="dark"] {
      --sage: #6e8c78;
      --sage-light: #3d4a42;
      --sage-pale: #242c26;
      --sage-deep: #a6cfb1;
      --warm: #2a2825;
      --warm-mid: #3b3834;
      --cream: #121312;
      --text: #e8e8e8;
      --text-soft: #a8a8a8;
      --text-pale: #757575;
      --white: #1b1c1b;
      --danger: #e25c56;
      --danger-bg: #422320;
      --shadow: 0 4px 24px rgba(0, 0, 0, 0.5);
      --shadow-sm: 0 2px 12px rgba(0, 0, 0, 0.3);
    }

    [data-theme="dark"] body::before {
      background-image: radial-gradient(circle at 20% 20%, rgba(124, 152, 133, 0.03) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(168, 196, 176, 0.03) 0%, transparent 50%);
    }

    [data-theme="dark"] .crisis-banner {
      border-bottom-color: #5a312d;
      color: #ffb4a9;
    }

    [data-theme="dark"] .crisis-banner strong {
      color: #ff8e8e;
    }

    [data-theme="dark"] .auth-card,
    [data-theme="dark"] .chat-container,
    [data-theme="dark"] .journal-form,
    [data-theme="dark"] .mood-chart-wrap,
    [data-theme="dark"] .entry-card,
    [data-theme="dark"] .hotline-card,
    [data-theme="dark"] .tip-card,
    [data-theme="dark"] .forum-compose,
    [data-theme="dark"] .post-card,
    [data-theme="dark"] .modal-card,
    [data-theme="dark"] .msg.assistant .msg-bubble,
    [data-theme="dark"] .typing-indicator {
      box-shadow: var(--shadow-sm), 0 0 0 1px rgba(255, 255, 255, 0.05);
    }

    [data-theme="dark"] .nav-tab,
    [data-theme="dark"] .btn-new-chat {
      border: 1.5px solid var(--sage);
      background: rgba(110, 140, 120, 0.15);
      color: var(--sage-deep);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
    }

    [data-theme="dark"] .nav-tab:hover,
    [data-theme="dark"] .btn-new-chat:hover {
      background: var(--sage);
      color: var(--white);
      transform: translateY(-2px);
      box-shadow: 0 6px 18px rgba(110, 140, 120, 0.4);
    }

    [data-theme="dark"] .nav-tab.active {
      background: var(--sage-deep);
      color: var(--white);
      border-color: var(--white);
      box-shadow: 0 0 15px rgba(166, 207, 177, 0.3);
    }

    [data-theme="dark"] #btn-leave-peer {
      border: 1.5px solid var(--danger);
      background: var(--danger-bg);
      color: #ffb4a9;
      box-shadow: 0 4px 12px rgba(226, 92, 86, 0.3);
    }

    [data-theme="dark"] .history-card {
      background: var(--sage-pale);
      border: 1px solid var(--sage-light);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    [data-theme="dark"] .history-card:hover {
      border-color: var(--sage-deep);
      background: var(--sage-light);
      transform: translateY(-2px);
    }

    /* ═══════════════════════════════════════════════
   RESET & BASE
═══════════════════════════════════════════════ */
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    html,
    body {
      height: 100%;
      font-family: 'DM Sans', sans-serif;
      background: var(--cream);
      color: var(--text);
      font-size: 15px;
      line-height: 1.6;
    }

    /* Background texture */
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background-image: radial-gradient(circle at 20% 20%, rgba(124, 152, 133, 0.06) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(168, 196, 176, 0.06) 0%, transparent 50%);
      pointer-events: none;
      z-index: 0;
    }

    /* ═══════════════════════════════════════════════
   CRISIS BANNER — always visible
═══════════════════════════════════════════════ */
    .crisis-banner {
      background: var(--danger-bg);
      border-bottom: 1px solid #f0c0b8;
      text-align: center;
      padding: 8px 16px;
      font-size: 13px;
      color: #8b2e22;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
    }

    .crisis-banner strong {
      color: var(--danger);
    }

    /* ═══════════════════════════════════════════════
   AUTH SCREEN
═══════════════════════════════════════════════ */
    #auth-screen {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 80px 16px 40px;
      position: relative;
      z-index: 1;
    }

    .auth-card {
      background: var(--white);
      border-radius: 24px;
      box-shadow: var(--shadow), 0 0 0 1px rgba(124, 152, 133, 0.1);
      padding: 48px 40px;
      width: 100%;
      max-width: 440px;
      animation: fadeUp 0.5s ease;
    }

    .auth-logo {
      text-align: center;
      margin-bottom: 32px;
    }

    .auth-logo .leaf {
      font-size: 40px;
    }

    .auth-logo h1 {
      font-family: 'Lora', serif;
      font-size: 28px;
      color: var(--sage-deep);
      margin-top: 8px;
    }

    .auth-logo p {
      color: var(--text-soft);
      font-size: 14px;
      margin-top: 4px;
    }

    .tab-btns {
      display: flex;
      background: var(--sage-pale);
      border-radius: var(--radius-sm);
      padding: 4px;
      margin-bottom: 28px;
    }

    .tab-btn {
      flex: 1;
      padding: 10px;
      border: none;
      background: transparent;
      border-radius: 8px;
      font-family: 'DM Sans', sans-serif;
      font-size: 14px;
      font-weight: 500;
      color: var(--text-soft);
      cursor: pointer;
      transition: all 0.2s;
    }

    .tab-btn.active {
      background: var(--white);
      color: var(--sage-deep);
      box-shadow: 0 1px 6px rgba(0, 0, 0, 0.08);
    }

    .form-group {
      margin-bottom: 16px;
    }

    .form-group label {
      display: block;
      font-size: 13px;
      font-weight: 500;
      color: var(--text-soft);
      margin-bottom: 6px;
    }

    .form-group input {
      width: 100%;
      padding: 12px 16px;
      border: 1.5px solid var(--warm-mid);
      border-radius: var(--radius-sm);
      font-family: 'DM Sans', sans-serif;
      font-size: 14px;
      background: var(--cream);
      color: var(--text);
      transition: border-color 0.2s;
      outline: none;
    }

    .form-group input:focus {
      border-color: var(--sage);
      background: var(--white);
    }

    .btn-primary {
      width: 100%;
      padding: 14px;
      background: var(--sage);
      color: var(--white);
      border: none;
      border-radius: var(--radius-sm);
      font-family: 'DM Sans', sans-serif;
      font-size: 15px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.2s;
      margin-top: 8px;
    }

    .btn-primary:hover {
      background: var(--sage-deep);
      transform: translateY(-1px);
      box-shadow: 0 4px 16px rgba(77, 107, 85, 0.25);
    }

    .btn-primary:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
    }

    .auth-msg {
      margin-top: 14px;
      padding: 12px 16px;
      border-radius: var(--radius-sm);
      font-size: 13px;
      text-align: center;
      display: none;
    }

    .auth-msg.error {
      background: var(--danger-bg);
      color: var(--danger);
    }

    .auth-msg.success {
      background: var(--sage-pale);
      color: var(--sage-deep);
    }

    /* ═══════════════════════════════════════════════
   MAIN APP LAYOUT
═══════════════════════════════════════════════ */
    #app {
      display: none;
      min-height: 100vh;
      position: relative;
      z-index: 1;
      padding-top: 36px;
      /* crisis banner */
    }

    /* Top nav */
    .topnav {
      background: var(--white);
      border-bottom: 1px solid var(--warm-mid);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 24px;
      height: 62px;
      position: sticky;
      top: 36px;
      z-index: 100;
      box-shadow: var(--shadow-sm);
    }

    .topnav-brand {
      font-family: 'Lora', serif;
      font-size: 20px;
      color: var(--sage-deep);
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .topnav-tabs {
      display: flex;
      gap: 4px;
    }

    .nav-tab {
      padding: 8px 18px;
      border: none;
      background: transparent;
      border-radius: 8px;
      font-family: 'DM Sans', sans-serif;
      font-size: 14px;
      font-weight: 400;
      color: var(--text-soft);
      cursor: pointer;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .nav-tab:hover {
      background: var(--sage-pale);
      color: var(--sage-deep);
    }

    .nav-tab.active {
      background: var(--sage-pale);
      color: var(--sage-deep);
      font-weight: 500;
    }

    .topnav-user {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .user-avatar {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 13px;
      font-weight: 600;
      color: var(--white);
    }

    .btn-logout {
      padding: 6px 14px;
      border: 1.5px solid var(--warm-mid);
      background: transparent;
      border-radius: 8px;
      font-size: 13px;
      color: var(--text-soft);
      cursor: pointer;
      transition: all 0.2s;
    }

    .btn-logout:hover {
      border-color: var(--sage);
      color: var(--sage-deep);
    }

    /* ── Tab panels ── */
    .tab-panel {
      display: none;
      padding: 32px 24px;
      max-width: 900px;
      margin: 0 auto;
    }

    .tab-panel.active {
      display: block;
      animation: fadeUp 0.3s ease;
    }

    #panel-chat.active {
      display: flex;
    }

    #panel-chat {
      padding: 0 24px 0 0;
      /* Adding space on the right */
      max-width: none;
      height: calc(100vh - 62px - 36px);
      margin: 0;
      background: var(--cream);
    }

    .chat-layout {
      display: flex;
      width: 100%;
      height: 100%;
    }

    .chat-sidebar {
      width: 250px;
      background: var(--white);
      border-right: 1px solid var(--warm-mid);
      display: flex;
      flex-direction: column;
      overflow-y: auto;
    }

    .chat-history-list {
      flex: 1;
      padding: 10px;
      overflow-y: auto;
    }

    .history-item {
      padding: 10px;
      margin-bottom: 5px;
      border-radius: 8px;
      background: var(--cream);
      font-size: 13px;
      color: var(--text-soft);
      cursor: pointer;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      transition: all 0.2s;
    }

    .history-item:hover,
    .history-item.active {
      background: var(--sage-pale);
      color: var(--sage-deep);
    }

    .chat-container {
      flex: 1;
      background: var(--white);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      height: 100%;
    }

    .chat-header {
      background: linear-gradient(135deg, var(--sage-deep), var(--sage));
      padding: 20px 24px;
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .hana-avatar {
      width: 44px;
      height: 44px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
    }

    .chat-header-info h3 {
      font-family: 'Lora', serif;
      color: var(--white);
      font-size: 17px;
    }

    .chat-header-info p {
      color: rgba(255, 255, 255, 0.8);
      font-size: 12px;
    }

    .online-dot {
      width: 8px;
      height: 8px;
      background: #88ff99;
      border-radius: 50%;
      display: inline-block;
      margin-right: 4px;
      animation: pulse 2s infinite;
    }

    .chat-messages {
      flex: 1;
      overflow-y: auto;
      padding: 32px 0;
      display: flex;
      flex-direction: column;
      gap: 24px;
      background: var(--cream);
      align-items: center;
    }

    .msg {
      display: flex;
      gap: 16px;
      animation: fadeUp 0.25s ease;
      width: 100%;
      max-width: 800px;
      padding: 0 24px;
    }

    .msg.user {
      flex-direction: row-reverse;
    }

    .msg.assistant {
      align-self: center;
    }

    .msg-bubble {
      padding: 13px 17px;
      border-radius: 18px;
      font-size: 14px;
      line-height: 1.6;
    }

    .msg.user .msg-bubble {
      background: var(--sage);
      color: var(--white);
      border-bottom-right-radius: 4px;
    }

    .history-card {
      background: var(--white);
      border: 1px solid var(--warm-mid);
      border-radius: 12px;
      padding: 15px;
      margin-bottom: 12px;
      cursor: pointer;
      transition: all 0.2s;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .history-card:hover {
      border-color: var(--sage);
      box-shadow: 0 4px 12px rgba(124, 152, 133, 0.1);
      transform: translateY(-2px);
    }

    .history-meta {
      font-size: 11px;
      color: var(--text-soft);
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .history-preview {
      font-size: 13px;
      color: var(--text-pale);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      margin-top: 4px;
      max-width: 250px;
    }

    .msg.assistant .msg-bubble {
      background: var(--white);
      color: var(--text);
      border-bottom-left-radius: 4px;
      box-shadow: var(--shadow-sm);
    }

    .msg-avatar {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      background: var(--sage-pale);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      flex-shrink: 0;
      margin-top: 4px;
    }

    .typing-indicator {
      display: none;
      align-items: center;
      gap: 5px;
      padding: 13px 17px;
      background: var(--white);
      border-radius: 18px;
      border-bottom-left-radius: 4px;
      box-shadow: var(--shadow-sm);
      width: fit-content;
    }

    .typing-indicator span {
      width: 7px;
      height: 7px;
      background: var(--sage-light);
      border-radius: 50%;
      animation: typing 1.2s infinite;
    }

    .typing-indicator span:nth-child(2) {
      animation-delay: 0.2s;
    }

    .typing-indicator span:nth-child(3) {
      animation-delay: 0.4s;
    }

    .chat-input-area {
      padding: 16px 0;
      background: var(--white);
      border-top: 1px solid var(--warm-mid);
      display: flex;
      justify-content: center;
    }

    .chat-input-wrap {
      display: flex;
      gap: 12px;
      align-items: flex-end;
      width: 100%;
      max-width: 800px;
      padding: 0 24px;
    }

    #chat-input,
    #peer-chat-input {
      flex: 1;
      padding: 12px 16px;
      border: 1.5px solid var(--warm-mid);
      border-radius: 12px;
      font-family: 'DM Sans', sans-serif;
      font-size: 16px;
      color: var(--text);
      resize: none;
      outline: none;
      max-height: 120px;
      background: var(--cream);
      transition: border-color 0.2s;
    }

    #chat-input:focus,
    #peer-chat-input:focus {
      border-color: var(--sage);
      background: var(--white);
    }

    .btn-send {
      width: 42px;
      height: 42px;
      border-radius: 10px;
      background: var(--sage);
      border: none;
      color: var(--white);
      font-size: 18px;
      cursor: pointer;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .btn-send:hover {
      background: var(--sage-deep);
      transform: translateY(-1px);
    }

    .btn-new-chat {
      padding: 8px 14px;
      border: 1.5px solid var(--warm-mid);
      background: transparent;
      border-radius: 8px;
      font-size: 13px;
      color: var(--text-soft);
      cursor: pointer;
      transition: all 0.2s;
      white-space: nowrap;
    }

    .btn-new-chat:hover {
      border-color: var(--sage);
      color: var(--sage-deep);
    }

    /* ═══════════════════════════════════════════════
   JOURNAL TAB
═══════════════════════════════════════════════ */
    .section-title {
      font-family: 'Lora', serif;
      font-size: 22px;
      color: var(--sage-deep);
      margin-bottom: 6px;
    }

    .section-sub {
      color: var(--text-soft);
      font-size: 14px;
      margin-bottom: 28px;
    }

    .journal-form {
      background: var(--white);
      border-radius: var(--radius);
      padding: 28px;
      box-shadow: var(--shadow-sm);
      margin-bottom: 28px;
    }

    .mood-picker {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }

    .mood-btn {
      flex: 1;
      min-width: 70px;
      padding: 14px 8px;
      border: 2px solid var(--warm-mid);
      background: var(--cream);
      border-radius: var(--radius-sm);
      cursor: pointer;
      text-align: center;
      transition: all 0.2s;
    }

    .mood-btn:hover {
      border-color: var(--sage-light);
    }

    .mood-btn.selected {
      border-color: var(--sage);
      background: var(--sage-pale);
    }

    .mood-btn .mood-emoji {
      font-size: 24px;
      display: block;
      margin-bottom: 4px;
    }

    .mood-btn .mood-text {
      font-size: 11px;
      color: var(--text-soft);
      display: block;
    }

    .journal-textarea {
      width: 100%;
      padding: 14px;
      border: 1.5px solid var(--warm-mid);
      border-radius: var(--radius-sm);
      font-family: 'Lora', serif;
      font-size: 14px;
      line-height: 1.7;
      resize: vertical;
      min-height: 120px;
      background: var(--cream);
      color: var(--text);
      outline: none;
      transition: border-color 0.2s;
    }

    .journal-textarea:focus {
      border-color: var(--sage);
      background: var(--white);
    }

    .anon-check {
      display: flex;
      align-items: center;
      gap: 8px;
      margin: 12px 0;
      font-size: 13px;
      color: var(--text-soft);
      cursor: pointer;
    }

    .anon-check input {
      accent-color: var(--sage);
      width: 15px;
      height: 15px;
    }

    .btn-save {
      padding: 12px 28px;
      background: var(--sage);
      color: var(--white);
      border: none;
      border-radius: var(--radius-sm);
      font-family: 'DM Sans', sans-serif;
      font-size: 14px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.2s;
    }

    .btn-save:hover {
      background: var(--sage-deep);
    }

    /* Trend chart */
    .mood-chart-wrap {
      background: var(--white);
      border-radius: var(--radius);
      padding: 24px;
      box-shadow: var(--shadow-sm);
      margin-bottom: 28px;
    }

    .mood-chart-wrap h3 {
      font-family: 'Lora', serif;
      font-size: 16px;
      color: var(--sage-deep);
      margin-bottom: 16px;
    }

    .mini-chart {
      display: flex;
      align-items: flex-end;
      gap: 6px;
      height: 80px;
    }

    .chart-bar-wrap {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 4px;
    }

    .chart-bar {
      width: 100%;
      background: var(--sage-light);
      border-radius: 4px 4px 0 0;
      transition: height 0.4s ease;
      min-height: 4px;
    }

    .chart-label {
      font-size: 9px;
      color: var(--text-pale);
    }

    /* Entry cards */
    .entry-card {
      background: var(--white);
      border-radius: var(--radius);
      padding: 20px 22px;
      box-shadow: var(--shadow-sm);
      margin-bottom: 14px;
      animation: fadeUp 0.3s ease;
      border-left: 4px solid var(--sage-light);
      position: relative;
    }

    .entry-card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 8px;
    }

    .entry-mood-badge {
      font-size: 18px;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .entry-mood-badge span {
      font-size: 12px;
      color: var(--text-soft);
    }

    .entry-date {
      font-size: 12px;
      color: var(--text-pale);
    }

    .entry-content {
      font-size: 14px;
      color: var(--text);
      line-height: 1.6;
      font-family: 'Lora', serif;
    }

    .btn-delete-entry {
      position: absolute;
      top: 14px;
      right: 14px;
      background: none;
      border: none;
      color: var(--text-pale);
      cursor: pointer;
      font-size: 16px;
      transition: color 0.2s;
    }

    .btn-delete-entry:hover {
      color: var(--danger);
    }

    /* ═══════════════════════════════════════════════
   HOTLINES TAB
═══════════════════════════════════════════════ */
    .hotlines-intro {
      background: linear-gradient(135deg, var(--sage-deep), var(--sage));
      border-radius: var(--radius);
      padding: 28px 32px;
      color: var(--white);
      margin-bottom: 28px;
    }

    .hotlines-intro h2 {
      font-family: 'Lora', serif;
      font-size: 22px;
      margin-bottom: 6px;
    }

    .hotlines-intro p {
      opacity: 0.9;
      font-size: 14px;
    }

    .hotlines-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 16px;
      margin-bottom: 28px;
    }

    .hotline-card {
      background: var(--white);
      border-radius: var(--radius);
      padding: 22px;
      box-shadow: var(--shadow-sm);
      border-top: 4px solid var(--sage-light);
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .hotline-card:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow);
    }

    .hotline-flag {
      font-size: 24px;
      margin-bottom: 10px;
    }

    .hotline-country {
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: var(--text-soft);
      margin-bottom: 6px;
    }

    .hotline-name {
      font-family: 'Lora', serif;
      font-size: 17px;
      color: var(--sage-deep);
      margin-bottom: 4px;
    }

    .hotline-number {
      font-size: 20px;
      font-weight: 600;
      color: var(--sage);
      margin-bottom: 6px;
      letter-spacing: 0.5px;
    }

    .hotline-note {
      font-size: 12px;
      color: var(--text-soft);
      line-height: 1.4;
    }

    .tips-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 14px;
    }

    .tip-card {
      background: var(--white);
      border-radius: var(--radius);
      padding: 20px;
      box-shadow: var(--shadow-sm);
      text-align: center;
    }

    .tip-icon {
      font-size: 28px;
      margin-bottom: 10px;
    }

    .tip-title {
      font-family: 'Lora', serif;
      font-size: 15px;
      color: var(--sage-deep);
      margin-bottom: 6px;
    }

    .tip-text {
      font-size: 13px;
      color: var(--text-soft);
      line-height: 1.5;
    }

    /* ═══════════════════════════════════════════════
   FORUM TAB
═══════════════════════════════════════════════ */
    .forum-layout {
      display: grid;
      grid-template-columns: 1fr;
      gap: 20px;
    }

    .forum-compose {
      background: var(--white);
      border-radius: var(--radius);
      padding: 24px;
      box-shadow: var(--shadow-sm);
      margin-bottom: 4px;
    }

    .forum-compose h3 {
      font-family: 'Lora', serif;
      font-size: 16px;
      color: var(--sage-deep);
      margin-bottom: 16px;
    }

    .forum-title-input {
      width: 100%;
      padding: 12px 14px;
      border: 1.5px solid var(--warm-mid);
      border-radius: var(--radius-sm);
      font-family: 'DM Sans', sans-serif;
      font-size: 14px;
      background: var(--cream);
      color: var(--text);
      margin-bottom: 10px;
      outline: none;
      transition: border-color 0.2s;
    }

    .forum-title-input:focus {
      border-color: var(--sage);
      background: var(--white);
    }

    .post-card {
      background: var(--white);
      border-radius: var(--radius);
      padding: 22px;
      box-shadow: var(--shadow-sm);
      margin-bottom: 14px;
      cursor: pointer;
      transition: transform 0.2s, box-shadow 0.2s;
      animation: fadeUp 0.3s ease;
    }

    .post-card:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow);
    }

    .post-card.pinned {
      border-left: 4px solid var(--sage);
    }

    .post-author {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 10px;
    }

    .post-avatar {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      font-weight: 600;
      color: var(--white);
    }

    .post-author-name {
      font-size: 13px;
      font-weight: 500;
      color: var(--text);
    }

    .post-author-date {
      font-size: 11px;
      color: var(--text-pale);
    }

    .post-title {
      font-family: 'Lora', serif;
      font-size: 17px;
      color: var(--sage-deep);
      margin-bottom: 8px;
    }

    .post-preview {
      font-size: 14px;
      color: var(--text-soft);
      line-height: 1.5;
    }

    .post-meta {
      display: flex;
      gap: 16px;
      margin-top: 12px;
      font-size: 12px;
      color: var(--text-pale);
    }

    .post-meta-item {
      display: flex;
      align-items: center;
      gap: 4px;
    }

    .post-actions {
      display: flex;
      gap: 8px;
    }

    .btn-action-small {
      cursor: pointer;
      font-size: 14px;
      padding: 4px 10px;
      border-radius: 6px;
      border: 1.5px solid var(--warm-mid);
      color: var(--text-soft);
      background: none;
      transition: all 0.2s;
    }

    .btn-action-small:hover {
      border-color: var(--sage);
      color: var(--sage-deep);
      background: var(--sage-pale);
    }

    .btn-action-delete:hover {
      border-color: var(--danger);
      color: var(--danger);
      background: var(--danger-bg);
    }

    .btn-action-chat {
      border-color: var(--sage);
      color: var(--sage-deep);
      background: var(--sage-pale);
    }

    .btn-action-chat:hover {
      background: var(--sage);
      color: var(--white);
    }

    .history-card {
      background: var(--white);
      border: 1px solid var(--warm-mid);
      border-radius: 12px;
      padding: 15px;
      margin-bottom: 12px;
      cursor: pointer;
      transition: all 0.2s;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .history-card:hover {
      border-color: var(--sage);
      box-shadow: 0 4px 12px rgba(124, 152, 133, 0.1);
      transform: translateY(-2px);
    }

    .history-meta {
      font-size: 11px;
      color: var(--text-soft);
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .history-preview {
      font-size: 13px;
      color: var(--text-pale);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      margin-top: 4px;
      max-width: 250px;
    }

    /* Post detail modal */
    .modal-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.4);
      z-index: 500;
      padding: 36px 16px 16px;
      overflow-y: auto;
    }

    .modal-overlay.open {
      display: flex;
      justify-content: center;
      align-items: flex-start;
      animation: fadeIn 0.2s ease;
    }

    .modal-card {
      background: var(--white);
      border-radius: 20px;
      width: 100%;
      max-width: 700px;
      max-height: 85vh;
      overflow-y: auto;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
      padding: 24px 28px 0;
      position: sticky;
      top: 0;
      background: var(--white);
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
    }

    .btn-close-modal {
      background: var(--sage-pale);
      border: none;
      border-radius: 50%;
      width: 32px;
      height: 32px;
      font-size: 16px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--text-soft);
      transition: all 0.2s;
      flex-shrink: 0;
      margin-left: 16px;
      margin-top: 4px;
    }

    .btn-close-modal:hover {
      background: var(--warm-mid);
    }

    .modal-body {
      padding: 16px 28px 28px;
    }

    .reply-card {
      background: var(--sage-pale);
      border-radius: var(--radius-sm);
      padding: 14px 16px;
      margin-bottom: 10px;
      animation: fadeUp 0.25s ease;
    }

    .heart-btn {
      background: none;
      border: 1.5px solid var(--warm-mid);
      border-radius: 20px;
      padding: 5px 12px;
      font-size: 12px;
      cursor: pointer;
      transition: all 0.2s;
      color: var(--text-soft);
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }

    .heart-btn:hover,
    .heart-btn.hearted {
      border-color: #e88;
      color: #c66;
      background: #fff0f0;
    }

    /* ═══════════════════════════════════════════════
   SHARED UTILS
═══════════════════════════════════════════════ */
    .empty-state {
      text-align: center;
      padding: 48px 24px;
      color: var(--text-soft);
    }

    .empty-state .icon {
      font-size: 42px;
      margin-bottom: 12px;
    }

    .empty-state p {
      font-size: 15px;
    }

    .toast {
      position: fixed;
      bottom: 24px;
      right: 24px;
      background: var(--sage-deep);
      color: var(--white);
      padding: 12px 20px;
      border-radius: 10px;
      font-size: 14px;
      z-index: 9999;
      transform: translateY(80px);
      opacity: 0;
      transition: all 0.3s;
      pointer-events: none;
    }

    .toast.show {
      transform: translateY(0);
      opacity: 1;
    }

    .toast.error {
      background: var(--danger);
    }

    /* ═══════════════════════════════════════════════
   ANIMATIONS
═══════════════════════════════════════════════ */
    @keyframes fadeUp {
      from {
        opacity: 0;
        transform: translateY(16px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    @keyframes pulse {

      0%,
      100% {
        opacity: 1;
      }

      50% {
        opacity: 0.4;
      }
    }

    @keyframes typing {

      0%,
      60%,
      100% {
        transform: translateY(0);
        opacity: 0.4;
      }

      30% {
        transform: translateY(-5px);
        opacity: 1;
      }
    }

    /* ═══════════════════════════════════════════════
       GROUNDING TOOL
    ═══════════════════════════════════════════════ */
    .grounding-layout {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 60vh;
      text-align: center;
      gap: 40px;
    }

    .breathing-card {
      background: var(--white);
      padding: 60px 40px;
      border-radius: 40px;
      box-shadow: var(--shadow);
      display: grid;
      grid-template-columns: 100px 1fr 100px;
      align-items: center;
      gap: 20px;
      width: 100%;
      max-width: 700px;
      position: relative;
    }

    .breathing-timer-wrap, .breathing-counter-wrap {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
    }

    .stat-circle {
      width: 70px;
      height: 70px;
      border: 3px solid var(--sage-pale);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      font-weight: 700;
      color: var(--sage-deep);
      position: relative;
    }

    .stat-label {
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: var(--text-soft);
      font-weight: 600;
      text-align: center;
    }

    .breathing-info-center {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 20px;
    }

    .breathing-steps {
        display: flex;
        gap: 15px;
        margin-top: 10px;
    }

    .step-dot {
        padding: 5px 12px;
        border-radius: 20px;
        background: var(--sage-pale);
        font-size: 11px;
        font-weight: 700;
        color: var(--text-soft);
        opacity: 0.5;
        transition: all 0.3s;
    }

    .step-dot.active {
        background: var(--sage-deep);
        color: var(--white);
        opacity: 1;
        transform: scale(1.1);
    }

    .circle-container {
      position: relative;
      width: 250px;
      height: 250px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .breathing-circle {
      width: 120px;
      height: 120px;
      background: var(--sage);
      border-radius: 50%;
      transition: transform 1s cubic-bezier(0.4, 0, 0.2, 1), background-color 1s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 0 30px rgba(124, 152, 133, 0.3);
      z-index: 2;
    }

    .circle-outer {
      position: absolute;
      width: 250px;
      height: 250px;
      border: 2px dashed var(--sage-light);
      border-radius: 50%;
      opacity: 0.3;
      z-index: 1;
    }

    .breathing-text {
      font-family: 'Lora', serif;
      font-size: 32px;
      color: var(--sage-deep);
      font-weight: 500;
      height: 48px;
    }

    .breathing-instruction {
      font-size: 16px;
      color: var(--text-soft);
      font-weight: 400;
      max-width: 300px;
    }

    .btn-start-breathing {
      padding: 14px 32px;
      background: var(--sage);
      color: var(--white);
      border: none;
      border-radius: 50px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
      font-size: 16px;
    }

    .btn-start-breathing:hover {
      background: var(--sage-deep);
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(124, 152, 133, 0.3);
    }
    
    /* ═══════════════════════════════════════════════
    BADGES
    ═══════════════════════════════════════════════ */
    .badge-pill {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      padding: 2px 10px 2px 6px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 0.3px;
      white-space: nowrap;
      vertical-align: middle;
    }

    /* Leaderboard */
    .leaderboard-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
      gap: 20px;
      margin-top: 24px;
    }

    .leader-card {
      background: var(--white);
      border-radius: 20px;
      padding: 24px 20px;
      display: flex;
      align-items: center;
      gap: 16px;
      border: 1.5px solid var(--warm-mid);
      transition: all 0.2s;
      box-shadow: var(--shadow-sm);
    }

    .leader-card:hover {
      transform: translateY(-4px);
      border-color: var(--sage);
      box-shadow: 0 8px 24px rgba(124,152,133,0.15);
    }

    .leader-rank {
      font-size: 22px;
      font-weight: 700;
      color: var(--text-pale);
      width: 30px;
      text-align: center;
      flex-shrink: 0;
    }

    .leader-rank.gold   { color: #f4ad13; }
    .leader-rank.silver { color: #b0b0b0; }
    .leader-rank.bronze { color: #cd7f32; }

    .leader-avatar {
      width: 44px;
      height: 44px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      font-weight: 700;
      color: #fff;
      flex-shrink: 0;
    }

    .leader-info { flex: 1; min-width: 0; }

    .leader-name {
      font-weight: 600;
      font-size: 15px;
      color: var(--text);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .leader-stats {
      display: flex;
      gap: 12px;
      margin-top: 4px;
    }

    .leader-stat {
      font-size: 12px;
      color: var(--text-soft);
    }

    .my-badge-banner {
      background: var(--white);
      border: 1.5px solid var(--sage);
      border-radius: 20px;
      padding: 20px 28px;
      display: flex;
      align-items: center;
      gap: 16px;
      margin-bottom: 32px;
      box-shadow: 0 4px 20px rgba(124,152,133,0.12);
    }

    .my-badge-icon { font-size: 40px; }

    .my-badge-info h3 {
      font-family: 'Lora', serif;
      font-size: 20px;
      color: var(--sage-deep);
    }

    .my-badge-info p {
      font-size: 13px;
      color: var(--text-soft);
      margin-top: 2px;
    }

    .progress-bar-wrap {
      flex: 1;
      min-width: 120px;
    }

    .progress-bar-bg {
      background: var(--warm-mid);
      border-radius: 20px;
      height: 8px;
      overflow: hidden;
    }

    .progress-bar-fill {
      height: 100%;
      border-radius: 20px;
      background: var(--sage);
      transition: width 0.8s ease;
    }

    .progress-label {
      font-size: 11px;
      color: var(--text-soft);
      margin-top: 5px;
    }

    /* ═══════════════════════════════════════════════
    RESPONSIVE
    ═══════════════════════════════════════════════ */
    @media (max-width: 600px) {
      .auth-card {
        padding: 32px 22px;
      }

      .topnav-tabs .nav-tab span.label {
        display: none;
      }

      .tab-panel {
        padding: 20px 14px;
      }

      .modal-card {
        border-radius: 16px;
      }

      .modal-body {
        padding: 14px 18px 24px;
      }

      .modal-header {
        padding: 18px 18px 0;
      }
    }
  </style>
</head>

<body>

  <!-- ── Crisis Banner ─────────────────────────────────────────── -->
  <div class="crisis-banner">
    🆘 In immediate danger? Call <strong>999</strong> (BD Emergency) ·
    Kaan Pete Roi: <strong>01779-554391</strong> ·
    iCall: <strong>+91-9152987821</strong>
  </div>

  <!-- ════════════════════════════════════════════════
     AUTH SCREEN
════════════════════════════════════════════════ -->
  <div id="auth-screen">
    <div class="auth-card">
      <div class="auth-logo">
        <div class="leaf">🌿</div>
        <h1>HopeLine</h1>
        <p>A safe space for you — always</p>
      </div>

      <div class="tab-btns">
        <button class="tab-btn active" onclick="switchAuth('login')">Sign In</button>
        <button class="tab-btn" onclick="switchAuth('register')">Join HopeLine</button>
      </div>

      <!-- Login form -->
      <div id="form-login">
        <div class="form-group">
          <label>Username or Email</label>
          <input type="text" id="login-identifier" placeholder="Enter your username or email">
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" id="login-password" placeholder="Your password"
            onkeydown="if(event.key==='Enter') doLogin()">
        </div>
        <button class="btn-primary" onclick="doLogin()" id="btn-login">Sign In</button>
        <div class="auth-msg" id="login-msg"></div>
      </div>

      <!-- Register form -->
      <div id="form-register" style="display:none">
        <div class="form-group">
          <label>Display Name</label>
          <input type="text" id="reg-name" placeholder="How should we call you?">
        </div>
        <div class="form-group">
          <label>Username</label>
          <input type="text" id="reg-username" placeholder="Choose a username">
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" id="reg-email" placeholder="your@email.com">
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" id="reg-password" placeholder="At least 6 characters"
            onkeydown="if(event.key==='Enter') doRegister()">
        </div>
        <button class="btn-primary" onclick="doRegister()" id="btn-register">Create Account</button>
        <div class="auth-msg" id="register-msg"></div>
      </div>
    </div>
  </div>

  <!-- ════════════════════════════════════════════════
     MAIN APP
════════════════════════════════════════════════ -->
  <div id="app">

    <nav class="topnav">
      <div class="topnav-brand">🌿 <span>HopeLine</span></div>
      <div class="topnav-tabs">
        <button class="nav-tab active" onclick="showTab('chat')" id="tab-chat">💬 <span
            class="label">Chat</span></button>
        <button class="nav-tab" onclick="showTab('journal')" id="tab-journal">📓 <span
            class="label">Journal</span></button>
        <button class="nav-tab" onclick="showTab('hotlines')" id="tab-hotlines">📞 <span
            class="label">Hotlines</span></button>
        <button class="nav-tab" onclick="showTab('forum')" id="tab-forum">🤝 <span
            class="label">Community</span></button>
        <button class="nav-tab" onclick="showTab('safety')" id="tab-safety">🛡️ <span class="label">Safety
            Plan</span></button>
        <button class="nav-tab" onclick="showTab('peer')" id="tab-peer">👥 <span class="label">Peer Chat</span></button>
        <button class="nav-tab" onclick="showTab('grounding')" id="tab-grounding">🌬️ <span class="label">Grounding</span></button>
        <button class="nav-tab" onclick="showTab('leaderboard')" id="tab-leaderboard">🏅 <span class="label">Badges</span></button>
      </div>
      <div class="topnav-user">
        <button onclick="toggleDarkMode()" id="btn-dark-mode"
          style="background:none;border:none;font-size:20px;cursor:pointer;margin-right:8px;padding:4px;border-radius:50%;color:var(--text);display:flex;align-items:center;justify-content:center;">🌙</button>
        <div class="user-avatar" id="user-avatar-nav">?</div>
        <button class="btn-logout" onclick="doLogout()">Sign out</button>
      </div>
    </nav>

    <!-- ── Chat ───────────────────────────────────────── -->
    <div class="tab-panel active" id="panel-chat">
      <div class="chat-layout">
        <div class="chat-sidebar">
          <div style="padding: 15px; border-bottom: 1px solid var(--warm-mid);">
            <button class="btn-new-chat" onclick="newChatSession()" style="width: 100%;">+ New Chat</button>
          </div>
          <div class="chat-history-list" id="chat-history-list">
            <!-- Sidebar items populated by JS -->
          </div>
        </div>
        <div class="chat-container">
          <div class="chat-header">
            <div class="hana-avatar">🌸</div>
            <div class="chat-header-info">
              <h3>Mehjabeen</h3>
              <p><span class="online-dot"></span>AI Companion · Always here for you</p>
            </div>
          </div>
          <div class="chat-messages" id="chat-messages">
            <div class="empty-state" id="chat-loading">
              <div class="icon">🌸</div>
              <p>Loading your conversation…</p>
            </div>
          </div>
          <div style="width: 100%; display: flex; justify-content: center; background: var(--cream);">
            <div style="width: 100%; max-width: 800px; padding: 0 12px 12px;">
              <div class="typing-indicator" id="typing-indicator" style="margin: 0;">
                <span></span><span></span><span></span>
              </div>
            </div>
          </div>
          <div class="chat-input-area">
            <div class="chat-input-wrap">
              <textarea id="chat-input" placeholder="How are you feeling right now? I'm listening…" rows="1"
                onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();sendMessage()}"
                oninput="autoResize(this)"></textarea>
              <button class="btn-send" onclick="sendMessage()" title="Send">➤</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── Journal ────────────────────────────────────── -->
    <div class="tab-panel" id="panel-journal">
      <h2 class="section-title">Your Mood Journal</h2>
      <p class="section-sub">Track how you're feeling — your entries are private and safe here.</p>

      <div class="journal-form">
        <p style="font-size:13px; font-weight:500; color:var(--text-soft); margin-bottom:10px">How are you feeling
          today?</p>
        <div class="mood-picker" id="mood-picker">
          <button class="mood-btn" data-score="1" data-label="Very Low" onclick="selectMood(this)">
            <span class="mood-emoji">😔</span><span class="mood-text">Very Low</span>
          </button>
          <button class="mood-btn" data-score="2" data-label="Low" onclick="selectMood(this)">
            <span class="mood-emoji">😕</span><span class="mood-text">Low</span>
          </button>
          <button class="mood-btn" data-score="3" data-label="Okay" onclick="selectMood(this)">
            <span class="mood-emoji">😐</span><span class="mood-text">Okay</span>
          </button>
          <button class="mood-btn" data-score="4" data-label="Good" onclick="selectMood(this)">
            <span class="mood-emoji">🙂</span><span class="mood-text">Good</span>
          </button>
          <button class="mood-btn" data-score="5" data-label="Great" onclick="selectMood(this)">
            <span class="mood-emoji">😊</span><span class="mood-text">Great</span>
          </button>
        </div>
        <textarea class="journal-textarea" id="journal-content"
          placeholder="Write anything — your thoughts, feelings, what happened today… This is your space."></textarea>
        <label class="anon-check">
          <input type="checkbox" id="journal-anon"> Keep this entry anonymous (in case of community sharing)
        </label>
        <button class="btn-save" onclick="saveJournal()">Save Entry 💚</button>
      </div>

      <div class="mood-chart-wrap" id="mood-chart-wrap" style="display:none">
        <h3>📈 Your mood this week</h3>
        <div class="mini-chart" id="mood-chart"></div>
      </div>

      <div id="journal-entries-list"></div>
    </div>

    <!-- ── Hotlines ───────────────────────────────────── -->
    <div class="tab-panel" id="panel-hotlines">
      <div class="hotlines-intro">
        <h2>You Are Not Alone 💚</h2>
        <p>These are real people who want to help. Reaching out is a sign of strength, not weakness. You deserve
          support.</p>
      </div>

      <h3 style="font-family:'Lora',serif;color:var(--sage-deep);margin-bottom:16px">📞 Crisis Helplines</h3>
      <div class="hotlines-grid">
        <div class="hotline-card">
          <div class="hotline-flag">🇧🇩</div>
          <div class="hotline-country">Bangladesh</div>
          <div class="hotline-name">Kaan Pete Roi</div>
          <div class="hotline-number">01779-554391</div>
          <div class="hotline-note">Emotional support helpline, available daily. Free to call.</div>
        </div>
        <div class="hotline-card">
          <div class="hotline-flag">🇧🇩</div>
          <div class="hotline-country">Bangladesh Emergency</div>
          <div class="hotline-name">National Emergency</div>
          <div class="hotline-number">999</div>
          <div class="hotline-note">Police, Ambulance, Fire — available 24/7 nationwide.</div>
        </div>
        <div class="hotline-card">
          <div class="hotline-flag">🌏</div>
          <div class="hotline-country">International</div>
          <div class="hotline-name">iCall (India/South Asia)</div>
          <div class="hotline-number">+91-9152987821</div>
          <div class="hotline-note">Mon–Sat, 8am–10pm IST. Psychologists available for free.</div>
        </div>
        <div class="hotline-card">
          <div class="hotline-flag">🌐</div>
          <div class="hotline-country">International</div>
          <div class="hotline-name">Befrienders Worldwide</div>
          <div class="hotline-number">befrienders.org</div>
          <div class="hotline-note">Find a local crisis center anywhere in the world.</div>
        </div>
        <div class="hotline-card">
          <div class="hotline-flag">💬</div>
          <div class="hotline-country">Online / Chat</div>
          <div class="hotline-name">Crisis Text Line</div>
          <div class="hotline-number">Text HOME to 741741</div>
          <div class="hotline-note">Free crisis counseling via text. Available 24/7 in many countries.</div>
        </div>
        <div class="hotline-card">
          <div class="hotline-flag">🧠</div>
          <div class="hotline-country">Bangladesh</div>
          <div class="hotline-name">NIMH (National Mental Health)</div>
          <div class="hotline-number">16789</div>
          <div class="hotline-note">National mental health helpline, Government of Bangladesh.</div>
        </div>
      </div>

      <h3 style="font-family:'Lora',serif;color:var(--sage-deep);margin:28px 0 16px">🌱 Coping Strategies</h3>
      <div class="tips-grid">
        <div class="tip-card">
          <div class="tip-icon">🌬️</div>
          <div class="tip-title">Box Breathing</div>
          <div class="tip-text">Inhale 4 counts → Hold 4 → Exhale 4 → Hold 4. Repeat 4 times. Calms your nervous system
            instantly.</div>
        </div>
        <div class="tip-card">
          <div class="tip-icon">🖐️</div>
          <div class="tip-title">5-4-3-2-1 Grounding</div>
          <div class="tip-text">Name 5 things you see, 4 you can touch, 3 you hear, 2 you smell, 1 you taste. Brings you
            back to the present.</div>
        </div>
        <div class="tip-card">
          <div class="tip-icon">📱</div>
          <div class="tip-title">Reach Out</div>
          <div class="tip-text">Text or call one person you trust — a friend, family member, anyone. You don't have to
            explain everything.</div>
        </div>
        <div class="tip-card">
          <div class="tip-icon">🚶</div>
          <div class="tip-title">Move Your Body</div>
          <div class="tip-text">Even a 5-minute walk outside can shift your mood. Movement releases natural feel-good
            chemicals.</div>
        </div>
        <div class="tip-card">
          <div class="tip-icon">🎵</div>
          <div class="tip-title">Music or Nature Sounds</div>
          <div class="tip-text">Put on a song that feels safe and comforting. Music can regulate emotions in surprising
            ways.</div>
        </div>
        <div class="tip-card">
          <div class="tip-icon">✍️</div>
          <div class="tip-title">Write It Out</div>
          <div class="tip-text">Use the Journal tab. Writing down feelings, even briefly, can create distance from
            overwhelming emotions.</div>
        </div>
      </div>
    </div>

    <!-- ── Safety Plan ─────────────────────────────── -->
    <div class="tab-panel" id="panel-safety">
      <h2 class="section-title">My Safety Plan</h2>
      <p class="section-sub">A step-by-step plan that can help keep you safe during a crisis. Fill this out when you are
        feeling calm.</p>

      <div class="forum-compose" style="margin-bottom: 16px;">
        <h3 style="color:var(--sage-deep); font-size:15px">Step 1: Warning Signs</h3>
        <p style="font-size:13px; color:var(--text-soft); margin-bottom:8px">What thoughts, moods, or behaviors tell me
          a crisis might be developing?</p>
        <textarea class="journal-textarea" id="sp-warning" style="min-height:70px"></textarea>
      </div>

      <div class="forum-compose" style="margin-bottom: 16px;">
        <h3 style="color:var(--sage-deep); font-size:15px">Step 2: Coping Strategies</h3>
        <p style="font-size:13px; color:var(--text-soft); margin-bottom:8px">What can I do to distract myself and calm
          down without contacting anyone?</p>
        <textarea class="journal-textarea" id="sp-coping" style="min-height:70px"></textarea>
      </div>

      <div class="forum-compose" style="margin-bottom: 16px;">
        <h3 style="color:var(--sage-deep); font-size:15px">Step 3: Distractions</h3>
        <p style="font-size:13px; color:var(--text-soft); margin-bottom:8px">People and places that provide a safe
          distraction (who can I call just to chat?)</p>
        <textarea class="journal-textarea" id="sp-distractions" style="min-height:70px"></textarea>
      </div>

      <div class="forum-compose" style="margin-bottom: 16px;">
        <h3 style="color:var(--sage-deep); font-size:15px">Step 4: People I Can Ask for Help</h3>
        <p style="font-size:13px; color:var(--text-soft); margin-bottom:8px">Who can I talk to when I'm holding a lot of
          pain?</p>
        <textarea class="journal-textarea" id="sp-people" style="min-height:70px"></textarea>
      </div>

      <div class="forum-compose" style="margin-bottom: 16px;">
        <h3 style="color:var(--sage-deep); font-size:15px">Step 5: Professionals & Agencies</h3>
        <p style="font-size:13px; color:var(--text-soft); margin-bottom:8px">Therapist, psychiatrist, local hospital, or
          crisis hotlines.</p>
        <textarea class="journal-textarea" id="sp-prof" style="min-height:70px"></textarea>
      </div>

      <div class="forum-compose" style="margin-bottom: 16px;">
        <h3 style="color:var(--sage-deep); font-size:15px">Step 6: Making My Environment Safe</h3>
        <p style="font-size:13px; color:var(--text-soft); margin-bottom:8px">What things do I need to remove from my
          environment to stay safe?</p>
        <textarea class="journal-textarea" id="sp-env" style="min-height:70px"></textarea>
      </div>

      <div class="forum-compose" style="margin-bottom: 16px;">
        <h3 style="color:var(--sage-deep); font-size:15px">My Reasons for Living</h3>
        <p style="font-size:13px; color:var(--text-soft); margin-bottom:8px">What are some things that are important to
          me and worth living for?</p>
        <textarea class="journal-textarea" id="sp-reasons" style="min-height:70px"></textarea>
      </div>

      <button class="btn-save" onclick="saveSafetyPlan()" style="margin-bottom: 30px; width:100%">Save Safety Plan
        🛡️</button>
    </div>

    <!-- ── Anonymous Peer Chat ─────────────────────────── -->
    <div class="tab-panel" id="panel-peer">
      <div class="chat-container">
        <div class="chat-header">
          <div style="display:flex; align-items:center; gap:12px">
            <div class="icon-circle" style="background:var(--sage-pale); color:var(--sage-deep)">👥</div>
            <div class="chat-header-info">
              <h3 style="margin:0; font-size:17px; color:var(--white); font-family:'Lora',serif;">Anonymous Peer Chat</h3>
              <p style="margin:0; font-size:12px; color:rgba(255,255,255,0.85);" id="peer-status-text">Safe & Confidential</p>
            </div>
          </div>
          <div style="display: flex; gap: 8px; margin-left:auto">
            <button class="nav-tab active" id="btn-active-list" style="font-size:12px; padding:6px 12px" onclick="showPeerList('active')">Active Chats</button>
            <button class="nav-tab" id="btn-history-list" style="font-size:12px; padding:6px 12px" onclick="showPeerList('history')">History</button>
            <button class="btn-new-chat" id="btn-random-peer" style="font-size:12px; padding:6px 12px" onclick="peerChatFind()">Random Chat</button>
            <button class="btn-new-chat" id="btn-back-to-list" style="display:none; font-size:12px; padding:6px 12px" onclick="showPeerList('active')">Go Back</button>
            <button class="btn-new-chat" id="btn-leave-peer" style="display:none; color:var(--danger); border-color:var(--danger-bg); margin-left:10px; font-size:12px; padding:6px 12px" onclick="peerChatLeave()">Leave Chat</button>
          </div>
        </div>
        
        <!-- History List View -->
        <div id="peer-history-view" style="flex:1; overflow-y:auto; padding:20px;">
          <div id="peer-history-list"></div>
        </div>

        <!-- Active Chat View -->
        <div id="peer-chat-view" style="display:none; flex:1; flex-direction:column; overflow:hidden;">
          <div class="chat-messages" id="peer-chat-messages"></div>
          <div class="chat-input-area">
            <div class="chat-input-wrap">
              <textarea id="peer-chat-input" placeholder="Type a message..." rows="1"
                onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();peerChatSend()}"
                oninput="autoResize(this)" disabled></textarea>
              <button class="btn-send" onclick="peerChatSend()" title="Send" id="btn-peer-send" disabled>➤</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── Community Forum ─────────────────────────────── -->
    <div class="tab-panel" id="panel-forum">
      <h2 class="section-title">Community</h2>
      <p class="section-sub">A kind, judgment-free space. Share your story, support others, or just read. You belong
        here.</p>

      <div class="forum-compose">
        <h3>Share something with the community</h3>
        <input type="text" class="forum-title-input" id="forum-post-title" placeholder="Give your post a title…">
        <textarea class="journal-textarea" id="forum-post-content"
          placeholder="Write your thoughts, experience, or question. You can post anonymously."
          style="min-height:90px"></textarea>

        <div style="margin-top: 10px;">
          <label
            style="font-size:13px; color:var(--text-soft); cursor:pointer; display:inline-flex; align-items:center; gap:6px;">
            📷 <input type="file" id="forum-post-image" accept="image/*" style="font-size:12px">
          </label>
        </div>

        <div
          style="display:flex; justify-content:space-between; align-items:center; margin-top:12px; flex-wrap:wrap; gap:10px">
          <label class="anon-check" style="margin:0">
            <input type="checkbox" id="forum-anon"> Post anonymously
          </label>
          <button class="btn-save" onclick="submitPost()">Share Post 🌿</button>
        </div>
      </div>

      <div id="forum-posts-list"></div>
    </div>

    <!-- ── Grounding Tool (Pause & Breathe) ─────────────────── -->
    <div class="tab-panel" id="panel-grounding">
      <div class="grounding-layout">
        <h1 class="section-title">Pause & Breathe</h1>
        <p class="section-sub">Take a moment for yourself. Try the 4-7-8 breathing technique to calm your mind.</p>
        
        <div class="breathing-card">
          <!-- Left side: Timer -->
          <div class="breathing-timer-wrap">
            <div class="stat-circle" id="breathing-timer">0</div>
            <div class="stat-label">Seconds</div>
          </div>

          <!-- Center: Animation & Instructions -->
          <div class="breathing-info-center">
            <div class="circle-container">
                <div class="circle-outer"></div>
                <div class="breathing-circle" id="breathing-circle">
                <span id="breathing-emoji" style="font-size: 40px;">🌿</span>
                </div>
            </div>
            
            <div class="breathing-text" id="breathing-text">Ready?</div>
            <div class="breathing-instruction" id="breathing-instruction">Press Start to begin.</div>
            
            <div class="breathing-steps">
                <span class="step-dot" id="step-in">4s In</span>
                <span class="step-dot" id="step-hold">7s Hold</span>
                <span class="step-dot" id="step-out">8s Out</span>
            </div>

            <button class="btn-start-breathing" id="btn-breathing-control" onclick="toggleBreathing()" style="margin-top:10px">Start Session</button>
          </div>

          <!-- Right side: Counter -->
          <div class="breathing-counter-wrap">
            <div class="stat-circle" id="breathing-counter">0</div>
            <div class="stat-label">Cycles</div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── Badges / Leaderboard ─────────────────────────────── -->
    <div class="tab-panel" id="panel-leaderboard">
      <h1 class="section-title">🏅 Community Supporters</h1>
      <p class="section-sub">Heart-powered badges for our most supportive members. Every 💚 counts!</p>

      <!-- Current user's own badge -->
      <div id="my-badge-section"></div>

      <!-- Badge tiers legend -->
      <div style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:32px;">
        <span class="badge-pill" style="background:#f4ad1322; color:#c98b00;">🌟 Bright Star — 50+ hearts</span>
        <span class="badge-pill" style="background:#3498db22; color:#1a6fa8;">🏆 Active Listener — 20+ hearts</span>
        <span class="badge-pill" style="background:#27ae6022; color:#1a7a44;">💚 Kind Soul — 5+ hearts</span>
        <span class="badge-pill" style="background:#7c988522; color:#4d6b55;">🌱 Fresh Voice — 1+ heart</span>
      </div>

      <h2 style="font-family:'Lora',serif; font-size:18px; color:var(--sage-deep); margin-bottom:4px;">Top Supporters</h2>
      <p class="section-sub" style="margin-bottom:0;">Updated live as the community spreads kindness.</p>
      <div class="leaderboard-grid" id="leaderboard-list"></div>
    </div>

  </div><!-- /#app -->

  <!-- ── Post Detail Modal ─────────────────────────── -->
  <div class="modal-overlay" id="post-modal">
    <div class="modal-card">
      <div class="modal-header">
        <div id="modal-post-author"></div>
        <button class="btn-close-modal" onclick="closeModal()">✕</button>
      </div>
      <div class="modal-body" id="modal-body"></div>
    </div>
  </div>

  <!-- ── Edit Post Modal ───────────────────────────── -->
  <div class="modal-overlay" id="edit-post-modal">
    <div class="modal-card">
      <div class="modal-header">
        <h3 style="font-family:'Lora',serif; color:var(--sage-deep)">Edit Your Post</h3>
        <button class="btn-close-modal" onclick="closeEditModal()">✕</button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="edit-post-id">
        <input type="text" class="forum-title-input" id="edit-post-title" placeholder="Give your post a title…">
        <textarea class="journal-textarea" id="edit-post-content"
          placeholder="Write your thoughts, experience, or question. You can post anonymously."
          style="min-height:120px; margin-top: 10px;"></textarea>
        
        <div style="margin-top: 10px;">
          <label style="font-size:13px; color:var(--text-soft); cursor:pointer; display:inline-flex; align-items:center; gap:6px;">
            📷 Change image? <input type="file" id="edit-post-image" accept="image/*" style="font-size:12px">
          </label>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:16px; flex-wrap:wrap; gap:10px">
          <label class="anon-check" style="margin:0">
            <input type="checkbox" id="edit-post-anon"> Post anonymously
          </label>
          <div style="display: flex; gap: 10px;">
            <button class="btn-primary" style="padding: 10px 20px; width: auto;" onclick="submitEditPost()">Save Changes 🌿</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ── Edit Reply Modal ──────────────────────────── -->
  <div class="modal-overlay" id="edit-reply-modal">
    <div class="modal-card">
      <div class="modal-header">
        <h3 style="font-family:'Lora',serif; color:var(--sage-deep)">Edit Your Reply</h3>
        <button class="btn-close-modal" onclick="closeEditReplyModal()">✕</button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="edit-reply-id">
        <input type="hidden" id="edit-reply-post-id">
        <textarea class="journal-textarea" id="edit-reply-content"
          placeholder="Update your reply..."
          style="min-height:120px;"></textarea>
        
        <div style="margin-top: 10px;">
          <label style="font-size:13px; color:var(--text-soft); cursor:pointer; display:inline-flex; align-items:center; gap:6px;">
            📷 Change image? <input type="file" id="edit-reply-image" accept="image/*" style="font-size:12px">
          </label>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:16px; flex-wrap:wrap; gap:10px">
          <label class="anon-check" style="margin:0">
            <input type="checkbox" id="edit-reply-anon"> Reply anonymously
          </label>
          <button class="btn-primary" style="padding: 10px 20px; width: auto;" onclick="submitEditReply()">Update Reply 🌿</button>
        </div>
      </div>
    </div>
  </div>

  <div class="toast" id="toast"></div>

  <script>
    // ════════════════════════════════════════════════
    // STATE
    // ════════════════════════════════════════════════
    let currentUser = null;
    let chatSessionId = null;
    let selectedMoodScore = 0;
    let selectedMoodLabel = '';
    let isSending = false;

    // ════════════════════════════════════════════════
    // DARK MODE
    // ════════════════════════════════════════════════
    function initDarkMode() {
      if (localStorage.getItem('hopeline_dark') === 'true') {
        document.documentElement.setAttribute('data-theme', 'dark');
      }
    }
    initDarkMode(); // Run immediately

    function updateDarkIcon() {
      const btn = document.getElementById('btn-dark-mode');
      if (btn) btn.textContent = document.documentElement.hasAttribute('data-theme') ? '☀️' : '🌙';
    }

    function toggleDarkMode() {
      const root = document.documentElement;
      if (root.hasAttribute('data-theme')) {
        root.removeAttribute('data-theme');
        localStorage.setItem('hopeline_dark', 'false');
      } else {
        root.setAttribute('data-theme', 'dark');
        localStorage.setItem('hopeline_dark', 'true');
      }
      updateDarkIcon();
    }

    // ════════════════════════════════════════════════
    // INIT — check session via a quick ping
    // ════════════════════════════════════════════════
    window.addEventListener('load', () => {
      // Try to get session info from server
      fetch('session_check.php')
        .then(r => r.json())
        .then(d => {
          if (d.logged_in) {
            currentUser = d;
            showApp();
          }
        })
        .catch(() => { });
    });

    // ════════════════════════════════════════════════
    // AUTH
    // ════════════════════════════════════════════════
    function switchAuth(mode) {
      document.getElementById('form-login').style.display = mode === 'login' ? 'block' : 'none';
      document.getElementById('form-register').style.display = mode === 'register' ? 'block' : 'none';
      document.querySelectorAll('.tab-btn').forEach((b, i) => {
        b.classList.toggle('active', (i === 0 && mode === 'login') || (i === 1 && mode === 'register'));
      });
    }

    async function doLogin() {
      const btn = document.getElementById('btn-login');
      btn.disabled = true; btn.textContent = 'Signing in…';
      const fd = new FormData();
      fd.append('action', 'login');
      fd.append('identifier', document.getElementById('login-identifier').value);
      fd.append('password', document.getElementById('login-password').value);
      const res = await postForm('auth.php', fd);
      btn.disabled = false; btn.textContent = 'Sign In';
      showAuthMsg('login-msg', res);
      if (res.success) {
        currentUser = { display_name: res.message.replace('Welcome back, ', '').replace('!', '') };
        setTimeout(showApp, 400);
      }
    }

    async function doRegister() {
      const btn = document.getElementById('btn-register');
      btn.disabled = true; btn.textContent = 'Creating…';
      const fd = new FormData();
      fd.append('action', 'register');
      fd.append('display_name', document.getElementById('reg-name').value);
      fd.append('username', document.getElementById('reg-username').value);
      fd.append('email', document.getElementById('reg-email').value);
      fd.append('password', document.getElementById('reg-password').value);
      const res = await postForm('auth.php', fd);
      btn.disabled = false; btn.textContent = 'Create Account';
      showAuthMsg('register-msg', res);
      if (res.success) setTimeout(showApp, 400);
    }

    async function doLogout() {
      const fd = new FormData();
      fd.append('action', 'logout');
      await postForm('auth.php', fd);
      location.reload();
    }

    function showAuthMsg(id, res) {
      const el = document.getElementById(id);
      el.style.display = 'block';
      el.className = 'auth-msg ' + (res.success ? 'success' : 'error');
      el.textContent = res.message || (res.success ? '✓' : 'An error occurred.');
    }

    async function showApp() {
      // Get fresh user data
      const d = await fetch('session_check.php').then(r => r.json()).catch(() => ({}));
      if (d.logged_in) currentUser = d;

      document.getElementById('auth-screen').style.display = 'none';
      document.getElementById('app').style.display = 'block';

      // Set avatar
      const av = document.getElementById('user-avatar-nav');
      av.textContent = (currentUser.display_name || '?').charAt(0).toUpperCase();
      if (currentUser.avatar_color) av.style.background = currentUser.avatar_color;

      updateDarkIcon();
      loadChat();
      loadJournal();
      loadForum();
      loadSafetyPlan();
      checkPeerStatus();
    }

    // ════════════════════════════════════════════════
    // NAV
    // ════════════════════════════════════════════════
    function showTab(tab) {
      document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
      document.querySelectorAll('.nav-tab').forEach(b => b.classList.remove('active'));
      document.getElementById('panel-' + tab).classList.add('active');
      document.getElementById('tab-' + tab).classList.add('active');
      
      if (tab === 'peer') showPeerList('active');
      if (tab === 'leaderboard') loadLeaderboard();
    }

    // ════════════════════════════════════════════════
    // CHAT
    // ════════════════════════════════════════════════
    async function loadChat(requestedSessionId = null) {
      const fd = new FormData();
      fd.append('action', 'get_session');
      if (requestedSessionId) fd.append('session_id', requestedSessionId);

      const res = await postForm('chat.php', fd);
      if (!res.success) return;
      chatSessionId = res.session_id;
      renderChatHistory(res.messages);
      loadChatHistorySidebar();
    }

    async function loadChatHistorySidebar() {
      const fd = new FormData();
      fd.append('action', 'get_history');
      const res = await postForm('chat.php', fd);
      if (!res.success) return;

      const list = document.getElementById('chat-history-list');
      list.innerHTML = '';
      if (!res.history || res.history.length === 0) {
        list.innerHTML = '<div style="padding:10px;color:var(--text-pale);font-size:12px;text-align:center;">No past chats found</div>';
        return;
      }

      res.history.forEach(session => {
        const item = document.createElement('div');
        item.className = 'history-item' + (session.id == chatSessionId ? ' active' : '');
        item.textContent = session.first_msg || 'Empty Chat';
        item.title = session.first_msg || 'Empty Chat';
        item.onclick = () => {
          if (session.id != chatSessionId) {
            loadChat(session.id);
          }
        };
        list.appendChild(item);
      });
    }

    async function newChatSession() {
      const fd = new FormData(); fd.append('action', 'new_session');
      const res = await postForm('chat.php', fd);
      if (!res.success) return;
      chatSessionId = res.session_id;
      renderChatHistory([]);
      loadChatHistorySidebar();
      showToast('New conversation started 🌸');
    }

    function renderChatHistory(messages) {
      const box = document.getElementById('chat-messages');
      box.innerHTML = '';
      if (!messages.length) {
        box.innerHTML = `
      <div class="msg assistant">
        <div class="msg-avatar">🌸</div>
        <div class="msg-bubble">
          Hi there 💚 I'm Mehjabeen. I'm here to listen, without any judgment.<br><br>
          How are you feeling right now? You can share anything with me — big or small.
        </div>
      </div>`;
        return;
      }
      messages.forEach(m => appendMessage(m.role, m.content));
      scrollChat();
    }

    function appendMessage(role, content) {
      const box = document.getElementById('chat-messages');
      const div = document.createElement('div');
      div.className = 'msg ' + role;
      const avatar = role === 'assistant' ? '🌸' : (currentUser?.display_name || '?').charAt(0).toUpperCase();
      const avatarStyle = role === 'user' && currentUser?.avatar_color ? `style="background:${currentUser.avatar_color};color:#fff"` : '';
      div.innerHTML = `
    <div class="msg-avatar" ${avatarStyle}>${avatar}</div>
    <div class="msg-bubble">${escHtml(content).replace(/\n/g, '<br>')}</div>`;
      box.appendChild(div);
      scrollChat();
    }

    function scrollChat() {
      const box = document.getElementById('chat-messages');
      box.scrollTop = box.scrollHeight;
    }

    async function sendMessage() {
      if (isSending) return;
      const input = document.getElementById('chat-input');
      const text = input.value.trim();
      if (!text || !chatSessionId) return;

      input.value = ''; input.style.height = '';
      isSending = true;
      appendMessage('user', text);

      // Show typing indicator
      const ti = document.getElementById('typing-indicator');
      ti.style.display = 'flex';
      scrollChat();

      const fd = new FormData();
      fd.append('action', 'send');
      fd.append('session_id', chatSessionId);
      fd.append('message', text);
      const res = await postForm('chat.php', fd);

      ti.style.display = 'none';
      isSending = false;

      if (res.success) {
        appendMessage('assistant', res.reply);
        loadChatHistorySidebar();
      } else {
        appendMessage('assistant', 'I\'m having a little trouble connecting right now. Please try again in a moment 💚');
      }
    }

    function autoResize(el) {
      el.style.height = 'auto';
      el.style.height = Math.min(el.scrollHeight, 120) + 'px';
    }

    // ════════════════════════════════════════════════
    // JOURNAL
    // ════════════════════════════════════════════════
    function selectMood(btn) {
      document.querySelectorAll('.mood-btn').forEach(b => b.classList.remove('selected'));
      btn.classList.add('selected');
      selectedMoodScore = parseInt(btn.dataset.score);
      selectedMoodLabel = btn.dataset.label;
    }

    async function saveJournal() {
      if (!selectedMoodScore) { showToast('Please select a mood first 😊', true); return; }
      const content = document.getElementById('journal-content').value.trim();
      const isAnon = document.getElementById('journal-anon').checked ? 1 : 0;

      const fd = new FormData();
      fd.append('action', 'save');
      fd.append('mood_score', selectedMoodScore);
      fd.append('mood_label', selectedMoodLabel);
      fd.append('content', content);
      fd.append('is_anonymous', isAnon);
      const res = await postForm('journal.php', fd);

      if (res.success) {
        showToast(res.message);
        document.getElementById('journal-content').value = '';
        document.querySelectorAll('.mood-btn').forEach(b => b.classList.remove('selected'));
        selectedMoodScore = 0;
        loadJournal();
      } else {
        showToast(res.message, true);
      }
    }

    async function loadJournal() {
      const [listRes, trendRes] = await Promise.all([
        fetch('journal.php?action=list').then(r => r.json()),
        fetch('journal.php?action=trend').then(r => r.json()),
      ]);

      if (trendRes.success && trendRes.trend.length > 1) {
        renderMoodChart(trendRes.trend);
      }

      const container = document.getElementById('journal-entries-list');
      if (!listRes.success || !listRes.entries.length) {
        container.innerHTML = `<div class="empty-state"><div class="icon">📓</div><p>No entries yet. How are you feeling today?</p></div>`;
        return;
      }
      const moodMap = { '1': '😔', '2': '😕', '3': '😐', '4': '🙂', '5': '😊' };
      container.innerHTML = listRes.entries.map(e => `
    <div class="entry-card">
      <button class="btn-delete-entry" onclick="deleteEntry(${e.id})">🗑</button>
      <div class="entry-card-header">
        <div class="entry-mood-badge">${moodMap[e.mood_score] || '😐'} <span>${e.mood_label}</span></div>
        <div class="entry-date">${formatDate(e.created_at)}</div>
      </div>
      ${e.content ? `<div class="entry-content">${escHtml(e.content)}</div>` : ''}
    </div>`).join('');
    }

    function renderMoodChart(trend) {
      const wrap = document.getElementById('mood-chart-wrap');
      wrap.style.display = 'block';
      const chart = document.getElementById('mood-chart');
      const max = 5;
      chart.innerHTML = trend.map(d => `
    <div class="chart-bar-wrap">
      <div class="chart-bar" style="height:${(d.avg_mood / max) * 70}px"></div>
      <div class="chart-label">${d.day.slice(5)}</div>
    </div>`).join('');
    }

    async function deleteEntry(id) {
      if (!confirm('Delete this entry?')) return;
      const fd = new FormData(); fd.append('action', 'delete'); fd.append('id', id);
      const res = await postForm('journal.php', fd);
      if (res.success) { showToast('Entry deleted'); loadJournal(); }
    }

    // ════════════════════════════════════════════════
    // FORUM
    // ════════════════════════════════════════════════
    async function loadForum() {
      const res = await fetch('forum.php?action=list').then(r => r.json());
      const container = document.getElementById('forum-posts-list');
      if (!res.success || !res.posts.length) {
        container.innerHTML = `<div class="empty-state"><div class="icon">🤝</div><p>No posts yet. Be the first to share.</p></div>`;
        return;
      }
      container.innerHTML = res.posts.map(p => `
    <div class="post-card ${p.pinned ? 'pinned' : ''}" onclick="openPost(${p.id})">
      <div style="display:flex; justify-content:space-between; align-items:flex-start">
        <div class="post-author">
          <div class="post-avatar" style="background:${p.avatar_color}">${p.display_name.charAt(0)}</div>
          <div>
            <div class="post-author-name">
              ${escHtml(p.display_name)}
              ${p.badge ? `<span class="badge-pill" style="background:${p.badge.color}22; color:${p.badge.color};">${p.badge.icon} ${p.badge.name}</span>` : ''}
              ${p.pinned ? '📌' : ''}
            </div>
            <div class="post-author-date">${formatDate(p.created_at)}</div>
          </div>
        </div>
        ${p.user_id == currentUser?.user_id ? `
        <div class="post-actions" onclick="event.stopPropagation()">
          <button class="btn-action-small" onclick="openEditPost(${p.id})">Edit</button>
          <button class="btn-action-small btn-action-delete" onclick="deletePost(${p.id})">Delete</button>
        </div>` : `
        <button class="btn-action-small btn-action-chat" onclick="event.stopPropagation(); startPrivateChat(${p.user_id})">Chat Privately</button>
        `}
      </div>
      <div class="post-title">${escHtml(p.title)}</div>
      <div class="post-preview">${escHtml(p.content).slice(0, 160).replace(/\n/g, ' ')}${p.content.length > 160 ? '…' : ''}</div>
      ${p.image_path ? `<div style="margin-top:10px; border-radius:8px; overflow:hidden; max-height:200px; max-width:100%;"><img src="${p.image_path}" style="width:100%; object-fit:cover;"></div>` : ''}
      <div class="post-meta">
        <div class="post-meta-item">💚 ${p.heart_count}</div>
        <div class="post-meta-item">💬 ${p.reply_count}</div>
      </div>
    </div>`).join('');
    }

    async function submitPost() {
      const title = document.getElementById('forum-post-title').value.trim();
      const content = document.getElementById('forum-post-content').value.trim();
      const isAnon = document.getElementById('forum-anon').checked ? 1 : 0;
      const image = document.getElementById('forum-post-image').files[0];
      if (!title || !content) { showToast('Please add a title and content', true); return; }

      const fd = new FormData();
      fd.append('action', 'create_post');
      fd.append('title', title); fd.append('content', content); fd.append('is_anonymous', isAnon);
      if (image) fd.append('image', image);
      const res = await postForm('forum.php', fd);
      if (res.success) {
        showToast(res.message);
        document.getElementById('forum-post-title').value = '';
        document.getElementById('forum-post-content').value = '';
        loadForum();
      } else {
        showToast(res.message, true);
      }
    }

    async function openPost(postId) {
      const res = await fetch(`forum.php?action=get_post&post_id=${postId}`).then(r => r.json());
      if (!res.success) return;
      const p = res.post;

      document.getElementById('modal-post-author').innerHTML = `
    <div style="display:flex; justify-content:space-between; align-items:flex-start; width:100%">
      <div class="post-author">
        <div class="post-avatar" style="background:${p.avatar_color}">${p.display_name.charAt(0)}</div>
        <div>
          <div class="post-author-name">
            ${escHtml(p.display_name)}
            ${p.badge ? `<span class="badge-pill" style="background:${p.badge.color}22; color:${p.badge.color};">${p.badge.icon} ${p.badge.name}</span>` : ''}
          </div>
          <div class="post-author-date">${formatDate(p.created_at)}</div>
        </div>
      </div>
      ${p.user_id == currentUser?.user_id ? `
      <div class="post-actions">
        <button class="btn-action-small" onclick="openEditPost(${p.id}); closeModal()">Edit</button>
        <button class="btn-action-small btn-action-delete" onclick="deletePost(${p.id}); closeModal()">Delete</button>
      </div>` : `
      <button class="btn-action-small btn-action-chat" onclick="startPrivateChat(${p.user_id})">Chat Privately</button>
      `}
    </div>`;

      const repliesHtml = res.replies.length
        ? res.replies.map(r => `
        <div class="reply-card">
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
            <div class="post-author" style="margin:0">
              <div class="post-avatar" style="background:${r.avatar_color};width:24px;height:24px;font-size:10px">${r.display_name.charAt(0)}</div>
              <div>
                <div class="post-author-name" style="font-size:12px">
                  ${escHtml(r.display_name)}
                  ${r.badge ? `<span class="badge-pill" style="background:${r.badge.color}22; color:${r.badge.color}; font-size:9px;">${r.badge.icon} ${r.badge.name}</span>` : ''}
                </div>
                <div class="post-author-date">${formatDate(r.created_at)}</div>
              </div>
            </div>
            <div class="post-actions">
              ${r.user_id == currentUser?.user_id ? `
              <button class="btn-action-small" style="font-size:10px; padding:2px 6px" onclick="openEditReply(${r.id}, ${p.id})">Edit</button>
              <button class="btn-action-small btn-action-delete" style="font-size:10px; padding:2px 6px" onclick="deleteReply(${r.id}, ${p.id})">Delete</button>
              ` : `
              <button class="btn-action-small btn-action-chat" style="font-size:10px; padding:2px 6px" onclick="startPrivateChat(${r.user_id})">Chat</button>
              `}
              <button class="heart-btn ${r.user_hearted ? 'hearted' : ''}" onclick="toggleHeart(null,${r.id},this)">
                💚 <span>${r.heart_count}</span>
              </button>
            </div>
          </div>
          <div style="font-size:14px;color:var(--text);line-height:1.6">${escHtml(r.content).replace(/\n/g, '<br>')}</div>
          ${r.image_path ? `<div style="margin-top:8px; border-radius:6px; overflow:hidden; max-width:100%; max-height:150px"><img src="${r.image_path}" style="width:100%; object-fit:contain;"></div>` : ''}
        </div>`).join('')
        : `<div style="text-align:center;padding:24px 0;color:var(--text-soft);font-size:14px">No replies yet. Be the first to offer support 💚</div>`;

      document.getElementById('modal-body').innerHTML = `
    <div class="post-title" style="font-size:20px;margin-bottom:14px">${escHtml(p.title)}</div>
    <div style="font-size:15px;color:var(--text);line-height:1.7;margin-bottom:16px;font-family:'Lora',serif">${escHtml(p.content).replace(/\n/g, '<br>')}</div>
    ${p.image_path ? `<div style="margin-bottom:16px; border-radius:8px; overflow:hidden; max-width:100%;"><img src="${p.image_path}" style="width:100%;"></div>` : ''}
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px">
      <button class="heart-btn ${p.user_hearted ? 'hearted' : ''}" onclick="toggleHeart(${p.id},null,this)">
        💚 <span>${p.heart_count}</span>
      </button>
    </div>
    <hr style="border:none;border-top:1px solid var(--warm-mid);margin-bottom:20px">
    <h4 style="font-family:'Lora',serif;color:var(--sage-deep);margin-bottom:14px">Replies (${res.replies.length})</h4>
    <div id="modal-replies">${repliesHtml}</div>
    <div style="margin-top:20px">
      <textarea class="journal-textarea" id="reply-content" placeholder="Write a supportive reply…" style="min-height:80px"></textarea>
      
      <div style="margin-top: 10px;">
        <label style="font-size:13px; color:var(--text-soft); cursor:pointer; display:inline-flex; align-items:center; gap:6px;">
          📷 <input type="file" id="reply-image" accept="image/*" style="font-size:12px">
        </label>
      </div>

      <div style="display:flex;justify-content:space-between;align-items:center;margin-top:10px;flex-wrap:wrap;gap:8px">
        <label class="anon-check" style="margin:0"><input type="checkbox" id="reply-anon"> Reply anonymously</label>
        <button class="btn-save" onclick="submitReply(${p.id})">Reply 💚</button>
      </div>
    </div>`;

      document.getElementById('post-modal').classList.add('open');
    }

    async function submitReply(postId) {
      const content = document.getElementById('reply-content').value.trim();
      const isAnon = document.getElementById('reply-anon').checked ? 1 : 0;
      const image = document.getElementById('reply-image').files[0];
      if (!content) { showToast('Please write something', true); return; }

      const fd = new FormData();
      fd.append('action', 'reply');
      fd.append('post_id', postId);
      fd.append('content', content);
      fd.append('is_anonymous', isAnon);
      if (image) fd.append('image', image);
      const res = await postForm('forum.php', fd);

      if (res.success) {
        showToast(res.message);
        openPost(postId);
        loadForum();
      }
    }

    async function toggleHeart(postId, replyId, btn) {
      const fd = new FormData();
      fd.append('action', 'toggle_heart');
      if (postId) fd.append('post_id', postId);
      if (replyId) fd.append('reply_id', replyId);
      const res = await postForm('forum.php', fd);
      if (!res.success) return;
      btn.classList.toggle('hearted', res.hearted);
      const countEl = btn.querySelector('span');
      if (countEl) {
        let c = parseInt(countEl.textContent || '0');
        countEl.textContent = res.hearted ? c + 1 : Math.max(0, c - 1);
      }
    }

    async function deletePost(postId) {
      if (!confirm('Are you sure you want to delete this post? This cannot be undone.')) return;
      const fd = new FormData();
      fd.append('action', 'delete_post');
      fd.append('post_id', postId);
      const res = await postForm('forum.php', fd);
      if (res.success) {
        showToast(res.message);
        loadForum();
      } else {
        showToast(res.message, true);
      }
    }

    async function openEditPost(postId) {
      const res = await fetch(`forum.php?action=get_post&post_id=${postId}`).then(r => r.json());
      if (!res.success) return;
      const p = res.post;

      document.getElementById('edit-post-id').value = p.id;
      document.getElementById('edit-post-title').value = p.title;
      document.getElementById('edit-post-content').value = p.content;
      document.getElementById('edit-post-anon').checked = p.is_anonymous == 1;
      document.getElementById('edit-post-image').value = ''; // Reset image input

      document.getElementById('edit-post-modal').classList.add('open');
    }

    function closeEditModal() {
      document.getElementById('edit-post-modal').classList.remove('open');
    }

    async function submitEditPost() {
      const id = document.getElementById('edit-post-id').value;
      const title = document.getElementById('edit-post-title').value.trim();
      const content = document.getElementById('edit-post-content').value.trim();
      const isAnon = document.getElementById('edit-post-anon').checked ? 1 : 0;
      const image = document.getElementById('edit-post-image').files[0];

      if (!title || !content) { showToast('Title and content are required', true); return; }

      const fd = new FormData();
      fd.append('action', 'edit_post');
      fd.append('post_id', id);
      fd.append('title', title);
      fd.append('content', content);
      fd.append('is_anonymous', isAnon);
      if (image) fd.append('image', image);

      const res = await postForm('forum.php', fd);
      if (res.success) {
        showToast(res.message);
        closeEditModal();
        loadForum();
      } else {
        showToast(res.message, true);
      }
    }

    async function deleteReply(replyId, postId) {
      if (!confirm('Delete this reply?')) return;
      const fd = new FormData();
      fd.append('action', 'delete_reply');
      fd.append('reply_id', replyId);
      const res = await postForm('forum.php', fd);
      if (res.success) {
        showToast(res.message);
        openPost(postId); // Refresh post details
      } else {
        showToast(res.message, true);
      }
    }

    async function openEditReply(replyId, postId) {
      const res = await fetch(`forum.php?action=get_post&post_id=${postId}`).then(r => r.json());
      if (!res.success) return;
      const reply = res.replies.find(r => r.id == replyId);
      if (!reply) return;

      document.getElementById('edit-reply-id').value = reply.id;
      document.getElementById('edit-reply-post-id').value = postId;
      document.getElementById('edit-reply-content').value = reply.content;
      document.getElementById('edit-reply-anon').checked = (reply.is_anonymous == 1);
      document.getElementById('edit-reply-image').value = '';

      document.getElementById('edit-reply-modal').classList.add('open');
    }

    function closeEditReplyModal() {
      document.getElementById('edit-reply-modal').classList.remove('open');
    }

    async function submitEditReply() {
      const id = document.getElementById('edit-reply-id').value;
      const postId = document.getElementById('edit-reply-post-id').value;
      const content = document.getElementById('edit-reply-content').value.trim();
      const isAnon = document.getElementById('edit-reply-anon').checked ? 1 : 0;
      const image = document.getElementById('edit-reply-image').files[0];

      if (!content) { showToast('Content is required', true); return; }

      const fd = new FormData();
      fd.append('action', 'edit_reply');
      fd.append('reply_id', id);
      fd.append('content', content);
      fd.append('is_anonymous', isAnon);
      if (image) fd.append('image', image);

      const res = await postForm('forum.php', fd);
      if (res.success) {
        showToast(res.message);
        closeEditReplyModal();
        openPost(postId); // Refresh
      } else {
        showToast(res.message, true);
      }
    }

    async function startPrivateChat(targetUserId) {
      if (!confirm('Start a private anonymous chat with this member?')) return;
      
      const fd = new FormData();
      fd.append('action', 'start_with_user');
      fd.append('target_user_id', targetUserId);
      
      const res = await postForm('peer_chat.php', fd);
      if (res.success) {
        showToast('Private chat started! 🌿');
        showTab('peer');
        showPeerChat(res.session_id); 
        if (document.getElementById('post-modal').classList.contains('open')) {
           document.getElementById('post-modal').classList.remove('open');
        }
      } else {
        showToast(res.message, true);
      }
    }

    function closeModal() {
      document.getElementById('post-modal').classList.remove('open');
      loadForum();
    }

    // Close modals on overlay click
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
      overlay.addEventListener('click', function (e) {
        if (e.target === this) {
          this.classList.remove('open');
          loadForum();
        }
      });
    });

    // ════════════════════════════════════════════════
    // SAFETY PLAN
    // ════════════════════════════════════════════════
    async function loadSafetyPlan() {
      const res = await fetch('safety_plan.php?action=get').then(r => r.json());
      if (!res.success) return;
      const p = res.plan;
      document.getElementById('sp-warning').value = p.warning_signs || '';
      document.getElementById('sp-coping').value = p.coping_strategies || '';
      document.getElementById('sp-distractions').value = p.people_distractions || '';
      document.getElementById('sp-people').value = p.people_help || '';
      document.getElementById('sp-prof').value = p.professionals || '';
      document.getElementById('sp-env').value = p.environment_safe || '';
      document.getElementById('sp-reasons').value = p.reasons_to_live || '';
    }

    async function saveSafetyPlan() {
      const fd = new FormData();
      fd.append('action', 'save');
      fd.append('warning_signs', document.getElementById('sp-warning').value);
      fd.append('coping_strategies', document.getElementById('sp-coping').value);
      fd.append('people_distractions', document.getElementById('sp-distractions').value);
      fd.append('people_help', document.getElementById('sp-people').value);
      fd.append('professionals', document.getElementById('sp-prof').value);
      fd.append('environment_safe', document.getElementById('sp-env').value);
      fd.append('reasons_to_live', document.getElementById('sp-reasons').value);

      const res = await postForm('safety_plan.php', fd);
      if (res.success) {
        showToast(res.message);
      } else {
        showToast(res.message, true);
      }
    }

    // ════════════════════════════════════════════════
    // PEER CHAT
    // ════════════════════════════════════════════════
    let peerChatTimer = null;
    let currentPeerSessionId = null;

    async function checkPeerStatus() {
      if (!currentPeerSessionId) return;

      const res = await fetch(`peer_chat.php?action=get_messages&session_id=${currentPeerSessionId}`).then(r => r.json());
      
      if (res.success) {
        renderPeerMessages(res.messages, res.status);
      }

      if (res.status === 'active' || res.status === 'waiting') {
        clearTimeout(peerChatTimer);
        peerChatTimer = setTimeout(checkPeerStatus, 3000);
      }
    }

    let peerListFilter = 'active';

    async function loadPeerList(filter = 'active') {
      peerListFilter = filter;
      const res = await fetch(`peer_chat.php?action=list&filter=${filter}`).then(r => r.json());
      if (!res.success) return;
      
      // Update UI tabs
      document.getElementById('btn-active-list').classList.toggle('active', filter === 'active');
      document.getElementById('btn-history-list').classList.toggle('active', filter === 'history');
      
      const list = document.getElementById('peer-history-list');
      if (!res.sessions || res.sessions.length === 0) {
        const msg = filter === 'active' ? 'No active chats.' : 'No previous chats yet.';
        const icon = filter === 'active' ? '💬' : '📜';
        list.innerHTML = `<div class="empty-state"><div class="icon">${icon}</div><p>${msg}</p></div>`;
        return;
      }
      list.innerHTML = res.sessions.map(s => `
        <div class="history-card" onclick="showPeerChat(${s.id})">
          <div style="flex:1; overflow:hidden">
            <div class="history-meta">${formatSessionDate(s.created_at)} ${s.is_random == 1 ? '<span style="color:var(--sage); font-style:italic; margin-left:5px">(randomly connected)</span>' : ''}</div>
            <div class="history-preview">${s.last_msg ? escHtml(s.last_msg) : 'No messages yet.'}</div>
          </div>
          <div style="font-size:10px; font-weight:bold; color:${s.status == 'closed' ? 'var(--text-soft)' : 'var(--sage)'}; padding-left:10px">
            ${s.status.toUpperCase()}
          </div>
        </div>
      `).join('');
    }

    function formatSessionDate(str) {
      const d = new Date(str);
      const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
      const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
      return `${days[d.getDay()]}, ${months[d.getMonth()]} ${d.getDate()} · ${d.getHours() % 12 || 12}:${d.getMinutes().toString().padStart(2, '0')} ${d.getHours() >= 12 ? 'PM' : 'AM'}`;
    }

    function showPeerList(filter = 'active') {
      currentPeerSessionId = null;
      document.getElementById('peer-history-view').style.display = 'block';
      document.getElementById('peer-chat-view').style.display = 'none';
      
      document.getElementById('btn-active-list').style.display = 'inline-block';
      document.getElementById('btn-history-list').style.display = 'inline-block';
      document.getElementById('btn-random-peer').style.display = 'inline-block';
      document.getElementById('btn-back-to-list').style.display = 'none';
      document.getElementById('btn-leave-peer').style.display = 'none';
      
      document.getElementById('peer-status-text').textContent = 'Safe & Confidential';
      loadPeerList(filter);
      clearTimeout(peerChatTimer);
    }

    async function showPeerChat(sessionId) {
      currentPeerSessionId = sessionId;
      document.getElementById('peer-history-view').style.display = 'none';
      document.getElementById('peer-chat-view').style.display = 'flex';
      
      document.getElementById('btn-active-list').style.display = 'none';
      document.getElementById('btn-history-list').style.display = 'none';
      document.getElementById('btn-random-peer').style.display = 'none';
      document.getElementById('btn-back-to-list').style.display = 'inline-block';
      
      loadPeerMessages(sessionId);
    }

    async function peerChatFind() {
      document.getElementById('btn-find-peer').textContent = 'Looking...';
      const res = await fetch('peer_chat.php?action=find').then(r => r.json());
      document.getElementById('btn-find-peer').textContent = 'Find Someone';
      if (res.success) { 
        showPeerChat(res.session_id);
        checkPeerStatus(); 
      }
    }

    async function peerChatLeave() {
      if (!currentPeerSessionId) return;
      if (!confirm('Are you sure you want to leave this chat? It will be moved to History.')) return;
      const fd = new FormData();
      fd.append('action', 'leave');
      fd.append('session_id', currentPeerSessionId);
      await postForm('peer_chat.php', fd);
      showPeerList('active');
    }

    async function peerChatSend() {
      const input = document.getElementById('peer-chat-input');
      const content = input.value.trim();
      if (!content || !currentPeerSessionId) return;
      input.value = ''; input.style.height = '';
      const fd = new FormData(); 
      fd.append('action', 'send'); 
      fd.append('content', content);
      fd.append('session_id', currentPeerSessionId);
      await postForm('peer_chat.php', fd);
      loadPeerMessages(currentPeerSessionId);
    }

    async function loadPeerMessages(sessionId) {
      const res = await fetch(`peer_chat.php?action=get_messages&session_id=${sessionId}`).then(r => r.json());
      if (!res.success) return;
      renderPeerMessages(res.messages, res.status);
      
      // Poll if active
      if (res.status === 'active' || res.status === 'waiting') {
        clearTimeout(peerChatTimer);
        peerChatTimer = setTimeout(checkPeerStatus, 3000);
      }
    }

    function renderPeerMessages(messages, status) {
      const box = document.getElementById('peer-chat-messages');
      box.innerHTML = '';
      messages.forEach(m => {
        const div = document.createElement('div');
        if (m.role === 'system') {
          div.innerHTML = `<div style="text-align:center;font-size:12px;color:var(--text-pale);margin:10px 0">${m.content}</div>`;
        } else {
          div.className = 'msg ' + (m.role === 'me' ? 'user' : 'assistant');
          const avatar = m.role === 'me' ? 'M' : 'P';
          const avatarStyle = m.role === 'me' ? 'background:var(--sage-deep);color:#fff' : 'background:var(--sage-pale);color:var(--sage-deep)';
          div.innerHTML = `<div class="msg-avatar" style="${avatarStyle}">${avatar}</div><div class="msg-bubble">${escHtml(m.content).replace(/\n/g, '<br>')}</div>`;
        }
        box.appendChild(div);
      });
      box.scrollTop = box.scrollHeight;

      // Input Logic
      const input = document.getElementById('peer-chat-input');
      const sendBtn = document.getElementById('btn-peer-send');
      const leaveBtn = document.getElementById('btn-leave-peer');

      if (status === 'active') {
        input.disabled = false; sendBtn.disabled = false;
        document.getElementById('peer-status-text').textContent = 'Connected';
        leaveBtn.style.display = 'inline-block';
      } else if (status === 'waiting') {
        input.disabled = true; sendBtn.disabled = true;
        document.getElementById('peer-status-text').textContent = 'Waiting for peer...';
        leaveBtn.style.display = 'inline-block';
      } else {
        input.disabled = true; sendBtn.disabled = true;
        document.getElementById('peer-status-text').textContent = 'Session Closed';
        leaveBtn.style.display = 'none';
      }
    }

    // ════════════════════════════════════════════════
    // GROUNDING (4-7-8 Breathing)
    // ════════════════════════════════════════════════
    let isBreathing = false;
    let breathingCount = 0;

    function toggleBreathing() {
      const btn = document.getElementById('btn-breathing-control');
      const text = document.getElementById('breathing-text');
      const instruction = document.getElementById('breathing-instruction');
      const circle = document.getElementById('breathing-circle');
      const emoji = document.getElementById('breathing-emoji');
      const timer = document.getElementById('breathing-timer');
      const counter = document.getElementById('breathing-counter');

      if (isBreathing) {
        // Stop
        isBreathing = false;
        btn.textContent = 'Start Session';
        text.textContent = 'Ready?';
        instruction.textContent = 'Press Start to begin.';
        circle.style.transform = 'scale(1)';
        emoji.textContent = '🌿';
        timer.textContent = '0';
        document.querySelectorAll('.step-dot').forEach(d => d.classList.remove('active'));
      } else {
        // Start
        isBreathing = true;
        breathingCount = 0;
        counter.textContent = '0';
        btn.textContent = 'Stop Session';
        runBreathingCycle();
      }
    }

    async function runBreathingCycle() {
      if (!isBreathing) return;

      const timer = document.getElementById('breathing-timer');
      const text = document.getElementById('breathing-text');
      const instruction = document.getElementById('breathing-instruction');
      const circle = document.getElementById('breathing-circle');
      const emoji = document.getElementById('breathing-emoji');
      const counter = document.getElementById('breathing-counter');
      const dots = {
          in: document.getElementById('step-in'),
          hold: document.getElementById('step-hold'),
          out: document.getElementById('step-out')
      };

      // 1. Breathe In (4 seconds)
      setActiveStep(dots, 'in');
      text.textContent = 'Breathe In...';
      instruction.textContent = 'Fill your lungs with air.';
      emoji.textContent = '🌬️';
      circle.style.transform = 'scale(1.8)';
      circle.style.backgroundColor = 'var(--sage-light)';
      await countdown(4, timer);
      if (!isBreathing) return;

      // 2. Hold (7 seconds)
      setActiveStep(dots, 'hold');
      text.textContent = 'Hold...';
      instruction.textContent = 'Stay calm and focused.';
      emoji.textContent = '🧘';
      circle.style.backgroundColor = 'var(--sage)';
      await countdown(7, timer);
      if (!isBreathing) return;

      // 3. Exhale (8 seconds)
      setActiveStep(dots, 'out');
      text.textContent = 'Exhale...';
      instruction.textContent = 'Release all tension.';
      emoji.textContent = '🍃';
      circle.style.transform = 'scale(1)';
      circle.style.backgroundColor = 'var(--sage-pale)';
      await countdown(8, timer);
      if (!isBreathing) return;

      // Increment Cycle
      breathingCount++;
      counter.textContent = breathingCount;
      showToast('Cycle ' + breathingCount + ' complete 🌿');

      // Loop
      runBreathingCycle();
    }

    async function countdown(seconds, element) {
        for (let i = seconds; i > 0; i--) {
            if (!isBreathing) return;
            element.textContent = i;
            await sleep(1000);
        }
        element.textContent = '0';
    }

    function setActiveStep(dots, active) {
        Object.keys(dots).forEach(k => dots[k].classList.toggle('active', k === active));
    }

    function sleep(ms) {
      return new Promise(resolve => setTimeout(resolve, ms));
    }

    // ════════════════════════════════════════════════
    // UTILS
    // ════════════════════════════════════════════════
    // ════════════════════════════════════════════════
    // LEADERBOARD & BADGES
    // ════════════════════════════════════════════════
    async function loadLeaderboard() {
      const res = await fetch('forum.php?action=leaderboard').then(r => r.json());
      if (!res.success) return;

      // ── My Badge Banner ──
      const mySection = document.getElementById('my-badge-section');
      if (res.my_badge) {
        const b = res.my_badge;
        const pct  = b.next_at ? Math.min(100, Math.round((b.hearts / b.next_at) * 100)) : 100;
        const nextTip = b.next_at
          ? `${b.hearts}/${b.next_at} hearts — ${b.next_at - b.hearts} more to earn <strong>${b.next}</strong>`
          : `You've reached the highest badge! 🌟`;
        mySection.innerHTML = `
          <div class="my-badge-banner">
            <div class="my-badge-icon">${b.icon}</div>
            <div class="my-badge-info">
              <h3>${b.name}</h3>
              <p>You've earned ${b.hearts} 💚 from the community</p>
            </div>
            <div class="progress-bar-wrap">
              <div class="progress-bar-bg">
                <div class="progress-bar-fill" style="width:${pct}%; background:${b.color};"></div>
              </div>
              <div class="progress-label">${nextTip}</div>
            </div>
          </div>`;
      } else {
        mySection.innerHTML = `
          <div class="my-badge-banner" style="border-color:var(--warm-mid)">
            <div class="my-badge-icon">🌟</div>
            <div class="my-badge-info">
              <h3>No badge yet</h3>
              <p>Write helpful posts & replies. When someone hearts you, your journey begins! 💚</p>
            </div>
          </div>`;
      }

      // ── Leaderboard Grid ──
      const rankMedals = ['🥇','🥈','🥉'];
      const rankClass  = ['gold','silver','bronze'];
      const list = document.getElementById('leaderboard-list');

      if (!res.leaders.length) {
        list.innerHTML = `<div class="empty-state"><div class="icon">💚</div><p>Be the first to earn a badge by supporting others!</p></div>`;
        return;
      }

      list.innerHTML = res.leaders.map((l, i) => {
        const rank    = i < 3 ? rankMedals[i] : `#${i + 1}`;
        const rankCls = i < 3 ? rankClass[i]  : '';
        const badge   = l.badge;
        return `
        <div class="leader-card">
          <div class="leader-rank ${rankCls}">${rank}</div>
          <div class="leader-avatar" style="background:${l.avatar_color}">${l.display_name.charAt(0).toUpperCase()}</div>
          <div class="leader-info">
            <div class="leader-name">${escHtml(l.display_name)}</div>
            ${badge ? `<span class="badge-pill" style="background:${badge.color}22; color:${badge.color}; margin:4px 0; display:inline-flex;">${badge.icon} ${badge.name}</span>` : ''}
            <div class="leader-stats">
              <span class="leader-stat">💚 ${l.total_hearts}</span>
              <span class="leader-stat">📝 ${l.post_count} posts</span>
              <span class="leader-stat">💬 ${l.reply_count} replies</span>
            </div>
          </div>
        </div>`;
      }).join('');
    }

    // ════════════════════════════════════════════════
    async function postForm(url, formData) {
      try {
        const r = await fetch(url, { method: 'POST', body: formData });
        return await r.json();
      } catch { return { success: false, message: 'Network error. Please try again.' }; }
    }

    function escHtml(str) {
      return String(str || '')
        .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }

    function formatDate(str) {
      if (!str) return '';
      const d = new Date(str);
      return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) + ' · ' +
        d.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
    }

    let toastTimer;
    function showToast(msg, isError = false) {
      const t = document.getElementById('toast');
      t.textContent = msg;
      t.className = 'toast show' + (isError ? ' error' : '');
      clearTimeout(toastTimer);
      toastTimer = setTimeout(() => t.classList.remove('show'), 3000);
    }
  </script>
</body>

</html>
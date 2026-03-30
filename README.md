# 🌿 HopeLine — Setup Guide

A suicide prevention & mental health support platform for teens and young adults.
Built with HTML, CSS, PHP, MySQL (XAMPP).

---

## 📁 File Structure

```
hopeline/
├── index.html          ← Main frontend (single-page app)
├── config.php          ← Database + API config (EDIT THIS)
├── auth.php            ← Login / Register / Logout
├── chat.php            ← AI chat backend (LongCat API)
├── journal.php         ← Mood journal backend
├── forum.php           ← Community forum backend
├── peer_chat.php       ← Anonymous peer support backend
├── safety_plan.php     ← Safety Plan backend
├── session_check.php   ← Session status check
└── database.sql        ← Run this to set up your database
```

---

## 🚀 Setup Steps

### 1. Install & Start XAMPP
- Download XAMPP from https://www.apachefriends.org
- Start **Apache** and **MySQL** in the XAMPP control panel

### 2. Create the Database
- Open your browser → go to `http://localhost/phpmyadmin`
- Click **"New"** (left sidebar) → create database named `hopeline`
- Click on the `hopeline` database
- Click **"Import"** tab → choose `database.sql` → click **Go**
- ✅ All tables are now created

### 3. Place Files
- Copy the entire `hopeline/` folder into:
  - **Windows**: `C:\xampp\htdocs\hopeline\`
  - **Mac**: `/Applications/XAMPP/htdocs/hopeline/`
  - **Linux**: `/opt/lampp/htdocs/hopeline/`

### 4. Configure the App
Open `config.php` and edit:

```php
define('AI_API_KEY', 'ak_2Bl7ew0UM1SE7pi1zP5bR6z35Ai0K');  // ← paste your key here
define('AI_API_URL', 'https://api.longcat.ai/v1/chat/completions'); // adjust if needed
define('AI_MODEL',   'LongCat-Flash-Thinking-2601');  // ← use your LongCat model name
```

If your MySQL has a password (not default XAMPP):
```php
define('DB_PASS', 'your_mysql_password');
```

### 5. Open the App
- Browser → `http://localhost/hopeline/`
- Register an account → Start using HopeLine!

---

## 🔧 LongCat AI API Notes

Your LongCat AI API likely follows OpenAI-compatible format.
- Check your LongCat dashboard for the correct **base URL** and **model name**
- Common model names: `gpt-4o`, `claude-3-5-sonnet`, etc.
- Update `AI_API_URL` and `AI_MODEL` in `config.php` accordingly

---

## 🌿 Features

| Feature | Description |
|---------|-------------|
| 🤖 AI Chat (Mehjabeen) | Compassionate AI companion, crisis-aware, always recommends help |
| 📓 Mood Journal | Daily mood tracking (1–5 scale), private entries, mood trend chart |
| 📞 Hotlines | Bangladesh + international crisis lines, coping strategies |
| 🤝 Community Forum | Anonymous posting, replies, heart reactions |
| 🛡️ Safety Plan | Personalized step-by-step crisis plan to keep users safe |

---

## 🔒 Security Notes

- Passwords are hashed with bcrypt (secure)
- All DB queries use PDO prepared statements (SQL injection safe)
- HTML output is escaped (XSS safe)
- For production: add HTTPS, rate limiting, and email verification

---

## 📞 Crisis Resources (Pre-loaded)

- 🇧🇩 Kaan Pete Roi: **01779-554391**
- 🇧🇩 Bangladesh Emergency: **999**
- 🌏 iCall: **+91-9152987821**
- 🌐 Befrienders Worldwide: befrienders.org
- 🇧🇩 NIMH Helpline: **16789**

---

Built with 💚 for mental health support.

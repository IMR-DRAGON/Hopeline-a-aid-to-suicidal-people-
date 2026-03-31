# 🌿 HopeLine — Mental Health Support Platform

HopeLine is a suicide prevention and mental health support platform designed to provide a safe, anonymous space for teens and young adults. It integrates an empathetic AI companion (Mehjabeen), an anonymous peer support network, and a variety of self-help tools — all in a kind, judgment-free environment.

---

## ✨ Key Features

| Feature | Description |
|---------|-------------|
| 🤖 **AI Support (Mehjabeen)** | A compassionate, crisis-aware AI companion that provides immediate emotional support and crisis intervention, available 24/7. |
| 🤝 **Anonymous Peer Chat** | Connects users anonymously for mutual peer support, allowing them to share experiences in a completely safe environment. |
| 🗨️ **Community Forum** | An anonymous space for community discussion, support threads, and encouragement with heart reactions. |
| 🛡️ **Personalized Safety Plan** | A guided tool to help users build a step-by-step crisis plan to stay safe during difficult moments. |
| 📓 **Mood Tracker & Journal** | Private daily mood logging with trend charts to help users monitor and understand their emotional well-being. |
| 📞 **Emergency Hub** | Quick access to verified international and local (Bangladesh) crisis helplines, always one tap away. |
| 🌬️ **Pause & Breathe (Grounding Tool)** | An interactive 4-7-8 breathing exercise with a live animated circle, countdown timer, step indicators, and a cycle counter — designed for immediate anxiety relief. |
| 🏅 **Support Badges** | A community recognition system that awards badges to helpful forum members based on hearts received. Includes a live leaderboard and personal progress tracker. |

### 🏅 Badge Tiers

| Badge | Name | Hearts Required |
|-------|------|----------------|
| 🌱 | Fresh Voice | 1+ hearts |
| 💚 | Kind Soul | 5+ hearts |
| 🏆 | Active Listener | 20+ hearts |
| 🌟 | Bright Star | 50+ hearts |

---

## 🛠️ Technology Stack

- **Backend**: PHP (7.4+)
- **Database**: MySQL / MariaDB
- **Frontend**: HTML5, Vanilla CSS3, Javascript (ES6)
- **AI Integration**: LongCat API (OpenAI-compatible)
- **Security**: Password hashing (Bcrypt), Prepared Statements (PDO), XSS prevention

---

## 📁 Project Structure

```
hopeline/
├── index.php           ← Public landing page
├── login.php           ← Login & registration page
├── app.php             ← Main application (requires login)
├── config.php          ← DB + API config (DO NOT COMMIT)
├── config.php.example  ← Safe template for config
├── auth.php            ← Login / Register / Logout API
├── chat.php            ← AI chat backend (LongCat API)
├── forum.php           ← Community forum + badges/leaderboard API
├── journal.php         ← Mood journal backend
├── peer_chat.php       ← Anonymous peer support backend
├── safety_plan.php     ← Safety Plan backend
├── session_check.php   ← Session status check
├── database.sql        ← Run this to set up your database
└── uploads/            ← User-uploaded images (gitignored)
```

---

## 🚀 Installation & Local Setup (XAMPP)

### 1. Prerequisites
- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL)

### 2. Database Configuration
1. Open [phpMyAdmin](http://localhost/phpmyadmin).
2. Create a new database named `hopeline`.
3. Import the `database.sql` file provided in the repository.

### 3. Application Setup
1. Clone this repository into your XAMPP `htdocs` folder:
   ```bash
   git clone https://github.com/IMR-DRAGON/Hopeline-a-aid-to-suicidal-people-.git
   ```
2. Copy `config.php.example` to `config.php`:
   ```bash
   cp config.php.example config.php
   ```
3. Edit `config.php` and provide your **LongCat API Key**.
4. Open your browser and go to `http://localhost/hopeline`.

---

## 📋 Security & API Notes

- **API Keys**: Never commit your `config.php` file. It is added to `.gitignore` to keep your credentials private.
- **Privacy**: User identities are kept anonymous wherever possible to encourage open communication.
- **SQL Safety**: All database queries use PDO prepared statements to prevent SQL injection.

---

## 🌍 Crisis Resources (Pre-loaded)

- 🇧🇩 **Bangladesh Emergency**: 999
- 🇧🇩 **Kaan Pete Roi**: 01779-554391
- 🇧🇩 **NIMH Helpline**: 16789
- 🌐 **International**: [Befrienders Worldwide](https://www.befrienders.org)

---

Built with 💚 for mental health awareness.

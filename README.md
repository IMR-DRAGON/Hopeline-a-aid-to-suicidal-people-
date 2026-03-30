# 🌿 HopeLine — Mental Health Support Platform

HopeLine is a suicide prevention and mental health support platform designed to provide a safe, anonymous space for teens and young adults. It integrates an empathetic AI companion (Mehjabeen), an anonymous peer support network, and a variety of self-help tools.

---

## ✨ Key Features

| Feature | Description |
|---------|-------------|
| 🤖 **AI Support (Mehjabeen)** | A compassionate, crisis-aware AI companion that provides immediate emotional support and crisis intervention. |
| 🤝 **Anonymous Peer Chat** | Connects users anonymously for mutual peer support, allowing them to share experiences in a safe environment. |
| 🛡️ **Personalized Safety Plan** | A guided tool to help users create a step-by-step crisis plan for when they are in distress. |
| 📓 **Mood Tracker & Journal** | Private daily mood logging with trends to help users monitor their emotional well-being. |
| 📞 **Emergency Hub** | Quick access to verified international and local (Bangladesh) crisis helplines. |
| 🗨️ **Community Forum** | An anonymous space for community discussion, support threads, and encouragement. |

---

## 🛠️ Technology Stack

- **Backend**: PHP (7.4+)
- **Database**: MySQL / MariaDB
- **Frontend**: HTML5, Vanilla CSS3, Javascript (ES6)
- **AI Integration**: LongCat API (OpenAI-compatible)
- **Security**: Password hashing (Bcrypt), Prepared Statements (PDO), XSS prevention.

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
2. Navigate to the project folder and copy `config.php.example` to `config.php`:
   ```bash
   cp config.php.example config.php
   ```
3. Edit `config.php` and provide your **LongCat API Key**.
4. Open your browser and go to `http://localhost/hopeline`.

---

## 📋 Security & API Notes

- **API Keys**: Never commit your `config.php` file. It is added to `.gitignore` to keep your credentials private.
- **Privacy**: User identities are kept anonymous where possible to encourage open communication.

---

## 🌍 Crisis Resources (Pre-loaded)

- 🇧🇩 **Bangladesh Emergency**: 999
- 🇧🇩 **Kaan Pete Roi**: 01779-554391
- 🇧🇩 **NIMH Helpline**: 16789
- 🌐 **International**: [Befrienders Worldwide](https://www.befrienders.org)

---

Built with 💚 for mental health awareness.

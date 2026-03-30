<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HopeLine — A Sanctuary for Your Mind</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;1,400&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #7c9885;
            --primary-deep: #4d6b55;
            --accent: #a8c4b0;
            --bg: #faf8f4;
            --card-bg: #ffffff;
            --text-main: #2d2d2d;
            --text-soft: #6b6b6b;
            --white: #ffffff;
            --radius-lg: 32px;
            --radius-md: 20px;
            --shadow-soft: 0 10px 40px rgba(124, 152, 133, 0.12);
            --transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* ── Background Texture ── */
        .bg-elements {
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            overflow: hidden;
        }

        .blob {
            position: absolute;
            background: radial-gradient(circle, rgba(124, 152, 133, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(60px);
            animation: float 20s infinite alternate ease-in-out;
        }

        .blob-1 { width: 600px; height: 600px; top: -200px; left: -100px; }
        .blob-2 { width: 500px; height: 500px; bottom: -100px; right: -50px; animation-delay: -5s; }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(50px, 50px) scale(1.1); }
        }

        /* ── Navigation ── */
        nav {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 24px 8%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
            background: rgba(250, 248, 244, 0.8);
            backdrop-filter: blur(10px);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-family: 'Lora', serif;
            font-size: 24px;
            font-weight: 600;
            color: var(--primary-deep);
            text-decoration: none;
        }

        .nav-btn {
            padding: 12px 28px;
            background: var(--primary);
            color: var(--white);
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(124, 152, 133, 0.2);
        }

        .nav-btn:hover {
            background: var(--primary-deep);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(77, 107, 85, 0.3);
        }

        /* ── Hero Section ── */
        .hero {
            padding: 180px 8% 100px;
            text-align: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .hero h1 {
            font-family: 'Lora', serif;
            font-size: clamp(40px, 6vw, 72px);
            line-height: 1.1;
            color: var(--primary-deep);
            margin-bottom: 24px;
            opacity: 0;
            animation: fadeUp 0.8s forwards;
        }

        .hero p {
            font-size: clamp(18px, 2vw, 22px);
            color: var(--text-soft);
            max-width: 700px;
            margin: 0 auto 40px;
            opacity: 0;
            animation: fadeUp 0.8s 0.2s forwards;
        }

        /* ── Flashcards Section ── */
        .features {
            padding: 80px 8%;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 32px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .card {
            background: var(--card-bg);
            padding: 48px 40px;
            border-radius: var(--radius-lg);
            border: 1px solid rgba(124, 152, 133, 0.1);
            transition: var(--transition);
            cursor: default;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            gap: 20px;
            box-shadow: var(--shadow-soft);
        }

        .card:hover {
            transform: translateY(-12px);
            border-color: var(--primary);
            box-shadow: 0 20px 60px rgba(124, 152, 133, 0.2);
        }

        .card-icon {
            font-size: 48px;
            margin-bottom: 8px;
        }

        .card h3 {
            font-family: 'Lora', serif;
            font-size: 26px;
            color: var(--primary-deep);
        }

        .card p {
            font-size: 16px;
            color: var(--text-soft);
        }

        .card-tag {
            align-self: flex-start;
            padding: 6px 14px;
            background: var(--bg);
            border-radius: 50px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--primary);
        }

        /* ── Call to Action ── */
        .cta-section {
            padding: 120px 8%;
            text-align: center;
            background: linear-gradient(180deg, transparent 0%, rgba(124, 152, 133, 0.05) 100%);
        }

        .cta-box {
            max-width: 900px;
            margin: 0 auto;
            padding: 80px 40px;
            background: var(--primary-deep);
            color: var(--white);
            border-radius: var(--radius-lg);
            position: relative;
            overflow: hidden;
        }

        .cta-box h2 {
            font-family: 'Lora', serif;
            font-size: clamp(32px, 4vw, 48px);
            margin-bottom: 24px;
            position: relative;
            z-index: 2;
        }

        .cta-box p {
            font-size: 18px;
            margin-bottom: 40px;
            opacity: 0.9;
            position: relative;
            z-index: 2;
        }

        .btn-large {
            padding: 20px 48px;
            background: var(--white);
            color: var(--primary-deep);
            display: inline-block;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 18px;
            transition: var(--transition);
            position: relative;
            z-index: 2;
        }

        .btn-large:hover {
            background: var(--accent);
            color: var(--primary-deep);
            transform: scale(1.05);
        }

        .cta-bg {
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            z-index: 1;
        }

        /* ── Animations ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        footer {
            padding: 60px 8%;
            text-align: center;
            border-top: 1px solid rgba(124, 152, 133, 0.1);
            color: var(--text-soft);
            font-size: 14px;
        }

        @media (max-width: 768px) {
            nav { padding: 20px 5%; }
            .hero { padding-top: 140px; }
            .features { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <div class="bg-elements">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
    </div>

    <nav>
        <a href="#" class="logo">
            <span>🌿</span> HopeLine
        </a>
        <a href="login.php" class="nav-btn">Get Started</a>
    </nav>

    <main>
        <section class="hero">
            <h1>You are not alone.<br>We are here to listen.</h1>
            <p>HopeLine is a sanctuary for your mental well-being. Connect with empathetic AI, talk anonymously with peers, or build your own safety plan in a safe, judgment-free space.</p>
        </section>

        <section class="features">
            <!-- Flashcard 1 -->
            <div class="card">
                <div class="card-tag">Anonymous</div>
                <div class="card-icon">🤖</div>
                <h3>Mehjabeen AI</h3>
                <p>Your compassionate AI companion, trained to listen and support 24/7. Whether you need to vent or find calm, Mehjabeen is always here.</p>
            </div>

            <!-- Flashcard 2 -->
            <div class="card">
                <div class="card-tag">Peer Support</div>
                <div class="card-icon">🤝</div>
                <h3>Peer-to-Peer Chat</h3>
                <p>Connect anonymously with others who understand what you're going through. Shared experiences create lasting support.</p>
            </div>

            <!-- Flashcard 3 -->
            <div class="card">
                <div class="card-tag">Safe Space</div>
                <div class="card-icon">🗨️</div>
                <h3>Community Forum</h3>
                <p>Share your story or find inspiration in others'. An anonymous forum where every voice is heard and every struggle is valid.</p>
            </div>

            <!-- Flashcard 4 -->
            <div class="card">
                <div class="card-tag">Prevention</div>
                <div class="card-icon">🛡️</div>
                <h3>Personal Safety Plan</h3>
                <p>Create a step-by-step roadmap to navigate through your darkest moments. A tool designed to keep you safe when you need it most.</p>
            </div>

            <!-- Flashcard 5 -->
            <div class="card">
                <div class="card-tag">Journaling</div>
                <div class="card-icon">📓</div>
                <h3>Mood Tracker</h3>
                <p>Observe your emotional patterns over time. Journaling helps clarify thoughts and track your progress toward healing.</p>
            </div>

            <!-- Flashcard 6 -->
            <div class="card">
                <div class="card-tag">Immediate Help</div>
                <div class="card-icon">📞</div>
                <h3>Resource Hub</h3>
                <p>Instant access to local and international crisis helplines. Professional help is always just one tap away.</p>
            </div>
        </section>

        <section class="cta-section">
            <div class="cta-box">
                <div class="cta-bg"></div>
                <h2>Ready to find your peace?</h2>
                <p>Join a community committed to mental health awareness and mutual support. Registration is simple and your privacy is our priority.</p>
                <a href="login.php" class="btn-large">Get Started with HopeLine</a>
            </div>
        </section>
    </main>

    <footer>
        <p>© 2026 HopeLine. Built with 💚 for your well-being. If you are in immediate danger, please contact your local emergency services.</p>
    </footer>

</body>
</html>

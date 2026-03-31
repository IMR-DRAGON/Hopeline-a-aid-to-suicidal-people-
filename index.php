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
            /* Light Theme */
            --primary: #7c9885;
            --primary-deep: #4d6b55;
            --accent: #a8c4b0;
            --bg: #faf8f4;
            --card-bg: #ffffff;
            --text-main: #2d2d2d;
            --text-soft: #6b6b6b;
            --white: #ffffff;
            --glass: rgba(255, 255, 255, 0.8);
            --cta-bg: #4d6b55; /* primary-deep */
            --btn-text: #4d6b55;
            --radius-lg: 32px;
            --radius-md: 20px;
            --shadow-soft: 0 10px 40px rgba(124, 152, 133, 0.12);
            --transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
        }

        [data-theme="dark"] {
            --primary: #8fb19b;
            --primary-deep: #b4ccbc;
            --accent: #4d6b55;
            --bg: #0a0c0a;
            --card-bg: #141714;
            --text-main: #e0e0e0;
            --text-soft: #b0b0b0;
            --white: #ffffff;
            --glass: rgba(15, 20, 15, 0.9);
            --cta-bg: #121813; /* Very deep dark for CTA */
            --btn-text: #121813;
            --shadow-soft: 0 15px 50px rgba(0, 0, 0, 0.6);
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
            transition: background-color 0.4s ease;
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
            background: radial-gradient(circle, var(--primary) 0%, transparent 70%);
            opacity: 0.15;
            border-radius: 50%;
            filter: blur(80px);
            animation: float 20s infinite alternate ease-in-out;
        }

        .blob-1 { width: 800px; height: 800px; top: -300px; left: -200px; }
        .blob-2 { width: 600px; height: 600px; bottom: -150px; right: -100px; animation-delay: -5s; }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(100px, 100px) scale(1.2); }
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
            z-index: 1000;
            background: var(--glass);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(124, 152, 133, 0.1);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-family: 'Lora', serif;
            font-size: 26px;
            font-weight: 600;
            color: var(--primary-deep);
            text-decoration: none;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .theme-toggle {
            background: none;
            border: 1.5px solid var(--primary);
            color: var(--primary);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: var(--transition);
        }

        .theme-toggle:hover {
            background: var(--primary);
            color: white;
        }

        .nav-btn {
            padding: 12px 32px;
            background: var(--primary);
            color: white;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(124, 152, 133, 0.2);
        }

        .nav-btn:hover {
            background: var(--primary-deep);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(77, 107, 85, 0.4);
        }

        /* ── Hero Section ── */
        .hero {
            padding: 200px 8% 100px;
            text-align: center;
            max-width: 1000px;
            margin: 0 auto;
        }

        .hero h1 {
            font-family: 'Lora', serif;
            font-size: clamp(40px, 7vw, 84px);
            line-height: 1.05;
            color: var(--primary-deep);
            margin-bottom: 28px;
            opacity: 0;
            animation: fadeUp 1s forwards;
            font-weight: 600;
        }

        .hero p {
            font-size: clamp(18px, 2.2vw, 24px);
            color: var(--text-soft);
            max-width: 750px;
            margin: 0 auto 40px;
            opacity: 0;
            animation: fadeUp 1s 0.2s forwards;
            font-weight: 400;
        }

        /* ── MAGNIFIED FLASHCARDS ── */
        .card-container {
            padding: 60px 8% 120px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(360px, 1fr));
            gap: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .card {
            background: var(--card-bg);
            padding: 50px 40px;
            border-radius: var(--radius-lg);
            border: 1px solid rgba(124, 152, 133, 0.15);
            transition: var(--transition);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            min-height: 380px;
            box-shadow: var(--shadow-soft);
            z-index: 1;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, var(--primary) 0%, transparent 100%);
            opacity: 0;
            transition: var(--transition);
            z-index: -1;
        }

        .card:hover {
            transform: scale(1.05) translateY(-15px);
            z-index: 50;
            border-color: var(--primary);
            box-shadow: 0 30px 70px rgba(0,0,0,0.2);
        }

        .card:hover::before {
            opacity: 0.05;
        }

        .card-icon {
            font-size: 56px;
            margin-bottom: 24px;
            transition: transform 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .card:hover .card-icon {
            transform: scale(1.2) rotate(5deg);
        }

        .card h3 {
            font-family: 'Lora', serif;
            font-size: 28px;
            color: var(--primary-deep);
            margin-bottom: 15px;
        }

        .card-tag {
            align-self: flex-start;
            padding: 6px 16px;
            background: rgba(124, 152, 133, 0.1);
            border-radius: 50px;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--primary);
            margin-bottom: 12px;
        }

        .card p {
            font-size: 16px;
            color: var(--text-soft);
            line-height: 1.6;
        }

        .card-details {
            margin-top: auto;
            opacity: 0;
            transform: translateY(20px);
            transition: var(--transition);
            padding-top: 20px;
            color: var(--primary-deep);
            font-weight: 600;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card:hover .card-details {
            opacity: 1;
            transform: translateY(0);
        }

        /* ── Call to Action ── */
        .cta-section {
            padding: 100px 8%;
            text-align: center;
        }

        .cta-box {
            max-width: 1000px;
            margin: 0 auto;
            padding: 100px 50px;
            background: var(--cta-bg);
            color: white;
            border-radius: 40px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 40px 100px rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.05);
        }

        .cta-box h2 {
            font-family: 'Lora', serif;
            font-size: clamp(36px, 5vw, 60px);
            margin-bottom: 24px;
            position: relative;
            z-index: 2;
        }

        .cta-box p {
            font-size: clamp(16px, 2vw, 20px);
            margin-bottom: 60px; /* Moves button further down */
            opacity: 0.9;
            position: relative;
            z-index: 2;
        }

        .btn-glow {
            padding: 22px 56px;
            background: white;
            color: var(--btn-text);
            display: inline-block;
            text-decoration: none;
            border-radius: 60px;
            font-weight: 800;
            font-size: 18px;
            transition: var(--transition);
            position: relative;
            z-index: 2;
            box-shadow: 0 10px 40px rgba(255,255,255,0.1);
        }

        .btn-glow:hover {
            transform: scale(1.05) translateY(-3px);
            box-shadow: 0 20px 50px rgba(255,255,255,0.25);
            background: #c9e4ca; /* Vibrant Light Green */
            color: #121813;
        }

        /* ── Animations ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        footer {
            padding: 80px 8%;
            text-align: center;
            color: var(--text-soft);
            font-size: 15px;
            border-top: 1px solid rgba(124, 152, 133, 0.1);
        }

        @media (max-width: 768px) {
            nav { padding: 18px 5%; }
            .hero { padding-top: 160px; }
            .card-container { grid-template-columns: 1fr; }
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
        <div class="nav-actions">
            <button class="theme-toggle" id="theme-btn" title="Toggle Theme">🌙</button>
            <a href="login.php" class="nav-btn">Get Started</a>
        </div>
    </nav>

    <main>
        <section class="hero">
            <h1>Hope happens here.<br>You are not alone.</h1>
            <p>HopeLine is a kind, anonymous haven for your mental well-being. Share your story, find immediate calm, or connect with peers who truly understand.</p>
        </section>

        <section class="card-container">
            <!-- Flashcard 1 -->
            <div class="card" onclick="location.href='login.php'">
                <div class="card-tag">Companion</div>
                <div class="card-icon">🤖</div>
                <h3>Mehjabeen AI</h3>
                <p>A compassionate AI companion designed to listen with empathy. Whether you need to talk at 3 AM or find immediate support, Mehjabeen is always here.</p>
                <div class="card-details">Learn more about AI support ➔</div>
            </div>

            <!-- Flashcard 2 -->
            <div class="card" onclick="location.href='login.php'">
                <div class="card-tag">Community</div>
                <div class="card-icon">🤝</div>
                <h3>Peer-to-Peer Chat</h3>
                <p>Connect 100% anonymously with others. Share your struggles, offer kindness, and realize that you're never navigating this world alone.</p>
                <div class="card-details">Connect with a peer ➔</div>
            </div>

            <!-- Flashcard 3 -->
            <div class="card" onclick="location.href='login.php'">
                <div class="card-tag">Relief</div>
                <div class="card-icon">🌬️</div>
                <h3>Pause & Breathe</h3>
                <p>An interactive grounding tool using the 4-7-8 technique. Sync your breathing with calm animations to reduce anxiety instantly.</p>
                <div class="card-details">Try a 4-7-8 session ➔</div>
            </div>

            <!-- Flashcard 4 -->
            <div class="card" onclick="location.href='login.php'">
                <div class="card-tag">Recognition</div>
                <div class="card-icon">🏅</div>
                <h3>Support Badges</h3>
                <p>Be recognized for your kindness. Earn badges from "Fresh Voice" to "Bright Star" as you help others find hope in the forum.</p>
                <div class="card-details">Explore badge tiers ➔</div>
            </div>

            <!-- Flashcard 5 -->
            <div class="card" onclick="location.href='login.php'">
                <div class="card-tag">Analysis</div>
                <div class="card-icon">📈</div>
                <h3>Emotional Insights</h3>
                <p>AI-powered reflections on your mood journal. Mehjabeen helps you notice patterns in your emotional well-being over time.</p>
                <div class="card-details">View your patterns ➔</div>
            </div>

            <!-- Flashcard 6 -->
            <div class="card" onclick="location.href='login.php'">
                <div class="card-tag">Planning</div>
                <div class="card-icon">🛡️</div>
                <h3>Personal Safety Plan</h3>
                <p>Build a guided, step-by-step roadmap for difficult moments. A private tool to keep your coping strategies and support contacts focused.</p>
                <div class="card-details">Create your plan ➔</div>
            </div>
        </section>

        <section class="cta-section">
            <div class="cta-box">
                <h2>Take the first step toward peace.</h2>
                <p>Your journey of healing belongs only to you. We're just here to make the path a little brighter.</p>
                <a href="login.php" class="btn-glow">Join HopeLine Today</a>
            </div>
        </section>
    </main>

    <footer>
        <p>© 2026 HopeLine. Built with 💚 for mental health awareness. If you are in immediate danger, please contact your local emergency services.</p>
    </footer>

    <script>
        const themeBtn = document.getElementById('theme-btn');
        const body = document.body;

        // Check for saved theme
        const savedTheme = localStorage.getItem('hopeline_theme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedTheme);
        themeBtn.textContent = savedTheme === 'dark' ? '☀️' : '🌙';

        themeBtn.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('hopeline_theme', newTheme);
            themeBtn.textContent = newTheme === 'dark' ? '☀️' : '🌙';
        });

        // Add intersection observer for reveal effect
        const observerOptions = {
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(40px)';
            card.style.transition = 'all 0.8s cubic-bezier(0.23, 1, 0.32, 1)';
            observer.observe(card);
        });
    </script>

</body>
</html>

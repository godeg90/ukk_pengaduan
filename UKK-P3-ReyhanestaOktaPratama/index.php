<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduSpeak | Sistem Pengaduan Sekolah</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #f97316;
            --primary-dark: #ea580c;
            --secondary: #18181b;
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --bg-light: #fffaf5;
            --nav-height: 80px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-main);
            background-color: white;
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* --- NAVIGATION --- */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 8%;
            height: var(--nav-height);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid #f3f4f6;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
            text-decoration: none;
            letter-spacing: -0.5px;
            z-index: 1001;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-main);
            font-weight: 500;
            transition: 0.3s;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .btn-nav {
            background: var(--secondary);
            color: white !important;
            padding: 10px 24px;
            border-radius: 10px;
            font-weight: 600;
        }

        /* Mobile Menu Toggle */
        .menu-toggle {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
            z-index: 1001;
        }

        /* --- HERO SECTION --- */
        .hero {
            padding: calc(var(--nav-height) + 60px) 8% 80px;
            background: linear-gradient(135deg, #fff7ed 0%, #ffffff 100%);
            display: flex;
            align-items: center;
            min-height: 100vh;
            gap: 40px;
        }

        .hero-content {
            flex: 1.2;
        }

        .hero-content span {
            background: #ffedd5;
            color: var(--primary-dark);
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            display: inline-block;
            margin-bottom: 20px;
        }

        .hero-content h1 {
            font-size: clamp(2.5rem, 5vw, 3.5rem);
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 24px;
            color: var(--secondary);
        }

        .hero-content p {
            font-size: clamp(1rem, 2vw, 1.15rem);
            color: var(--text-muted);
            margin-bottom: 40px;
            max-width: 540px;
        }

        .cta-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .btn {
            padding: 16px 32px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            transition: 0.3s;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 10px 20px rgba(249, 115, 22, 0.2);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
        }

        .hero-image {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: clamp(8rem, 15vw, 12rem);
            filter: drop-shadow(20px 20px 50px rgba(0,0,0,0.1));
            user-select: none;
        }

        /* --- STEPS SECTION --- */
        .steps {
            padding: 100px 8%;
            text-align: center;
            background-color: white;
        }

        .section-title {
            margin-bottom: 60px;
        }

        .section-title h2 {
            font-size: clamp(1.75rem, 4vw, 2.5rem);
            font-weight: 800;
            margin-bottom: 10px;
        }

        .grid-steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }

        .step-card {
            padding: 40px;
            background: white;
            border-radius: 24px;
            border: 1px solid #f3f4f6;
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .step-card:hover {
            border-color: var(--primary);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            transform: translateY(-12px);
        }

        .icon-box {
            width: 70px;
            height: 70px;
            background: #fff7ed;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 25px;
        }

        .step-card h3 {
            margin-bottom: 15px;
            font-size: 1.25rem;
        }

        /* --- FOOTER --- */
        footer {
            background: var(--secondary);
            color: #9ca3af;
            padding: 80px 8% 40px;
            text-align: center;
        }

        .footer-logo {
            color: white;
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 20px;
            display: inline-block;
            text-decoration: none;
        }

        .footer-logo span { color: var(--primary); }

        .copyright {
            margin-top: 60px;
            padding-top: 30px;
            border-top: 1px solid #3f3f46;
            font-size: 0.9rem;
        }

        /* --- RESPONSIVE BREAKPOINTS --- */
        
        /* Tablet */
        @media (max-width: 1024px) {
            .hero { gap: 20px; }
            .hero-image { font-size: 10rem; }
        }

        /* Mobile Large & Small */
        @media (max-width: 768px) {
            nav { padding: 0 5%; }
            
            .menu-toggle { display: block; }

            .nav-links {
                position: fixed;
                top: 0;
                right: -100%;
                width: 80%;
                height: 100vh;
                background: white;
                flex-direction: column;
                justify-content: center;
                transition: 0.4s;
                box-shadow: -10px 0 30px rgba(0,0,0,0.1);
            }

            .nav-links.active { right: 0; }

            .nav-links a {
                font-size: 1.2rem;
                margin: 0;
            }

            .hero {
                flex-direction: column-reverse;
                text-align: center;
                padding-top: 120px;
            }

            .hero-content {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .hero-content h1 { margin-top: 10px; }

            .hero-image {
                margin-bottom: 20px;
                font-size: 8rem;
            }

            .cta-group {
                justify-content: center;
                width: 100%;
            }

            .btn { width: 100%; max-width: 300px; }

            .grid-steps {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <nav>
        <a href="#" class="logo">EduSpeak<span>.</span></a>
        
        <div class="menu-toggle" id="mobile-menu">
            <i class="fas fa-bars"></i>
        </div>

        <div class="nav-links" id="nav-list">
            <a href="#prosedur">Prosedur</a>
            <a href="#tentang">Tentang</a>
            <a href="login.php" class="btn-nav">Masuk</a>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <span>Suara Siswa, Masa Depan Sekolah</span>
            <h1>Berani Lapor untuk Perubahan.</h1>
            <p>Layanan aspirasi dan pengaduan daring bagi seluruh siswa. Sampaikan keluhan, saran, atau laporan fasilitas secara aman dan transparan.</p>
            <div class="cta-group">
                <a href="login.php" class="btn btn-primary">Mulai Melapor</a>
                <a href="#prosedur" class="btn" style="border: 2px solid #e5e7eb;">Lihat Alur</a>
            </div>
        </div>
        <div class="hero-image">
            🏫
        </div>
    </section>

    <section class="steps" id="prosedur">
        <div class="section-title">
            <h2>Alur Pengaduan</h2>
            <p style="color: var(--text-muted)">Hanya butuh 3 langkah mudah untuk suara Anda didengar</p>
        </div>
        <div class="grid-steps">
            <div class="step-card">
                <div class="icon-box">✍️</div>
                <h3>Tulis Laporan</h3>
                <p style="color: var(--text-muted); font-size: 0.95rem;">Laporkan keluhan atau aspirasi Anda dengan menyertakan deskripsi dan data yang valid.</p>
            </div>
            <div class="step-card">
                <div class="icon-box">🔍</div>
                <h3>Verifikasi</h3>
                <p style="color: var(--text-muted); font-size: 0.95rem;">Laporan Anda akan segera ditinjau dan divalidasi oleh tim administrator sekolah.</p>
            </div>
            <div class="step-card">
                <div class="icon-box">✅</div>
                <h3>Tindak Lanjut</h3>
                <p style="color: var(--text-muted); font-size: 0.95rem;">Pantau status laporan Anda secara real-time hingga selesai ditangani petugas terkait.</p>
            </div>
        </div>
    </section>

    <footer>
        <a href="#" class="footer-logo">EduSpeak<span>.</span></a>
        <p>Mewujudkan lingkungan sekolah yang transparan, aman, dan responsif melalui aspirasi digital.</p>
        <div class="copyright">
            &copy; 2026 EduSpeak System. Hak Cipta Dilindungi.
        </div>
    </footer>

    <script>
        // Script untuk toggle menu mobile
        const menuToggle = document.getElementById('mobile-menu');
        const navList = document.getElementById('nav-list');

        menuToggle.addEventListener('click', () => {
            navList.classList.toggle('active');
            // Ganti icon saat menu dibuka
            const icon = menuToggle.querySelector('i');
            if (navList.classList.contains('active')) {
                icon.classList.replace('fa-bars', 'fa-times');
            } else {
                icon.classList.replace('fa-times', 'fa-bars');
            }
        });

        // Menutup menu saat link diklik (untuk mobile)
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                navList.classList.remove('active');
                menuToggle.querySelector('i').classList.replace('fa-times', 'fa-bars');
            });
        });
    </script>

</body>
</html>
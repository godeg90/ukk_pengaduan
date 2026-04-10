<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register | Sistem Pengaduan Sekolah</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #f97316;
            --primary-dark: #ea580c;
            --bg-gradient: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
            --text-main: #431407;
            --text-muted: #71717a;
            --card-bg: rgba(255, 255, 255, 0.8);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-gradient);
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            color: var(--text-main);
        }

        /* Dekorasi Background Bulatan */
        body::before, body::after {
            content: "";
            position: absolute;
            z-index: -1;
            border-radius: 50%;
            filter: blur(80px);
        }
        body::before { width: 300px; height: 300px; background: #fed7aa; top: 10%; left: 10%; }
        body::after { width: 250px; height: 250px; background: #fdba74; bottom: 10%; right: 10%; }

        .wrapper {
            width: 100%;
            max-width: 900px;
            padding: 20px;
            z-index: 1;
        }

        .header-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .header-section h1 {
            font-weight: 800;
            font-size: 2.5rem;
            margin: 0;
            background: linear-gradient(to right, #ea580c, #f97316);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -1px;
        }

        /* Tombol Kembali Styling */
        .back-nav {
            margin-bottom: 20px;
            display: flex;
            justify-content: flex-start;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            color: var(--text-main);
            font-weight: 600;
            font-size: 0.9rem;
            background: rgba(255, 255, 255, 0.6);
            padding: 8px 16px;
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: #fff;
            transform: translateX(-5px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 25px;
            justify-content: center;
        }

        .box {
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 40px;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .box:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px -12px rgba(234, 88, 12, 0.15);
            border-color: rgba(249, 115, 22, 0.3);
        }

        .box h3 {
            font-size: 1.5rem;
            margin-top: 0;
            margin-bottom: 8px;
            color: #1f2937;
            font-weight: 700;
        }

        .subtitle {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-bottom: 25px;
        }

        /* Styling Input */
        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        input {
            width: 100%;
            padding: 14px 16px;
            box-sizing: border-box;
            border-radius: 12px;
            border: 1.5px solid #e5e7eb;
            background: rgba(255, 255, 255, 0.5);
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        input:focus {
            border-color: var(--primary);
            outline: none;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.1);
        }

        button {
            width: 100%;
            padding: 14px;
            margin-top: 10px;
            background: #18181b; 
            color: white;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        button:hover {
            background: var(--primary-dark);
            transform: scale(1.01);
            box-shadow: 0 10px 15px -3px rgba(234, 88, 12, 0.3);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .box, .back-nav {
            animation: fadeIn 0.8s ease forwards;
        }

        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
            }
            .header-section h1 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="back-nav">
        <a href="index.php" class="btn-back">
            <span style="margin-right: 8px;">←</span> Kembali ke Beranda
        </a>
    </div>

    <div class="header-section">
        <h1>Sistem Pengaduan Sekolah</h1>
        <p style="color: #9a3412; font-weight: 500;">Suarakan aspirasimu untuk sekolah yang lebih baik</p>
    </div>

    <div class="container">
        <div class="box" style="animation-delay: 0.1s;">
            <h3>Selamat Datang</h3>
            <div class="subtitle">Silakan masuk ke akun Anda</div>

            <form action="cek_login.php" method="POST">
                <div class="input-group">
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <button type="submit">Masuk Sekarang</button>
            </form>
        </div>

        <div class="box" style="animation-delay: 0.3s;">
            <h3>Daftar Baru</h3>
            <div class="subtitle">Lengkapi data untuk membuat akun</div>

            <form action="register.php" method="POST">
                <div class="input-group">
                    <input type="text" name="nama" placeholder="Nama Lengkap" required>
                </div>
                <div class="input-group">
                    <input type="text" name="username" placeholder="Buat Username" required>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Buat Password" required>
                </div>

                <input type="hidden" name="role" value="siswa">

                <button type="submit" style="background: var(--primary);">Buat Akun</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
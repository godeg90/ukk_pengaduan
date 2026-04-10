<?php 
session_start();
include 'koneksi.php';

// Keamanan: Cek Login & Role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// LOGIKA: TAMBAH USER
if(isset($_POST['tambah'])){
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);

    mysqli_query($conn, "INSERT INTO users (nama, username, password, role) 
    VALUES ('$nama','$username','$password','siswa')");
    header("Location: users.php?pesan=berhasil");
    exit;
}

// LOGIKA: HAPUS USER
if(isset($_GET['hapus'])){
    $id = intval($_GET['hapus']);
    // Hapus relasi pengaduan (Foreign Key manual jika tidak ada ON DELETE CASCADE)
    mysqli_query($conn, "DELETE FROM pengaduan WHERE user_id=$id");
    // Hapus user
    mysqli_query($conn, "DELETE FROM users WHERE id=$id");
    header("Location: users.php?pesan=terhapus");
    exit;
}

// AMBIL DATA SISWA
$data = mysqli_query($conn, "SELECT * FROM users WHERE role='siswa' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User | SekolahKu Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    :root {
        --primary: #ff7043;
        --primary-dark: #e65100;
        --secondary: #fb8c00;
        --success: #43a047;
        --danger: #e53935;
        --warning: #fbc02d;
        --bg: #fff3e0;
        --sidebar-width: 260px;
    }

    * { box-sizing: border-box; scroll-behavior: smooth; }

    body {
        margin: 0;
        font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
        background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
        background-attachment: fixed;
        min-height: 100vh;
    }

    /* SIDEBAR (Identik dengan Admin Dashboard) */
    .sidebar {
        width: var(--sidebar-width);
        height: 100vh;
        position: fixed;
        background: var(--primary-dark);
        padding: 30px 20px;
        color: white;
        display: flex;
        flex-direction: column;
        box-shadow: 4px 0 15px rgba(0,0,0,0.1);
        z-index: 1000;
        transition: 0.3s;
    }

    .sidebar-header { margin-bottom: 40px; text-align: center; }
    .sidebar-header h2 { margin: 0; font-size: 24px; color: #fff; letter-spacing: 1px; }
    .sidebar-header p { margin: 5px 0 0; opacity: 0.8; font-size: 14px; }

    .sidebar a {
        display: flex;
        align-items: center;
        color: white;
        padding: 14px 18px;
        margin: 5px 0;
        text-decoration: none;
        border-radius: 12px;
        transition: all 0.3s ease;
        font-weight: 600;
        gap: 15px;
    }

    .sidebar a:hover { background: rgba(255,255,255,0.2); transform: translateX(5px); }
    .sidebar a.active { background: white; color: var(--primary-dark); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .sidebar a.logout { margin-top: auto; background: rgba(211, 47, 47, 0.2); border: 1px solid rgba(255,255,255,0.1); }

    /* MAIN CONTENT */
    .main { 
        margin-left: var(--sidebar-width); 
        padding: 40px; 
        transition: 0.3s;
    }

    .welcome-text h2 { color: var(--primary-dark); margin: 0; font-size: 28px; }
    .welcome-text p { color: #8d6e63; margin: 5px 0 30px; }

    /* CARD MODERN */
    .card { 
        background: rgba(255, 255, 255, 0.95); 
        backdrop-filter: blur(10px); 
        padding: 30px; 
        border-radius: 24px; 
        margin-bottom: 30px; 
        border: 1px solid white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    /* FORM TAMBAH (Modernized) */
    .form-tambah-container {
        background: #fdf2f0;
        padding: 25px;
        border-radius: 18px;
        margin-bottom: 30px;
        border: 2px dashed var(--primary);
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .form-grid input {
        padding: 12px 15px;
        border-radius: 10px;
        border: 2px solid #ffe0b2;
        outline: none;
        font-size: 14px;
        transition: 0.3s;
    }

    .form-grid input:focus { border-color: var(--primary); background: white; }

    .btn-save {
        background: var(--primary-dark);
        color: white; border: none;
        border-radius: 10px; font-weight: bold;
        cursor: pointer; transition: 0.3s;
        display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-save:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(230, 81, 0, 0.3); }

    /* TABLE (Identik dengan Admin Dashboard) */
    .table-container { overflow-x: auto; margin-top: 10px; }
    table { width: 100%; border-collapse: separate; border-spacing: 0 10px; min-width: 600px; }
    th { padding: 15px; text-align: left; color: #8d6e63; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; }
    td { padding: 15px; background: white; vertical-align: middle; border-top: 1px solid #fcfcfc; }
    tr td:first-child { border-radius: 15px 0 0 15px; }
    tr td:last-child { border-radius: 0 15px 15px 0; }

    .username-badge { background: #f0f0f0; padding: 4px 10px; border-radius: 6px; font-family: monospace; color: #555; }

    .btn-action {
        padding: 8px 12px; border-radius: 8px; border: none; color: white;
        font-weight: bold; cursor: pointer; font-size: 12px; transition: 0.2s;
        text-decoration: none; display: inline-flex; align-items: center; gap: 5px;
    }
    .btn-edit { background: #29b6f6; }
    .btn-hapus { background: var(--danger); }
    .btn-action:hover { opacity: 0.9; transform: scale(1.05); }

    /* MOBILE NAV */
    .menu-toggle { display: none; position: fixed; top: 20px; right: 20px; background: var(--primary-dark); color: white; padding: 10px; border-radius: 8px; z-index: 1001; cursor: pointer; }

    /* RESPONSIVE */
    @media (max-width: 992px) {
        :root { --sidebar-width: 80px; }
        .sidebar-header p, .sidebar a span { display: none; }
        .sidebar-header h2 { font-size: 18px; }
        .sidebar { padding: 30px 10px; }
        .sidebar a { justify-content: center; padding: 15px; }
    }

    @media (max-width: 768px) {
        .sidebar { left: -100%; width: 240px; }
        .sidebar.active { left: 0; }
        .sidebar-header p, .sidebar a span { display: block; }
        .main { margin-left: 0; padding: 20px; padding-top: 80px; }
        .menu-toggle { display: block; }
    }
    </style>
</head>
<body>

<div class="menu-toggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
</div>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h2>SekolahKu</h2>
        <p>Admin Panel</p>
    </div>
    <a href="admin.php"><i class="fas fa-chart-pie"></i> <span>Dashboard</span></a>
    <a href="users.php" class="active"><i class="fas fa-users"></i> <span>Kelola User</span></a>
    <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
</div>

<div class="main">
    <div class="welcome-text">
        <h2>Kelola Data Siswa 👥</h2>
        <p>Manajemen akun akses siswa aplikasi pengaduan.</p>
    </div>

    <div class="card">
        <div class="form-tambah-container">
            <h4 style="margin: 0 0 15px 0; color: var(--primary-dark);">
                <i class="fas fa-user-plus"></i> Tambah Akun Siswa Baru
            </h4>
            <form method="POST" class="form-grid">
                <input type="text" name="nama" placeholder="Nama Lengkap Siswa" required>
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="tambah" class="btn-save">
                    <i class="fas fa-save"></i> Simpan User
                </button>
            </form>
        </div>

        <h4 style="color: #8d6e63; margin-bottom: 10px;"><i class="fas fa-list"></i> Daftar Siswa Terdaftar</h4>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>Nama Siswa</th>
                        <th>Username</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while($d = mysqli_fetch_assoc($data)): 
                    ?>
                    <tr>
                        <td align="center"><strong><?= $no++; ?></strong></td>
                        <td style="font-weight: 600; color: #333;"><?= htmlspecialchars($d['nama']); ?></td>
                        <td><span class="username-badge"><?= htmlspecialchars($d['username']); ?></span></td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="edit_user.php?id=<?= $d['id']; ?>" class="btn-action btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="?hapus=<?= $d['id']; ?>" class="btn-action btn-hapus" title="Hapus" onclick="return confirm('Menghapus user ini akan menghapus semua riwayat laporannya. Lanjutkan?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    
                    <?php if(mysqli_num_rows($data) == 0): ?>
                    <tr>
                        <td colspan="4" align="center" style="padding: 50px; color: #999;">Belum ada data siswa terdaftar.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('active');
}

// Menutup sidebar otomatis jika layar diresize ke desktop
window.addEventListener('resize', function() {
    if (window.innerWidth > 768) {
        document.getElementById('sidebar').classList.remove('active');
    }
});
</script>

</body>
</html>
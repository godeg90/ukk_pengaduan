<?php 
session_start();
include 'koneksi.php';

// CEK LOGIN ADMIN
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// DASHBOARD COUNT
$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pengaduan"));
$menunggu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pengaduan WHERE status='menunggu'"));
$diproses = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pengaduan WHERE status='diproses'"));
$selesai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pengaduan WHERE status='selesai'"));

// FILTER LOGIC
$kategori = $_GET['kategori'] ?? '';
$where_clause = "";
if($kategori != '') {
    $where_clause = " WHERE p.kategori='" . mysqli_real_escape_string($conn, $kategori) . "'";
}

$query = mysqli_query($conn, "
    SELECT p.*, u.nama FROM pengaduan p
    JOIN users u ON u.id = p.user_id
    $where_clause
    ORDER BY p.id DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | SekolahKu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    :root {
        --primary: #ff7043;
        --primary-dark: #e65100;
        --secondary: #fb8c00;
        --success: #43a047;
        --danger: #e53935;
        --warning: #fbc02d;
        --dark-blue: #37474f;
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

    /* SIDEBAR */
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

    /* STATS */
    .dashboard-stats { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); 
        gap: 20px; 
        margin-bottom: 40px; 
    }
    .card-box { 
        padding: 25px; 
        border-radius: 20px; 
        color: white; 
        text-align: center; 
        transition: 0.3s;
        box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    }
    .card-box span { font-size: 36px; font-weight: 800; display: block; margin-bottom: 5px; }
    .total { background: linear-gradient(135deg, #fb8c00, #ffb74d); }
    .menunggu { background: linear-gradient(135deg, #e53935, #ef5350); }
    .diproses { background: linear-gradient(135deg, #fbc02d, #fdd835); color: #5d4037; }
    .selesai { background: linear-gradient(135deg, #43a047, #66bb6a); }

    /* TABLE CARD */
    .card { 
        background: rgba(255, 255, 255, 0.95); 
        backdrop-filter: blur(10px); 
        padding: 30px; 
        border-radius: 24px; 
        margin-bottom: 30px; 
        border: 1px solid white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    /* FILTER & BUTTONS */
    .filter-box { display: flex; gap: 15px; margin-bottom: 25px; align-items: flex-end; flex-wrap: wrap; }
    .form-group { flex-grow: 1; min-width: 200px; }
    .form-modern select { 
        padding: 12px 15px; 
        border-radius: 12px; border: 2px solid #ffe0b2; outline: none;
        transition: 0.3s; font-size: 15px; width: 100%;
    }
    .button-group { display: flex; gap: 10px; }
    
    .btn-filter { 
        background: var(--primary-dark); padding: 12px 20px; border-radius: 12px; 
        border: none; color: white; font-weight: bold; cursor: pointer; transition: 0.3s;
        height: 48px; display: flex; align-items: center; gap: 8px;
    }
    .btn-cetak { 
        background: var(--dark-blue); padding: 12px 20px; border-radius: 12px; 
        border: none; color: white; font-weight: bold; cursor: pointer; transition: 0.3s;
        text-decoration: none; height: 48px; display: flex; align-items: center; gap: 8px; font-size: 14px;
    }
    .btn-filter:hover, .btn-cetak:hover { transform: translateY(-2px); opacity: 0.9; }

    /* TABLE STYLE */
    .table-container { overflow-x: auto; }
    table { width: 100%; border-collapse: separate; border-spacing: 0 10px; min-width: 900px; }
    th { padding: 15px; text-align: left; color: #8d6e63; font-size: 13px; text-transform: uppercase; }
    td { padding: 15px; background: white; vertical-align: middle; border-top: 1px solid #fcfcfc; }
    tr td:first-child { border-radius: 15px 0 0 15px; }
    tr td:last-child { border-radius: 0 15px 15px 0; }

    .badge { padding: 8px 14px; border-radius: 10px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
    .menunggu-badge { background: #ffebee; color: #c62828; }
    .diproses-badge { background: #fffde7; color: #f9a825; }
    .selesai-badge { background: #e8f5e9; color: #2e7d32; }

    .btn-action {
        padding: 8px 12px; border-radius: 8px; border: none; color: white;
        font-weight: bold; cursor: pointer; font-size: 12px; transition: 0.2s;
        text-decoration: none; display: inline-flex; align-items: center; gap: 5px;
    }
    .btn-proses { background: var(--secondary); }
    .btn-selesai { background: var(--success); }
    .btn-hapus { background: var(--danger); }

    /* MOBILE NAV */
    .menu-toggle { display: none; position: fixed; top: 20px; right: 20px; background: var(--primary-dark); color: white; padding: 10px; border-radius: 8px; z-index: 1001; cursor: pointer; }

    @media (max-width: 768px) {
        .sidebar { left: -100%; }
        .sidebar.active { left: 0; }
        .main { margin-left: 0; padding: 20px; padding-top: 80px; }
        .menu-toggle { display: block; }
        .dashboard-stats { grid-template-columns: 1fr 1fr; }
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
    <a href="admin.php" class="active"><i class="fas fa-chart-pie"></i> <span>Dashboard</span></a>
    <a href="users.php"><i class="fas fa-users"></i> <span>Kelola User</span></a>
    <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
</div>

<div class="main">
    <div class="welcome-text">
        <h2>Dashboard Pengaduan 👋</h2>
        <p>Selamat datang kembali, <strong><?= htmlspecialchars($_SESSION['nama']); ?></strong></p>
    </div>

    <div class="dashboard-stats">
        <div class="card-box total"><span><?= $total['total']; ?></span> Total Laporan </div>
        <div class="card-box menunggu"><span><?= $menunggu['total']; ?></span> Menunggu </div>
        <div class="card-box diproses"><span><?= $diproses['total']; ?></span> Diproses </div>
        <div class="card-box selesai"><span><?= $selesai['total']; ?></span> Selesai </div>
    </div>

    <div class="card">
        <h3 style="color: var(--primary-dark); margin: 0 0 20px 0;">
            <i class="fas fa-list"></i> Daftar Masuk Pengaduan
        </h3>
        
        <form method="GET" class="filter-box">
            <div class="form-group">
                <label style="font-size: 12px; font-weight: bold; color: #8d6e63; display:block; margin-bottom:5px;">Filter Kategori:</label>
                <select name="kategori" class="form-modern">
                    <option value="">Semua Kategori</option>
                    <option <?= $kategori=='Fasilitas'?'selected':'' ?>>Fasilitas</option>
                    <option <?= $kategori=='Kebersihan'?'selected':'' ?>>Kebersihan</option>
                    <option <?= $kategori=='Keamanan'?'selected':'' ?>>Keamanan</option>
                </select>
            </div>
            <div class="button-group">
                <button type="submit" class="btn-filter">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="cetak.php?kategori=<?= $kategori ?>" target="_blank" class="btn-cetak">
                    <i class="fas fa-print"></i> Cetak PDF
                </a>
            </div>
        </form>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Pelapor</th>
                        <th>Kategori</th>
                        <th>Isi Pengaduan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while($d = mysqli_fetch_array($query)){ 
                        $s = $d['status'];
                        $class = ($s == 'menunggu') ? 'menunggu-badge' : (($s == 'diproses') ? 'diproses-badge' : 'selesai-badge');
                    ?>
                    <tr>
                        <td align="center"><strong><?= $no++; ?></strong></td>
                        <td>
                            <strong><?= htmlspecialchars($d['nama']); ?></strong><br>
                            <small style="color: #999;"><?= date('d/m/y', strtotime($d['tanggal'])); ?></small>
                        </td>
                        <td><span style="font-weight: 600; color: var(--primary-dark);"><?= $d['kategori']; ?></span></td>
                        <td style="font-size: 14px; max-width: 300px;"><?= nl2br(htmlspecialchars($d['isi'])); ?></td>
                        <td><span class="badge <?= $class ?>"><?= $s ?></span></td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <form action="update_status.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $d['id']; ?>">
                                    <?php if($d['status']=='menunggu'){ ?>
                                        <button type="submit" name="status" value="diproses" class="btn-action btn-proses">Proses</button>
                                    <?php } elseif($d['status']=='diproses'){ ?>
                                        <button type="submit" name="status" value="selesai" class="btn-action btn-selesai">Selesai</button>
                                    <?php } ?>
                                </form>
                                <a href="hapus.php?id=<?= $d['id']; ?>" class="btn-action btn-hapus" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('active');
}
</script>
</body>
</html>
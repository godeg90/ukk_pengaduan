<?php 
session_start();
include 'koneksi.php';

// CEK LOGIN
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'siswa') {
    header("Location: /auth/login.php");
    exit;
}

$id_user = $_SESSION['id'];

// DASHBOARD COUNT
$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pengaduan WHERE user_id='$id_user'"));
$menunggu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pengaduan WHERE user_id='$id_user' AND status='menunggu'"));
$diproses = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pengaduan WHERE user_id='$id_user' AND status='diproses'"));
$selesai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pengaduan WHERE user_id='$id_user' AND status='selesai'"));

// FILTER
$kategori = $_GET['kategori'] ?? '';
$where_clause = "WHERE p.user_id='$id_user'";
if($kategori != '') {
    $where_clause .= " AND p.kategori='" . mysqli_real_escape_string($conn, $kategori) . "'";
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
    <title>Dashboard Siswa | SekolahKu</title>
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

    /* SIDEBAR */
    .sidebar {
        width: var(--sidebar-width);
        height: 100vh;
        position: fixed;
        background: var(--primary);
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
    .sidebar a.logout { margin-top: auto; background: rgba(0,0,0,0.1); }

    /* MAIN CONTENT */
    .main { 
        margin-left: var(--sidebar-width); 
        padding: 40px; 
        transition: 0.3s;
    }

    .welcome-text h2 { color: var(--primary-dark); margin: 0; font-size: 28px; }
    .welcome-text p { color: #8d6e63; margin: 5px 0 30px; }

    /* DASHBOARD STATS */
    .dashboard { 
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
    .card-box:hover { transform: translateY(-5px); }
    .card-box span { font-size: 36px; font-weight: 800; display: block; margin-bottom: 5px; }
    
    .total { background: linear-gradient(135deg, #fb8c00, #ffb74d); }
    .menunggu { background: linear-gradient(135deg, #e53935, #ef5350); }
    .diproses { background: linear-gradient(135deg, #fbc02d, #fdd835); color: #5d4037; }
    .selesai { background: linear-gradient(135deg, #43a047, #66bb6a); }

    /* CARD FORM & DATA */
    .card { 
        background: rgba(255, 255, 255, 0.95); 
        backdrop-filter: blur(10px); 
        padding: 30px; 
        border-radius: 24px; 
        margin-bottom: 30px; 
        border: 1px solid white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .form-modern label { display: block; margin-bottom: 8px; font-weight: 600; color: #5d4037; font-size: 14px; }
    .form-modern input, .form-modern select, .form-modern textarea { 
        width: 100%; padding: 12px 15px; margin-bottom: 20px; 
        border-radius: 12px; border: 2px solid #ffe0b2; outline: none;
        transition: 0.3s; font-size: 15px;
    }
    .form-modern input:focus, .form-modern select:focus, .form-modern textarea:focus { border-color: var(--primary); background: #fff; }

    .form-modern button { 
        width: 100%; background: linear-gradient(135deg, #fb8c00, #f4511e); 
        padding: 16px; border-radius: 12px; border: none; color: white; 
        font-weight: bold; cursor: pointer; transition: 0.3s; font-size: 16px;
    }
    .form-modern button:hover { opacity: 0.9; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(244, 81, 30, 0.3); }

    /* TABLE */
    .table-container { overflow-x: auto; margin-top: 10px; }
    table { width: 100%; border-collapse: separate; border-spacing: 0 10px; min-width: 900px; }
    th { padding: 15px; text-align: left; color: #8d6e63; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; }
    td { padding: 15px; background: white; vertical-align: middle; border-top: 1px solid #fcfcfc; }
    tr td:first-child { border-radius: 15px 0 0 15px; }
    tr td:last-child { border-radius: 0 15px 15px 0; }

    .badge { padding: 8px 14px; border-radius: 10px; font-size: 12px; font-weight: 700; display: inline-block; }
    .menunggu-badge { background: #ffebee; color: #c62828; }
    .diproses-badge { background: #fffde7; color: #f9a825; }
    .selesai-badge { background: #e8f5e9; color: #2e7d32; }

    .img-evidence { border-radius: 10px; object-fit: cover; transition: 0.3s; box-shadow: 0 3px 10px rgba(0,0,0,0.1); }
    .img-evidence:hover { transform: scale(1.1); }

    .filter-box { display: flex; gap: 10px; margin-bottom: 25px; align-items: flex-end; flex-wrap: wrap; }
    
    /* HAMBURGER FOR MOBILE */
    .menu-toggle { display: none; position: fixed; top: 20px; right: 20px; background: var(--primary); color: white; padding: 10px; border-radius: 8px; z-index: 1001; cursor: pointer; }

    /* RESPONSIVE DESIGN */
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
        .form-grid { grid-template-columns: 1fr !important; }
        .dashboard { grid-template-columns: 1fr 1fr; }
        .welcome-text h2 { font-size: 22px; }
    }

    @media (max-width: 480px) {
        .dashboard { grid-template-columns: 1fr; }
        .filter-box select { width: 100%; }
        .filter-box button { width: 100%; }
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
        <p>Siswa Access</p>
    </div>
    <a href="#" class="nav-link active" data-target="home"><i class="fas fa-home"></i> <span>Dashboard</span></a>
    <a href="#form" class="nav-link"><i class="fas fa-pen-nib"></i> <span>Buat Pengaduan</span></a>
    <a href="#data" class="nav-link"><i class="fas fa-folder-open"></i> <span>Data Laporan</span></a>
    <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
</div>

<div class="main">
    <div class="welcome-text">
        <h2>Halo, <?= htmlspecialchars($_SESSION['nama']); ?> 👋</h2>
        <p>Ada yang ingin kamu laporkan hari ini?</p>
    </div>

    <div class="dashboard">
        <div class="card-box total"><span><?= $total['total']; ?></span> Total Laporan </div>
        <div class="card-box menunggu"><span><?= $menunggu['total']; ?></span> Menunggu </div>
        <div class="card-box diproses"><span><?= $diproses['total']; ?></span> Diproses </div>
        <div class="card-box selesai"><span><?= $selesai['total']; ?></span> Selesai </div>
    </div>

    <div class="card" id="form">
        <h3 style="color: var(--primary-dark); margin: 0 0 25px 0; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-plus-circle"></i> Kirim Pengaduan Baru
        </h3>
        <form action="simpan.php" method="POST" enctype="multipart/form-data" class="form-modern">
            <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Kategori Laporan</label>
                    <select name="kategori" required>
                        <option value="">Pilih Kategori</option>
                        <option>Fasilitas</option>
                        <option>Kebersihan</option>
                        <option>Keamanan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-calendar-alt"></i> Tanggal Kejadian</label>
                    <input type="date" name="tanggal" value="<?= date('Y-m-d'); ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label><i class="fas fa-info-circle"></i> Detail Laporan</label>
                <textarea name="isi" rows="4" placeholder="Ceritakan detail keluhanmu secara jelas..." required></textarea>
            </div>
            <div class="form-group">
                <label><i class="fas fa-camera"></i> Lampiran Bukti (Foto)</label>
                <input type="file" name="bukti" accept="image/*" style="border: 2px dashed #ffe0b2; background: #fffaf3;">
            </div>
            <button type="submit">🚀 Kirim Laporan Sekarang</button>
        </form>
    </div>

    <div class="card" id="data">
        <h3 style="color: var(--primary-dark); margin: 0 0 20px 0;">📂 Riwayat Laporan Kamu</h3>
        
        <form method="GET" class="filter-box">
            <div style="flex-grow: 1;">
                <label style="font-size: 12px; font-weight: bold; color: #8d6e63;">Filter Kategori:</label>
                <select name="kategori" class="form-modern" style="margin-bottom:0;">
                    <option value="">Semua Kategori</option>
                    <option <?= $kategori=='Fasilitas'?'selected':'' ?>>Fasilitas</option>
                    <option <?= $kategori=='Kebersihan'?'selected':'' ?>>Kebersihan</option>
                    <option <?= $kategori=='Keamanan'?'selected':'' ?>>Keamanan</option>
                </select>
            </div>
            <button type="submit" class="form-modern" style="width: auto; padding: 12px 25px; margin-bottom: 0;">🔍 Filter</button>
        </form>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th width="120">Tanggal</th>
                        <th width="120">Kategori</th>
                        <th>Isi Laporan</th>
                        <th width="100">Bukti</th>
                        <th width="130">Status</th>
                        <th>Feedback Admin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while($dataRow = mysqli_fetch_array($query)){ 
                        $fotoPath = 'uploads/' . $dataRow['bukti'];
                    ?>
                    <tr>
                        <td align="center"><strong><?= $no++; ?></strong></td>
                        <td><?= date('d M Y', strtotime($dataRow['tanggal'])); ?></td>
                        <td><span style="color: var(--primary-dark); font-weight: 600;"><?= $dataRow['kategori']; ?></span></td>
                        <td style="font-size: 14px; line-height: 1.5; color: #5d4037;"><?= nl2br(htmlspecialchars($dataRow['isi'])); ?></td>
                        <td>
                            <?php if(!empty($dataRow['bukti']) && file_exists($fotoPath)){ ?>
                                <img src="<?= $fotoPath; ?>" width="60" height="60" class="img-evidence">
                            <?php } else { echo '<span style="color:#ccc">N/A</span>'; } ?>
                        </td>
                        <td>
                            <?php if($dataRow['status']=='menunggu'){ ?>
                                <span class="badge menunggu-badge"><i class="fas fa-clock"></i> Menunggu</span>
                            <?php } elseif($dataRow['status']=='diproses'){ ?>
                                <span class="badge diproses-badge"><i class="fas fa-sync"></i> Diproses</span>
                            <?php } else { ?>
                                <span class="badge selesai-badge"><i class="fas fa-check-circle"></i> Selesai</span>
                            <?php } ?>
                        </td>
                        <td>
                            <div style="background: #fdf2f0; padding: 10px; border-radius: 10px; font-size: 13px; border-left: 3px solid var(--primary);">
                                <?= !empty($dataRow['feedback']) ? '<strong>Admin:</strong> ' . htmlspecialchars($dataRow['feedback']) : '<span style="color:#999">Menunggu respon...</span>'; ?>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php if(mysqli_num_rows($query) == 0): ?>
                        <tr><td colspan="7" align="center" style="padding: 50px; color: #999;">Tidak ada data laporan ditemukan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Fungsi Toggle Sidebar Mobile
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('active');
}

// Navigasi Smooth & Active State
document.querySelectorAll('.sidebar a.nav-link').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        
        if (href.startsWith('#')) {
            e.preventDefault();
            const targetId = href.substring(1);
            const targetElement = document.getElementById(targetId) || document.body;
            
            window.scrollTo({
                top: targetElement.offsetTop - 20,
                behavior: 'smooth'
            });

            // Mobile: tutup sidebar setelah klik
            if(window.innerWidth <= 768) {
                document.getElementById('sidebar').classList.remove('active');
            }
        }

        // Update active class
        document.querySelectorAll('.sidebar a').forEach(a => a.classList.remove('active'));
        this.classList.add('active');
    });
});
</script>

</body>
</html>
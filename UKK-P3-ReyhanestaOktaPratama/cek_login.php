<?php
session_start();
include 'koneksi.php';

$username = $_POST['username'];
$password = $_POST['password'];

$data = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");
$user = mysqli_fetch_array($data);

if ($user) {
    $_SESSION['id'] = $user['id'];
    $_SESSION['nama'] = $user['nama'];
    $_SESSION['role'] = $user['role'];

    if ($user['role'] == 'admin') {
        header("Location: admin.php");
    } else {
        header("Location: siswa.php");
    }
} else {
    echo "Login gagal!";
}
?>
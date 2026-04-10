<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'siswa') {
    header("Location: /auth/login.php");
    exit;
}

$id_user = $_SESSION['id'];

// AMBIL DATA DARI FORM, ESCAPE
$kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
$isi = mysqli_real_escape_string($conn, $_POST['isi']);
$tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
$status = 'menunggu'; // default status
$feedback = '';

// CEK FILE UPLOAD
$bukti = '';
if(isset($_FILES['bukti']) && $_FILES['bukti']['error'] == 0){
    $filename = $_FILES['bukti']['name'];
    $tmpname = $_FILES['bukti']['tmp_name'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $newname = 'bukti_'.time().'_'.rand(1000,9999).'.'.$ext;

    if(!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }
    if(move_uploaded_file($tmpname, 'uploads/'.$newname)){
        $bukti = $newname;
    }
}

// CEK DUPLIKASI: sama user, tanggal, dan isi
$cek = mysqli_query($conn, "SELECT * FROM pengaduan WHERE user_id='$id_user' AND tanggal='$tanggal' AND isi='$isi'");
if(mysqli_num_rows($cek) == 0){
    $sql = "INSERT INTO pengaduan (user_id, kategori, isi, bukti, tanggal, status, feedback) 
            VALUES ('$id_user', '$kategori', '$isi', '$bukti', '$tanggal', '$status', '$feedback')";

    if(mysqli_query($conn, $sql)){
        // REDIRECT supaya form tidak double submit
        header("Location: siswa.php?success=1");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // Jika duplikat
    header("Location: siswa.php?duplicate=1");
    exit;
}
?>
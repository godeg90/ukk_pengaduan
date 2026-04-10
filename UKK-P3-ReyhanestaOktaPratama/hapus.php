<?php
include 'koneksi.php';

$id = $_GET['id'];
mysqli_query($conn,"DELETE FROM pengaduan WHERE id='$id'");

header("Location: admin.php");
?>
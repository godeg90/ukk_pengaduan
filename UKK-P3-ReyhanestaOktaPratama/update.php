<?php
include 'koneksi.php';

$id = $_POST['id'];
$status = isset($_POST['status']) ? $_POST['status'] : null;
$feedback = $_POST['feedback'];

$query = "";

if($status){
    $query = "UPDATE pengaduan SET status='$status', feedback='$feedback' WHERE id='$id'";
} else {
    $query = "UPDATE pengaduan SET feedback='$feedback' WHERE id='$id'";
}

mysqli_query($conn, $query) or die(mysqli_error($conn));

header("Location: admin.php");
?>
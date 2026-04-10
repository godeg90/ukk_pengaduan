<?php 
include 'koneksi.php';

$id = $_GET['id'];
$data = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE id='$id'"));

if(isset($_POST['update'])){
    $nama = $_POST['nama'];
    $username = $_POST['username'];

    mysqli_query($conn, "UPDATE users SET 
        nama='$nama',
        username='$username'
        WHERE id='$id'
    ");

    header("Location: users.php");
}
?>

<form method="POST">
<input type="text" name="nama" value="<?= $data['nama']; ?>">
<input type="text" name="username" value="<?= $data['username']; ?>">
<button name="update">Update</button>
</form>
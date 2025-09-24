<?php
session_start();
include 'koneksi.php';

$user = mysqli_real_escape_string($koneksi, $_POST['username']);
$pass = $_POST['password'];

$data = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$user'");
$row = mysqli_fetch_array($data);

if ($row && password_verify($pass, $row['password'])) {
    $_SESSION['username'] = $row['username'];
    $_SESSION['nama'] = $row['nama']; 
    
    header("Location: index.php");
    exit();
} else {
    echo '<script>alert("Username atau Password salah");window.location.href="login.php";</script>';
    exit();
}
?>
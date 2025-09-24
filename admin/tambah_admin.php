<?php
require('koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password']; 
    $nama     = $_POST['nama'];
    $email    = $_POST['email'];
    $wa       = $_POST['wa'];

    // Hash password sebelum simpan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // cek upload file
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $gambar = $_FILES['gambar']['name'];
        $tmp    = $_FILES['gambar']['tmp_name'];
        move_uploaded_file($tmp, "img/" . $gambar);
    } else {
        $gambar = "";
    }

    // validasi semua field harus diisi
    if ($username && $password && $nama && $email && $wa && $gambar) {
        $sql = "INSERT INTO admin (username, password, nama, email, wa, gambar)
                VALUES ('$username','$hashed_password','$nama','$email','$wa','$gambar')";
        $result = mysqli_query($koneksi, $sql);

        if ($result) {
            // Redirect to admin.php after successful addition
            header("Location: admin.php");
            exit;
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    } else {
        echo "Semua field wajib diisi.";
    }
}
?>

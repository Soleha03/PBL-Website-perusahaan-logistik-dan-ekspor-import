<?php
require('koneksi.php');

// Memeriksa apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];

    // Memindahkan file gambar ke lokasi yang ditentukan
    $gambar = $_FILES['gambar']['name'];
    $ukuran_file = $_FILES['gambar']['size'];
    $tmp_file = $_FILES['gambar']['tmp_name'];
    $uploadDir = 'img/';
    $path = $uploadDir . $gambar;
    $ekstensi_diperbolehkan = array('jpg', 'png');
    $x = explode('.', $gambar);
    $ekstensi = strtolower(end($x));

    if (in_array($ekstensi, $ekstensi_diperbolehkan) && $ukuran_file <= 1000000) {
        if (move_uploaded_file($tmp_file, $path)) {
            $stmt = $koneksi->prepare("INSERT INTO berita (judul, isi_teks, gambar) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $judul, $isi, $gambar);

            if ($stmt->execute()) {
                header("Location: news.php");
                exit;
            } else {
                echo "Terjadi Kesalahan Upload: " . $stmt->error;
                echo "<br><a href='news.php'>Kembali</a>";
            }

            $stmt->close();
        } else {
            echo "Terjadi kesalahan dalam mengupload gambar.";
        }
    } else {
        echo "Format file gambar tidak diperbolehkan atau ukuran file terlalu besar.";
    }
}

$koneksi->close();
?>

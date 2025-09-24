<?php
require('admin/koneksi.php');

// Memeriksa apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data form
    $nama_lengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $no_telp = $_POST['no_telp'];
    $pendidikan_terakhir = $_POST['pendidikan_terakhir'];
    $pengalaman = $_POST['pengalaman'];
    $jurusan = $_POST['jurusan'];

    // Ambil file CV
    $cv = $_FILES['cv']['name'];
    $ukuran_file = $_FILES['cv']['size'];
    $tmp_file = $_FILES['cv']['tmp_name'];
    $uploadDir = 'admin/file/'; 
    $path = $uploadDir . $cv;

    // Format file yang diperbolehkan
    $ekstensi_diperbolehkan = array('jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx');

    // Ambil ekstensi file
    $x = explode('.', $cv);
    $ekstensi = strtolower(end($x));

    // Maksimal 5MB
    $max_size = 5 * 1024 * 1024;

    if (in_array($ekstensi, $ekstensi_diperbolehkan) && $ukuran_file <= $max_size) {
        if (move_uploaded_file($tmp_file, $path)) {
            // Query insert
            $query = "INSERT INTO lamaran (nama_lengkap, email, no_telp, pendidikan_terakhir, pengalaman, jurusan, cv) 
                      VALUES ('$nama_lengkap', '$email', '$no_telp','$pendidikan_terakhir', '$pengalaman', '$jurusan', '$cv')";
            $sql = mysqli_query($koneksi, $query);

            if ($sql) {
                header("Location: index.php");
                exit;
            } else {
                echo "❌ Terjadi kesalahan saat menyimpan data ke database.";
                echo "<br><a href='index.php'>Kembali</a>";
            }
        } else {
            echo "❌ Gagal upload file.";
        }
    } else {
        echo "❌ Format file tidak diperbolehkan atau ukuran file terlalu besar (maksimal 5MB).";
    }
}

mysqli_close($koneksi);
?>

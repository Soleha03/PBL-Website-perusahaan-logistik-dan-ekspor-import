<?php
	require 'koneksi.php';

	if (isset($_POST['Submit'])) {
		$judul = $_POST['judul'];
    	$isi = $_POST['isi'];
		$id = $_POST['id'];

		// Cek apakah gambar baru diunggah
		if ($_FILES['gambar']['name']) {
			$gambar = $_FILES['gambar']['name'];
			$targetDir = "img/";
			$targetFile = $targetDir . basename($gambar);
			move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetFile);

			// Hapus gambar lama jika ada
			$oldImage = $_POST['old_image'];
			if ($oldImage) {
				unlink($targetDir . $oldImage);
			}
		} else {
			// Jika tidak ada gambar baru diunggah, gunakan gambar lama
			$gambar = $_POST['old_image'];
		}

		$query = mysqli_query($koneksi, "UPDATE berita SET judul='$judul', isi_teks='$isi', gambar='$gambar' WHERE id=$id");
		if ($query) {
			header("location: news.php");
		} else {
			echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
		}
	}
?>

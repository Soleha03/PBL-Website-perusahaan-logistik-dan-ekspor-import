<?php
	require('koneksi.php');
	$id	= $_GET["id"];
	$hapus_data 		= "DELETE FROM business WHERE id='$id'";
	$query  			= mysqli_query($koneksi, $hapus_data);
	echo "<meta http-equiv='refresh' content='0;url=business.php'>";
?>
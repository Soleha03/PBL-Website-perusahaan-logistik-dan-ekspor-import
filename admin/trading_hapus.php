<?php
	require('koneksi.php');
	$id	= $_GET["id"];
	$hapus_data 		= "DELETE FROM trading WHERE id='$id'";
	$query  			= mysqli_query($koneksi, $hapus_data);
	echo "<meta http-equiv='refresh' content='0;url=trading.php'>";
?>
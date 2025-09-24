<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "company_website_db"; // Nama Database

// Melakukan koneksi ke database
$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Gagal koneksi: " . mysqli_connect_error());
}

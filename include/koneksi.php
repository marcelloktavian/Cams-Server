<?php

// panggil fungsi validasi xss dan injection

$server =  "localhost";
$username = "root";
$password = "";
$database = "cams_db2022";

// Koneksi dan memilih database di server
mysql_connect($server,$username,$password) or die("Koneksi gagal");
mysql_select_db($database) or die("Database tidak bisa dibuka");



?>

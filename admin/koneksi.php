<?php 

$host = "localhost";
$user = "root";
$pass = "ilkom123";
$db = "project_online"; 

$koneksi = mysqli_connect($host, $user, $pass, $db);

	if (!$koneksi) {
		die("Koneksi Gagal:".mysqli_connect_error());
	}
 ?>
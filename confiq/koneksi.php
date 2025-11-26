<?php
$host     = "localhost";
$user     = "pikreque_request123";
$password = "v[2V%5GlD*=K";
$database = "pikreque_request_web"; // Ganti sesuai nama database kamu

// Pastikan variabel $base_url didefinisikan dengan HTTPS!
$base_url = "https://pikrequestsman1tpp.my.id/"; // <-- PENTING: PASTIKAN INI HTTPS DAN TIDAK ADA "HTTP://" GANDA

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Set timezone untuk fungsi date() (opsional, tapi disarankan)
date_default_timezone_set('Asia/Jakarta');

?>
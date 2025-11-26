<?php
// ------------------------------------------------------------------
// 1. DEFENISI UTAMA (LIVE CREDENTIALS) SEBAGAI NILAI DEFAULT
// ------------------------------------------------------------------
$host     = "localhost";
$user     = "pikreque_request123";
$password = "v[2V%5GlD*=K";
$database = "pikreque_request_web";
$base_url = "https://pikrequestsman1tpp.my.id/"; 
// ------------------------------------------------------------------


// 2. CEK DAN TIMPA (OVERRIDE) JIKA FILE LOKAL ADA
if (file_exists(__DIR__ . '/db_override.php')) {
    include __DIR__ . '/db_override.php';
    
    // Timpa variabel default dengan variabel override
    $host = $host_override;
    $user = $user_override;
    $password = $password_override;
    $database = $database_override;
    $base_url = $base_url_override;
}


// 3. GUNAKAN NILAI AKHIR UNTUK KONEKSI
$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

date_default_timezone_set('Asia/Jakarta');

?>
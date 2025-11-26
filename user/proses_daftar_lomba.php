<?php
session_start();
include '../confiq/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

// Tangkap data POST dan bersihkan
$nama_peserta = trim($_POST['nama_peserta'] ?? '');
$kelas = trim($_POST['kelas'] ?? '');
$id_lomba = $_POST['id_lomba'] ?? '';
$anggota_kelompok = trim($_POST['anggota_kelompok'] ?? '');
$kontak = trim($_POST['kontak'] ?? '');

// Validasi wajib
if (!$nama_peserta || !$kelas || !$id_lomba || !$kontak) {
    $_SESSION['error'] = "Data wajib belum lengkap, silakan isi semua field kecuali anggota kelompok.";
    header("Location: daftar_lomba.php");
    exit;
}

// Validasi format nomor kontak sederhana
if (!preg_match('/^[0-9+]+$/', $kontak)) {
    $_SESSION['error'] = "Nomor kontak tidak valid. Gunakan angka dan tanda + saja.";
    header("Location: daftar_lomba.php");
    exit;
}

// Escape string untuk keamanan
$nama_peserta = mysqli_real_escape_string($conn, $nama_peserta);
$kelas = mysqli_real_escape_string($conn, $kelas);
$id_lomba = (int)$id_lomba;
$anggota_kelompok = mysqli_real_escape_string($conn, $anggota_kelompok);
$kontak = mysqli_real_escape_string($conn, $kontak);

// Ambil nama lomba berdasarkan ID
$result = mysqli_query($conn, "SELECT nama_lomba FROM lomba WHERE id_lomba = '$id_lomba'");
$data_lomba = mysqli_fetch_assoc($result);
$nama_lomba = $data_lomba['nama_lomba'] ?? '';

// Simpan ke database
$query = "INSERT INTO pendaftaran_lomba 
(nama_peserta, kelas, id_lomba, anggota_kelompok, kontak, tanggal_daftar)
VALUES ('$nama_peserta', '$kelas', '$id_lomba', '$anggota_kelompok', '$kontak', NOW())";

if (mysqli_query($conn, $query)) {
    $_SESSION['success'] = "🎉 Pendaftaran Anda berhasil!";
    $_SESSION['nama_peserta'] = $nama_peserta;
    $_SESSION['nama_lomba'] = $nama_lomba;
    header("Location: daftar_lomba.php");
    exit;
} else {
    $_SESSION['error'] = "Terjadi kesalahan saat menyimpan data: " . mysqli_error($conn);
    header("Location: daftar_lomba.php");
    exit;
}

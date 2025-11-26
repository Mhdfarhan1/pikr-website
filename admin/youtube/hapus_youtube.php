<?php
include '../../confiq/koneksi.php'; // pastikan nama folder dan file benar


$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>alert('ID video tidak ditemukan!'); window.location.href='../dashboard_admin.php?page=youtube_channel';</script>";
    exit;
}

// Ambil link atau info sebelum hapus (opsional)
$get = mysqli_query($conn, "SELECT judul, link FROM youtube WHERE id = '$id'");
$data = mysqli_fetch_assoc($get);

if (!$data) {
    echo "<script>alert('Video tidak ditemukan!'); window.location.href='../dashboard_admin.php?page=youtube_channel';</script>";
    exit;
}

// Hapus data dari database
$hapus = mysqli_query($conn, "DELETE FROM youtube WHERE id = '$id'");

if ($hapus) {
    echo "<script>
        alert('Video berhasil dihapus.');
        window.location.href = '../dashboard_admin.php?page=youtube_channel';
    </script>";
} else {
    echo "<script>
        alert('Gagal menghapus video.');
        window.location.href = '../dashboard_admin.php?page=youtube_channel';
    </script>";
}
?>

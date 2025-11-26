<?php
include '../../confiq/koneksi.php';

// Pastikan ID dikirim
$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>alert('ID tidak ditemukan'); window.location.href='dashboard_admin.php?page=data_prestasi';</script>";
    exit;
}

// Ambil data prestasi
$query = mysqli_query($conn, "SELECT * FROM prestasi WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan'); window.location.href='../dashboard_admin.php?page=data_prestasi';</script>";
    exit;
}

// Hapus gambar dari folder jika ada
if (!empty($data['gambar']) && file_exists('../' . $data['gambar'])) {
    unlink('../' . $data['gambar']);
}

// Hapus data dari database
$delete = mysqli_query($conn, "DELETE FROM prestasi WHERE id = '$id'");

if ($delete) {
    echo "<script>alert('Data prestasi berhasil dihapus'); window.location.href='../dashboard_admin.php?page=data_prestasi';</script>";
} else {
    echo "<script>alert('Gagal menghapus data'); window.history.back();</script>";
}
?>

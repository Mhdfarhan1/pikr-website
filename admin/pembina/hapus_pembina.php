<?php
include '../confiq/koneksi.php';

$id = $_GET['id'] ?? 0;
$id = intval($id);

if ($id <= 0) {
    echo "<script>alert('ID pembina tidak valid.'); window.location.href='dashboard_admin.php?page=data_pembina';</script>";
    exit;
}

// Ambil data pembina dulu untuk mendapatkan id_user
$query = mysqli_query($conn, "SELECT id_user FROM pembina WHERE id_guru = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data pembina tidak ditemukan.'); window.location.href='dashboard_admin.php?page=data_pembina';</script>";
    exit;
}

$id_user = $data['id_user'];

// Mulai transaksi untuk menghapus pembina dan user terkait
mysqli_begin_transaction($conn);

try {
    // Hapus data pembina
    $hapus_pembina = mysqli_query($conn, "DELETE FROM pembina WHERE id_guru = $id");
    if (!$hapus_pembina) throw new Exception("Gagal menghapus data pembina.");

    // Hapus data user terkait (opsional, jika memang diinginkan)
    // Jika user digunakan untuk login dan hanya milik pembina ini,
    // hapus juga data user agar tidak ada user "orphan".
    $hapus_user = mysqli_query($conn, "DELETE FROM users WHERE id = $id_user");
    if (!$hapus_user) throw new Exception("Gagal menghapus data user.");

    // Commit transaksi
    mysqli_commit($conn);

    echo "<script>alert('Data pembina berhasil dihapus.'); window.location.href='dashboard_admin.php?page=data_pembina';</script>";
} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "<script>alert('Terjadi kesalahan: " . $e->getMessage() . "'); window.location.href='dashboard_admin.php?page=data_pembina';</script>";
}

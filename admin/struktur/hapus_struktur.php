<?php
include '../../confiq/koneksi.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>alert('ID tidak ditemukan!'); window.location.href='../dashboard_admin.php?page=data_struktur';</script>";
    exit;
}

// Ambil nama file gambar sebelum hapus
$get = mysqli_query($conn, "SELECT foto FROM struktur_organisasi WHERE id = '$id'");
$data = mysqli_fetch_assoc($get);

if ($data && $data['foto']) {
    $filePath = "../upload/struktur/" . $data['foto'];
    if (file_exists($filePath)) {
        unlink($filePath); // Hapus file dari folder
    }
}

// Hapus data dari database
$hapus = mysqli_query($conn, "DELETE FROM struktur_organisasi WHERE id = '$id'");

if ($hapus) {
    echo "<script>
        alert('Data berhasil dihapus.');
        window.location.href = '../dashboard_admin.php?page=data_struktur';
    </script>";
} else {
    echo "<script>
        alert('Gagal menghapus data.');
        window.location.href = '../dashboard_admin.php?page=data_struktur';
    </script>";
}

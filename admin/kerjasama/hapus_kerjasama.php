<?php
include '../../confiq/koneksi.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<script>alert('ID tidak ditemukan!'); window.location.href='../dashboard_admin.php?page=data_kerjasama';</script>";
    exit;
}

// Ambil data dulu untuk mendapatkan nama file gambar
$query = mysqli_query($conn, "SELECT * FROM kerja_sama WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='../dashboard_admin.php?page=data_kerjasama';</script>";
    exit;
}

// Hapus gambar dari folder jika ada
if (!empty($data['gambar'])) {
    $path = '../upload/kerjasama/' . $data['gambar'];
    if (file_exists($path)) {
        unlink($path);
    }
}

// Hapus data dari database
$delete = mysqli_query($conn, "DELETE FROM kerja_sama WHERE id = '$id'");

if ($delete) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data kerja sama berhasil dihapus!',
            showConfirmButton: false,
            timer: 2000
        }).then(() => {
            window.location.href = '../dashboard_admin.php?page=data_kerjasama';
        });
    </script>";
} else {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Gagal menghapus data!',
            confirmButtonColor: '#d33'
        }).then(() => {
            window.location.href = '../dashboard_admin.php?page=data_kerjasama';
        });
    </script>";
}
?>

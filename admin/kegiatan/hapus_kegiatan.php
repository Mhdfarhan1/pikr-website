<?php
session_start();
include '../../confiq/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

$id = $_GET['id'] ?? null;
$delete = false;

if ($id) {
    $delete = mysqli_query($conn, "DELETE FROM kegiatan WHERE id='$id'");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hapus Kegiatan</title>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <script>
        Swal.fire({
            icon: '<?= $delete ? "success" : "error" ?>',
            title: '<?= $delete ? "Berhasil" : "Gagal" ?>',
            text: '<?= $delete ? "Data kegiatan berhasil dihapus." : "Gagal menghapus data kegiatan." ?>',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = '../dashboard_admin.php?page=data_kegiatan';
        });
    </script>
</body>
</html>

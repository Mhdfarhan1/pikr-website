<?php
include '../../confiq/koneksi.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<script>
        alert('ID tidak ditemukan!');
        window.location.href='../dashboard_admin.php?page=data_lomba';
    </script>";
    exit;
}

$query = mysqli_query($conn, "SELECT * FROM pendaftaran_lomba WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>
        alert('Data tidak ditemukan!');
        window.location.href='../dashboard_admin.php?page=data_lomba';
    </script>";
    exit;
}

$delete = mysqli_query($conn, "DELETE FROM pendaftaran_lomba WHERE id = '$id'");

// Hentikan output HTML putih dengan membuat dokumen HTML lengkap:
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hapus Data</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php if ($delete): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data lomba berhasil dihapus!',
            showConfirmButton: false,
            timer: 2000
        }).then(() => {
            window.location.href = '../dashboard_admin.php?page=data_pendaftaran';
        });
    </script>
<?php else: ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Gagal menghapus data.',
            showConfirmButton: true
        }).then(() => {
            window.location.href = '../dashboard_admin.php?page=data_pendaftaran';
        });
    </script>
<?php endif; ?>
</body>
</html>

<?php
include '../../confiq/koneksi.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<script>
        document.write('<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script>');
        Swal.fire({
            icon: 'error',
            title: 'ID Tidak Ditemukan!',
            text: 'ID berita tidak valid.',
        }).then(() => {
            window.location.href = '../dashboard_admin.php?page=data_berita';
        });
    </script>";
    exit;
}

// Ambil data berita untuk cek gambar
$query = mysqli_query($conn, "SELECT * FROM berita WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>
        document.write('<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script>');
        Swal.fire({
            icon: 'error',
            title: 'Data Tidak Ditemukan!',
            text: 'Berita dengan ID tersebut tidak tersedia.',
        }).then(() => {
            window.location.href = '../dashboard_admin.php?page=data_berita';
        });
    </script>";
    exit;
}

// Hapus gambar jika ada
if (!empty($data['gambar'])) {
    $path = '../upload/berita/' . $data['gambar'];
    if (file_exists($path)) {
        unlink($path);
    }
}

// Hapus data berita
$delete = mysqli_query($conn, "DELETE FROM berita WHERE id = '$id'");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hapus Berita</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<script>
    Swal.fire({
        icon: '<?= $delete ? "success" : "error" ?>',
        title: '<?= $delete ? "Berita Dihapus!" : "Gagal Menghapus!" ?>',
        text: '<?= $delete ? "Data berita berhasil dihapus." : "Terjadi kesalahan saat menghapus data." ?>',
        confirmButtonColor: '<?= $delete ? "#3085d6" : "#d33" ?>',
        confirmButtonText: 'OK'
    }).then(() => {
        window.location.href = '../dashboard_admin.php?page=data_berita';
    });
</script>
</body>
</html>

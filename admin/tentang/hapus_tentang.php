<?php
include '../../confiq/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

// Ambil ID dari URL
$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'ID tidak ditemukan!',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href='../dashboard_admin.php?page=data_tentang';
        });
    </script>";
    exit;
}

// Ambil data lama
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tentang_pikr WHERE id = '$id'"));
if (!$data) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Data tidak ditemukan!',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href='../dashboard_admin.php?page=data_tentang';
        });
    </script>";
    exit;
}

$kategori = $data['kategori'];
$gambar = $data['gambar'];

// Hapus gambar
$pathGambar = '../upload/tentang/' . $gambar;
if (!empty($gambar) && file_exists($pathGambar)) {
    unlink($pathGambar);
}

// Hapus dari DB
$hapus = mysqli_query($conn, "DELETE FROM tentang_pikr WHERE id = '$id'");
?>

<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php if ($hapus): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Data berhasil dihapus!',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = '../dashboard_admin.php?page=data_tentang&kategori=<?= $kategori ?>';
        });
    </script>
<?php else: ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal menghapus data!',
            confirmButtonText: 'OK'
        }).then(() => {
            window.history.back();
        });
    </script>
<?php endif; ?>
</body>
</html>

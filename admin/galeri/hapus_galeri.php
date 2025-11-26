<?php
include '../../confiq/koneksi.php';
$id = intval($_GET['id'] ?? 0);

// Ambil output awal (tutup PHP biar bisa pakai HTML)
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hapus Galeri</title>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
if ($id <= 0) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'ID tidak valid!',
            text: 'ID galeri yang akan dihapus tidak ditemukan atau tidak valid.',
            showConfirmButton: false,
            timer: 3000
        }).then(() => {
            window.location.href='../dashboard_admin.php?page=data_galeri';
        });
    </script>";
    exit;
}

$stmt_select = mysqli_prepare($conn, "SELECT gambar FROM galeri WHERE id = ? LIMIT 1");
if (!$stmt_select) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Gagal menyiapkan query pemilihan data.',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href='../dashboard_admin.php?page=data_galeri';
        });
    </script>";
    exit;
}
mysqli_stmt_bind_param($stmt_select, "i", $id);
mysqli_stmt_execute($stmt_select);
$result_select = mysqli_stmt_get_result($stmt_select);

if (mysqli_num_rows($result_select) == 0) {
    echo "<script>
        Swal.fire({
            icon: 'warning',
            title: 'Tidak Ditemukan!',
            text: 'Data galeri tidak ditemukan.',
            showConfirmButton: false,
            timer: 3000
        }).then(() => {
            window.location.href='../dashboard_admin.php?page=data_galeri';
        });
    </script>";
    exit;
}
$data = mysqli_fetch_assoc($result_select);
mysqli_stmt_close($stmt_select);

// Hapus gambar fisik
if (!empty($data['gambar'])) {
    $gambarPath = '../../upload/galeri/' . $data['gambar'];
    if (file_exists($gambarPath)) {
        if (!unlink($gambarPath)) {
            error_log("Gagal menghapus file gambar: " . $gambarPath);
        }
    }
}

// Hapus data dari database
$stmt_delete = mysqli_prepare($conn, "DELETE FROM galeri WHERE id = ?");
if (!$stmt_delete) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Gagal menyiapkan query penghapusan data.',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href='../dashboard_admin.php?page=data_galeri';
        });
    </script>";
    exit;
}
mysqli_stmt_bind_param($stmt_delete, "i", $id);

if (mysqli_stmt_execute($stmt_delete)) {
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Galeri berhasil dihapus.',
            showConfirmButton: false,
            timer: 2000
        }).then(() => {
            window.location.href = '../dashboard_admin.php?page=data_galeri';
        });
    </script>";
} else {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Gagal menghapus galeri: " . mysqli_error($conn) . "',
            confirmButtonText: 'OK'
        }).then(() => {
            window.history.back();
        });
    </script>";
}
mysqli_stmt_close($stmt_delete);
?>
</body>
</html>

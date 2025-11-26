<?php
include '../confiq/koneksi.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            icon: 'warning',
            title: 'ID Tidak Valid',
            text: 'ID frofile tidak ditemukan atau tidak sesuai.',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            window.location.href = 'dashboard_admin.php?page=data_frofile';
        });
    </script>";
    exit;
}

// Ambil data frofile dulu untuk cek dan ambil nama file foto
$sql = "SELECT foto FROM frofile WHERE id = $id LIMIT 1";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) == 0) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Data Tidak Ditemukan',
            text: 'Data frofile tidak ada dalam database.',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            window.location.href = 'dashboard_admin.php?page=data_frofile';
        });
    </script>";
    exit;
}
$row = mysqli_fetch_assoc($result);

// Hapus data dari DB
$deleteSql = "DELETE FROM frofile WHERE id = $id";
if (mysqli_query($conn, $deleteSql)) {
    // Hapus file foto jika ada dan file ada di folder
    if ($row['foto'] && file_exists('../assets/img/frofile/' . $row['foto'])) {
        unlink('../assets/img/frofile/' . $row['foto']);
    }

    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil Dihapus',
            text: 'Data frofile berhasil dihapus.',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            window.location.href = 'dashboard_admin.php?page=data_frofile';
        });
    </script>";
    exit;
} else {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal Menghapus',
            text: 'Terjadi kesalahan saat menghapus data.',
            confirmButtonColor: '#d33'
        }).then(() => {
            window.location.href = 'dashboard_admin.php?page=data_frofile';
        });
    </script>";
    exit;
}
?>

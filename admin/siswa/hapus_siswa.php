<?php
include '../confiq/koneksi.php';

if (!isset($_GET['id'])) {
    echo "<script>alert('ID siswa tidak ditemukan.'); window.location.href='dashboard_admin.php?page=data_siswa';</script>";
    exit;
}

$id = (int) $_GET['id'];

// Ambil data siswa untuk ambil id_user-nya
$cek = mysqli_query($conn, "SELECT id_user FROM siswa WHERE id = $id");
if (mysqli_num_rows($cek) == 0) {
    echo "<script>window.location.href='dashboard_admin.php?page=data_siswa&hapus=sukses';</script>";
    exit;
}

$data = mysqli_fetch_assoc($cek);
$id_user = $data['id_user'];

// Hapus siswa dari tabel siswa
$hapus_siswa = mysqli_query($conn, "DELETE FROM siswa WHERE id = $id");

// Hapus akun user siswa juga
$hapus_user = mysqli_query($conn, "DELETE FROM users WHERE id = $id_user");

if ($hapus_siswa && $hapus_user) {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data siswa berhasil dihapus.',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'dashboard_admin.php?page=data_siswa';
        });
    </script>";
} else {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Gagal menghapus data siswa.',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Kembali'
        }).then(() => {
            window.location.href = 'dashboard_admin.php?page=data_siswa';
        });
    </script>";
}
?>

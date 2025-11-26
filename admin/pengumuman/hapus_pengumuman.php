<?php
include '../../confiq/koneksi.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'ID Tidak Valid',
                text: 'ID pengumuman tidak valid!',
            }).then(() => {
                window.location.href='dashboard_admin.php?page=data_pengumuman';
            });
        });
    </script>";
    exit;
}

// Ambil data lama untuk hapus file jika perlu
$query = mysqli_query($conn, "SELECT gambar FROM pengumuman WHERE id = $id LIMIT 1");
$data = mysqli_fetch_assoc($query);

// Hapus gambar jika ada dan file-nya tersedia
if ($data && $data['gambar']) {
    $filePath = '../upload/pengumuman/' . $data['gambar'];
    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

// Hapus data dari database
$delete = mysqli_query($conn, "DELETE FROM pengumuman WHERE id = $id");
?>
<!-- Panggil library SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    <?php if ($delete): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Pengumuman berhasil dihapus!',
        }).then(() => {
            window.location.href = '../dashboard_admin.php?page=data_pengumuman';
        });
    <?php else: ?>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Gagal menghapus pengumuman.',
        }).then(() => {
            window.location.href = '../dashboard_admin.php?page=data_pengumuman';
        });
    <?php endif; ?>
});
</script>

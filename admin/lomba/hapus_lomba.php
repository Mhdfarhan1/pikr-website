<?php
include '../../confiq/koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk hapus data
    $query = "DELETE FROM lomba WHERE id_lomba = '$id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>
            alert('Data lomba berhasil dihapus.');
            window.location.href = '../dashboard_admin.php?page=data_lomba';
        </script>";
    } else {
        echo "<script>
            alert('Gagal menghapus data.');
            window.location.href = '../dashboard_admin.php?page=data_lomba';
        </script>";
    }
} else {
    echo "<script>
        alert('ID tidak ditemukan.');
        window.location.href = '../dashboard_admin.php?page=data_lomba';
    </script>";
}
?>

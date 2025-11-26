<?php
include '../../confiq/koneksi.php';
$id = $_GET['id'];
$get = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM logo WHERE id = '$id'"));
if ($get && !empty($get['gambar'])) {
    unlink("../../upload/logo/" . $get['gambar']);
}
mysqli_query($conn, "DELETE FROM logo WHERE id = '$id'");
header('Location: ../dashboard_admin.php?page=data_logo');
?>

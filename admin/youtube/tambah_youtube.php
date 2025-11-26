<?php
include '../confiq/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

if (isset($_POST['submit'])) {
    $judul = htmlspecialchars($_POST['judul']);
    $link  = htmlspecialchars($_POST['link']);
    $tanggal_upload = $_POST['tanggal_upload'];

    $insert = mysqli_query($conn, "INSERT INTO youtube (judul, link, tanggal_upload) 
                                   VALUES ('$judul', '$link', '$tanggal_upload')");
    if ($insert) {
        echo "<script>
                alert('Video berhasil ditambahkan!');
                window.location.href='dashboard_admin.php?page=youtube_channel';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<div class="container px-4">
    <h3 class="mb-4">Tambah Video YouTube</h3>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Judul Video</label>
            <input type="text" name="judul" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Link Embed / URL</label>
            <input type="text" name="link" class="form-control" placeholder="https://www.youtube.com/embed/..." required>
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal Upload</label>
            <input type="datetime-local" name="tanggal_upload" class="form-control" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Tambah Video</button>
        <a href="dashboard_admin.php?page=youtube_channel" class="btn btn-secondary">Batal</a>
    </form>
</div>

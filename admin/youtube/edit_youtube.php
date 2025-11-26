<?php
include '../confiq/koneksi.php';

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM youtube WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (isset($_POST['submit'])) {
    $judul = htmlspecialchars($_POST['judul']);
    $link  = htmlspecialchars($_POST['link']);
    $tanggal_upload = $_POST['tanggal_upload'];

    $update = mysqli_query($conn, "UPDATE youtube SET 
                                    judul='$judul', 
                                    link='$link', 
                                    tanggal_upload='$tanggal_upload' 
                                    WHERE id=$id");

    if ($update) {
        echo "<script>
                alert('Video berhasil diperbarui!');
                window.location.href='dashboard_admin.php?page=youtube_channel';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<div class="container px-4">
    <h3 class="mb-4">Edit Video YouTube</h3>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Judul Video</label>
            <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($data['judul']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Link Embed / URL</label>
            <input type="text" name="link" class="form-control" value="<?= htmlspecialchars($data['link']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal Upload</label>
            <input type="datetime-local" name="tanggal_upload" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($data['tanggal_upload'])) ?>" required>
        </div>
        <button type="submit" name="submit" class="btn btn-warning">Update Video</button>
        <a href="dashboard_admin.php?page=youtube_channel" class="btn btn-secondary">Batal</a>
    </form>
</div>

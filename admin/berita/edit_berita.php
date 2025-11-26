<?php
include '../confiq/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>alert('ID tidak ditemukan!'); window.location.href='dashboard_admin.php?page=data_berita';</script>";
    exit;
}

// Ambil data berita
$query = mysqli_query($conn, "SELECT * FROM berita WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='dashboard_admin.php?page=data_berita';</script>";
    exit;
}

// Proses update
if (isset($_POST['update'])) {
    $judul          = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi            = mysqli_real_escape_string($conn, $_POST['isi']);
    $penulis        = mysqli_real_escape_string($conn, $_POST['penulis']);
    $tanggal_terbit = mysqli_real_escape_string($conn, $_POST['tanggal_terbit']);
    $tautan         = mysqli_real_escape_string($conn, $_POST['tautan']);
    $gambarLama     = $_POST['gambar_lama'];
    $tanggal_input  = date('Y-m-d H:i:s');

    // Proses gambar
    $gambarBaru = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    $uploadDir = "../upload/berita/";
    $namaFileBaru = '';

    if (!empty($gambarBaru)) {
        $ext = pathinfo($gambarBaru, PATHINFO_EXTENSION);
        $namaFileBaru = uniqid('berita_') . '.' . $ext;
        move_uploaded_file($tmp, $uploadDir . $namaFileBaru);

        // Hapus gambar lama
        if ($gambarLama && file_exists($uploadDir . $gambarLama)) {
            unlink($uploadDir . $gambarLama);
        }
    } else {
        $namaFileBaru = $gambarLama;
    }

    // Update berita
    $update = mysqli_query($conn, "UPDATE berita SET 
        judul = '$judul',
        isi = '$isi',
        gambar = '$namaFileBaru',
        penulis = '$penulis',
        tanggal_terbit = '$tanggal_terbit',
        tautan = '$tautan',
        tanggal_input = '$tanggal_input'
        WHERE id = '$id'");
    if ($update) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
        Swal.fire({
            icon: 'success',
            title: '✅ Berita Diperbarui!',
            text: 'Data berita berhasil diubah.',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'dashboard_admin.php?page=data_berita';
            }
        });
    </script>";
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal Update!',
            text: '" . mysqli_error($conn) . "',
            confirmButtonColor: '#d33'
        });
    </script>";
    }
}
?>

<!-- Header -->
<div class="bg-primary text-white px-5 pt-5 pb-4 mb-4 shadow" style="border-radius: 0 0 40px 40px; margin-top: -60px; min-height: 150px;">
    <h3 class="fw-semibold mb-1" style="font-size: 1.5rem; margin-top: 20px;">Edit Berita</h3>
    <p class="mb-0">Perbarui informasi berita PIK-R.</p>
</div>

<!-- Form Edit -->
<div class="container px-4 pb-5">
    <div class="card shadow border-0 rounded-4">
        <div class="card-body px-4 py-4">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="gambar_lama" value="<?= htmlspecialchars($data['gambar']) ?>">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Judul Berita</label>
                    <input type="text" name="judul" value="<?= htmlspecialchars($data['judul']) ?>" class="form-control shadow-sm" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Isi Berita</label>
                    <textarea name="isi" class="form-control shadow-sm" rows="6" required><?= htmlspecialchars($data['isi']) ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Penulis</label>
                        <input type="text" name="penulis" value="<?= htmlspecialchars($data['penulis']) ?>" class="form-control shadow-sm">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Tanggal Terbit</label>
                        <input type="datetime-local" name="tanggal_terbit" value="<?= date('Y-m-d\TH:i', strtotime($data['tanggal_terbit'])) ?>" class="form-control shadow-sm" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Tautan (opsional)</label>
                    <input type="url" name="tautan" value="<?= htmlspecialchars($data['tautan']) ?>" class="form-control shadow-sm">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Gambar Baru (opsional)</label>
                        <input type="file" name="gambar" accept="image/*" class="form-control shadow-sm">
                        <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Gambar Saat Ini</label><br>
                        <?php if ($data['gambar']) : ?>
                            <img src="../upload/berita/<?= htmlspecialchars($data['gambar']) ?>" class="img-fluid rounded shadow" style="max-height: 100px;">
                        <?php else : ?>
                            <span class="text-muted">Belum ada gambar</span>
                        <?php endif; ?>
                    </div>
                </div>

                <button type="submit" name="update" class="btn btn-primary fw-semibold shadow-sm px-4">
                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                </button>
                <a href="dashboard_admin.php?page=data_berita" class="btn btn-secondary shadow-sm">Kembali</a>
            </form>
        </div>
    </div>
</div>
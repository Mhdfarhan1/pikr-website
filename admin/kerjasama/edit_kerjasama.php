<?php
include '../confiq/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>alert('ID tidak ditemukan!'); window.location.href='dashboard_admin.php?page=data_kerjasama';</script>";
    exit;
}

// Ambil data kerja sama dari database
$query = mysqli_query($conn, "SELECT * FROM kerja_sama WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='dashboard_admin.php?page=data_kerjasama';</script>";
    exit;
}

// Proses update
if (isset($_POST['update'])) {
    $nama_instansi = mysqli_real_escape_string($conn, $_POST['nama_instansi']);
    $deskripsi     = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $urutan        = (int) $_POST['urutan'];
    $status        = mysqli_real_escape_string($conn, $_POST['status']);
    $gambarLama    = $_POST['gambar_lama'];
    $tanggal_input = date('Y-m-d H:i:s');

    $gambarBaru = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    $uploadDir = "../upload/kerjasama/";
    $namaFileBaru = '';

    if (!empty($gambarBaru)) {
        $ext = pathinfo($gambarBaru, PATHINFO_EXTENSION);
        $namaFileBaru = uniqid('kerjasama_') . '.' . $ext;
        move_uploaded_file($tmp, $uploadDir . $namaFileBaru);

        // Hapus gambar lama jika ada
        if ($gambarLama && file_exists($uploadDir . $gambarLama)) {
            unlink($uploadDir . $gambarLama);
        }
    } else {
        $namaFileBaru = $gambarLama;
    }

    $update = mysqli_query($conn, "UPDATE kerja_sama SET 
        nama_instansi = '$nama_instansi',
        deskripsi = '$deskripsi',
        gambar = '$namaFileBaru',
        urutan = '$urutan',
        status = '$status',
        tanggal_input = '$tanggal_input'
        WHERE id = '$id'");

    if ($update) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data kerja sama berhasil diperbarui!',
            showConfirmButton: false,
            timer: 2000
        }).then(() => {
            window.location.href = 'dashboard_admin.php?page=data_kerjasama';
        });
    </script>";
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            html: 'Gagal mengupdate data:<br><small>" . mysqli_error($conn) . "</small>'
        }).then(() => {
            window.history.back();
        });
    </script>";
    }
}
?>

<!-- Header -->
<div class="bg-primary text-white px-5 pt-5 pb-4 mb-4 shadow" style="border-radius: 0 0 40px 40px; margin-top: -60px; min-height: 150px;">
    <h3 class="fw-semibold mb-1" style="font-size: 1.5rem; margin-top: 20px;">Edit Kerja Sama</h3>
    <p class="mb-0">Perbarui informasi kerja sama PIK-R.</p>
</div>

<!-- Form -->
<div class="container px-4 pb-5">
    <div class="card shadow border-0 rounded-4">
        <div class="card-body px-4 py-4">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="gambar_lama" value="<?= htmlspecialchars($data['gambar']) ?>">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Instansi</label>
                    <input type="text" name="nama_instansi" value="<?= htmlspecialchars($data['nama_instansi']) ?>" required class="form-control shadow-sm">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control shadow-sm" rows="4" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Urutan</label>
                        <input type="number" name="urutan" value="<?= (int) $data['urutan'] ?>" class="form-control shadow-sm">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select shadow-sm">
                            <option value="aktif" <?= $data['status'] == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="nonaktif" <?= $data['status'] == 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
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
                            <img src="../upload/kerjasama/<?= htmlspecialchars($data['gambar']) ?>" alt="Gambar" class="img-fluid rounded shadow" style="max-height: 100px;">
                        <?php else : ?>
                            <span class="text-muted">Belum ada gambar</span>
                        <?php endif; ?>
                    </div>
                </div>

                <button type="submit" name="update" class="btn btn-primary fw-semibold shadow-sm px-4">
                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                </button>
                <a href="dashboard_admin.php?page=data_kerjasama" class="btn btn-secondary shadow-sm">Kembali</a>
            </form>
        </div>
    </div>
</div>
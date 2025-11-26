<?php
include '../confiq/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>alert('ID tidak ditemukan!'); window.location.href='dashboard_admin.php?page=data_struktur';</script>";
    exit;
}

$data = mysqli_query($conn, "SELECT * FROM struktur_organisasi WHERE id = '$id'");
$row = mysqli_fetch_assoc($data);
if (!$row) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='dashboard_admin.php?page=data_struktur';</script>";
    exit;
}

if (isset($_POST['update'])) {
    $nama       = $_POST['nama'];
    $jabatan    = $_POST['jabatan'];
    $deskripsi  = $_POST['deskripsi'];
    $urutan     = $_POST['urutan'];
    $status     = $_POST['status'];
    $fotoLama   = $_POST['foto_lama'];

    $fotoBaru   = $_FILES['foto']['name'];
    $tmp        = $_FILES['foto']['tmp_name'];
    $path       = "../upload/struktur/";

    if (!empty($fotoBaru)) {
        $ext = pathinfo($fotoBaru, PATHINFO_EXTENSION);
        $nama_foto = 'struktur_' . time() . '.' . $ext;
        move_uploaded_file($tmp, $path . $nama_foto);

        // Hapus foto lama jika ada
        if ($fotoLama && file_exists($path . $fotoLama)) {
            unlink($path . $fotoLama);
        }
    } else {
        $nama_foto = $fotoLama;
    }

    $update = mysqli_query($conn, "UPDATE struktur_organisasi SET 
        nama = '$nama',
        jabatan = '$jabatan',
        deskripsi = '$deskripsi',
        urutan = '$urutan',
        status = '$status',
        foto = '$nama_foto'
        WHERE id = '$id'");

    if ($update) {
        echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data berhasil diperbarui!',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = '../dashboard_admin.php?page=data_struktur';
        });
    </script>";
    } else {
        echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Gagal mengupdate data!',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Kembali'
        });
    </script>";
    }
}
?>

<!-- Header -->
<div class="bg-primary text-white px-5 pt-5 pb-4 mb-4 shadow" style="border-radius: 0 0 40px 40px; margin-top: -60px; min-height: 150px;">
    <h3 class="fw-semibold mb-1" style="font-size: 1.5rem; margin-top: 20px;">Edit Struktur Organisasi</h3>
    <p class="mb-0">Perbarui data struktur PIK-R.</p>
</div>

<!-- Form -->
<div class="container px-4 pb-5">
    <div class="card border-0 shadow rounded-4">
        <div class="card-body px-4 py-4">

            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="foto_lama" value="<?= htmlspecialchars($row['foto']) ?>">
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nama</label>
                        <input type="text" name="nama" value="<?= htmlspecialchars($row['nama']) ?>" required class="form-control shadow-sm">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Jabatan</label>
                        <input type="text" name="jabatan" value="<?= htmlspecialchars($row['jabatan']) ?>" required class="form-control shadow-sm">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control shadow-sm" rows="3"><?= htmlspecialchars($row['deskripsi']) ?></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Urutan</label>
                        <input type="number" name="urutan" value="<?= $row['urutan'] ?>" class="form-control shadow-sm">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select shadow-sm">
                            <option value="aktif" <?= $row['status'] == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="nonaktif" <?= $row['status'] == 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Foto Baru (opsional)</label>
                        <input type="file" name="foto" accept="image/*" class="form-control shadow-sm">
                        <small class="text-muted">Abaikan jika tidak ingin mengubah foto.</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Foto Saat Ini</label><br>
                        <?php if ($row['foto']): ?>
                            <img src="../upload/struktur/<?= htmlspecialchars($row['foto']) ?>" alt="Foto" class="img-fluid rounded shadow" style="max-height: 100px;">
                        <?php else: ?>
                            <span class="text-muted">Belum ada foto</span>
                        <?php endif; ?>
                    </div>
                </div>

                <button type="submit" name="update" class="btn btn-primary fw-semibold shadow-sm px-4">
                    <i class="bi bi-save"></i> Simpan Perubahan
                </button>
                <a href="dashboard_admin.php?page=data_struktur" class="btn btn-secondary shadow-sm">Kembali</a>
            </form>

        </div>
    </div>
</div>
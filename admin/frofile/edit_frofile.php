<?php
include '../confiq/koneksi.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    echo "<script>alert('ID frofile tidak valid'); window.location.href='dashboard_admin.php?page=data_frofile';</script>";
    exit;
}

// Ambil data frofile lama
$sql = "SELECT * FROM frofile WHERE id = $id LIMIT 1";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('Data frofile tidak ditemukan'); window.location.href='dashboard_admin.php?page=data_frofile';</script>";
    exit;
}
$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $fotoLama = $row['foto'];

    if (empty($nama) || empty($jabatan) || empty($deskripsi)) {
        echo "<script>alert('Pastikan semua data wajib diisi!'); window.history.back();</script>";
        exit;
    }

    // Proses upload foto baru jika ada
    $newFileName = $fotoLama; // default tetap foto lama
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['foto']['tmp_name'];
        $fileName = $_FILES['foto']['name'];
        $fileSize = $_FILES['foto']['size'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileExtension, $allowedfileExtensions)) {
            echo "<script>alert('Format file tidak diizinkan. Gunakan jpg, jpeg, png, gif'); window.history.back();</script>";
            exit;
        }
        if ($fileSize > 2 * 1024 * 1024) {
            echo "<script>alert('Ukuran file terlalu besar, maksimal 2MB'); window.history.back();</script>";
            exit;
        }

        // Upload folder
        $uploadFileDir = '../assets/img/frofile/';
        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0755, true);
        }

        $newFileName = uniqid('frofile_', true) . '.' . $fileExtension;
        $dest_path = $uploadFileDir . $newFileName;

        if (!move_uploaded_file($fileTmpPath, $dest_path)) {
            echo "<script>alert('Gagal mengupload foto'); window.history.back();</script>";
            exit;
        }

        // Hapus foto lama jika ada dan bukan default placeholder
        if ($fotoLama && file_exists($uploadFileDir . $fotoLama)) {
            unlink($uploadFileDir . $fotoLama);
        }
    }

    // Update data ke DB
    $updateSql = "UPDATE frofile SET
        nama = '$nama',
        jabatan = '$jabatan',
        deskripsi = '$deskripsi',
        foto = '$newFileName'
        WHERE id = $id";

    if (mysqli_query($conn, $updateSql)) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data frofile berhasil diupdate.',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
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
            title: 'Gagal!',
            text: 'Data tidak berhasil diupdate.',
            confirmButtonColor: '#d33'
        }).then(() => {
            window.history.back();
        });
    </script>";
        exit;
    }
}
?>

<!-- Header -->
<div class="bg-primary text-white px-4 px-md-5 pt-5 pb-4 shadow" style="border-radius: 0 0 40px 40px; min-height: 170px;">
    <div class="container">
        <h3 class="fw-semibold mb-1" style="font-size: 1.6rem;">Edit Frofile</h3>
        <p class="mb-0">Ubah data kepala sekolah / pembina pada sistem PIK-R.</p>
    </div>
</div>

<!-- Form -->
<div class="container px-3 px-md-5 pb-5" style="margin-top: -30px;">
    <div class="card border-0 shadow-sm mx-auto" style="max-width: 700px;">
        <div class="card-body px-4 py-4">
            <form method="POST" enctype="multipart/form-data" novalidate>
                <h5 class="fw-semibold text-center mb-4 text-primary">Form Edit Frofile</h5>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($row['nama']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Jabatan</label>
                    <input type="text" name="jabatan" class="form-control" value="<?= htmlspecialchars($row['jabatan']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" rows="4" required><?= htmlspecialchars($row['deskripsi']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Foto Saat Ini</label><br>
                    <?php if ($row['foto'] && file_exists('../assets/img/frofile/' . $row['foto'])) : ?>
                        <img src="../assets/img/frofile/<?= htmlspecialchars($row['foto']) ?>" alt="Foto Frofile" style="max-width:150px; border-radius:8px; box-shadow: 0 0 10px rgba(0,0,0,0.15);">
                    <?php else: ?>
                        <p><i>Tidak ada foto</i></p>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Ganti Foto (opsional)</label>
                    <input class="form-control" type="file" name="foto" accept=".jpg,.jpeg,.png,.gif">
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah foto. Maksimal ukuran 2MB.</small>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="dashboard_admin.php?page=data_frofile" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
include '../confiq/koneksi.php';
date_default_timezone_set('Asia/Jakarta'); // pastikan waktu lokal Indonesia

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    echo "<script>alert('ID pengumuman tidak valid'); window.location.href='dashboard_admin.php?page=data_pengumuman';</script>";
    exit;
}

$sql = "SELECT * FROM pengumuman WHERE id = $id LIMIT 1";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('Data pengumuman tidak ditemukan'); window.location.href='dashboard_admin.php?page=data_pengumuman';</script>";
    exit;
}
$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi = mysqli_real_escape_string($conn, $_POST['isi']);
    $tautan = mysqli_real_escape_string($conn, $_POST['tautan']);
    $tanggal = date('Y-m-d H:i:s');
    $gambarLama = $row['gambar'];

    if (empty($judul) || empty($isi)) {
        echo "<script>alert('Judul dan isi wajib diisi!'); window.history.back();</script>";
        exit;
    }

    $newFileName = $gambarLama;

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['gambar']['tmp_name'];
        $fileName = $_FILES['gambar']['name'];
        $fileSize = $_FILES['gambar']['size'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($fileExtension, $allowedExt)) {
            echo "<script>alert('Format gambar tidak valid. Gunakan jpg/jpeg/png/gif'); window.history.back();</script>";
            exit;
        }

        if ($fileSize > 2 * 1024 * 1024) {
            echo "<script>alert('Ukuran gambar maksimal 2MB'); window.history.back();</script>";
            exit;
        }

        $uploadDir = '../upload/pengumuman/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $newFileName = uniqid('pengumuman_', true) . '.' . $fileExtension;
        $destPath = $uploadDir . $newFileName;

        if (!move_uploaded_file($fileTmpPath, $destPath)) {
            echo "<script>alert('Gagal mengupload gambar'); window.history.back();</script>";
            exit;
        }

        // Hapus gambar lama jika ada
        if (!empty($gambarLama) && file_exists($uploadDir . $gambarLama)) {
            unlink($uploadDir . $gambarLama);
        }
    }

    $updateSql = "UPDATE pengumuman SET 
                    judul = '$judul',
                    isi = '$isi',
                    tautan = '$tautan',
                    tanggal = '$tanggal',
                    gambar = '$newFileName'
                  WHERE id = $id";

    if (mysqli_query($conn, $updateSql)) {
        echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data pengumuman berhasil diperbarui',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'dashboard_admin.php?page=data_pengumuman';
            }
        });
    </script>";
        exit;
    } else {
        echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Gagal memperbarui data',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Coba Lagi'
        }).then((result) => {
            if (result.isConfirmed) {
                window.history.back();
            }
        });
    </script>";
        exit;
    }
}
?>

<!-- Header -->
<div class="bg-primary text-white px-4 px-md-5 pt-5 pb-4 shadow" style="border-radius: 0 0 40px 40px; min-height: 170px;">
    <div class="container">
        <h3 class="fw-semibold mb-1" style="font-size: 1.6rem;">Edit Pengumuman</h3>
        <p class="mb-0">Ubah informasi pengumuman di sistem PIK-R.</p>
    </div>
</div>

<!-- Form -->
<div class="container px-3 px-md-5 pb-5" style="margin-top: -30px;">
    <div class="card border-0 shadow-sm mx-auto" style="max-width: 700px;">
        <div class="card-body px-4 py-4">
            <form method="POST" enctype="multipart/form-data" novalidate>
                <h5 class="fw-semibold text-center mb-4 text-primary">Form Edit Pengumuman</h5>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Judul Pengumuman</label>
                    <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($row['judul']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Isi Pengumuman</label>
                    <textarea class="form-control" name="isi" rows="5" required><?= htmlspecialchars($row['isi']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Tautan (Opsional)</label>
                    <input type="url" name="tautan" class="form-control" value="<?= htmlspecialchars($row['tautan']) ?>" placeholder="https://contoh.link/pengumuman">
                    <small class="text-muted">Kosongkan jika tidak ingin menampilkan tautan.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Gambar Saat Ini</label><br>
                    <?php if ($row['gambar'] && file_exists('../upload/pengumuman/' . $row['gambar'])) : ?>
                        <img src="../upload/pengumuman/<?= htmlspecialchars($row['gambar']) ?>" alt="Gambar" style="max-width: 150px;" class="rounded shadow-sm">
                    <?php else : ?>
                        <p class="text-muted"><i>Tidak ada gambar</i></p>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Ganti Gambar (Opsional)</label>
                    <input type="file" name="gambar" class="form-control" accept=".jpg,.jpeg,.png,.gif">
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar. Maksimal 2MB.</small>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="dashboard_admin.php?page=data_pengumuman" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
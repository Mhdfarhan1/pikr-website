<?php
include '../confiq/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    echo "<script>alert('ID kegiatan tidak valid'); window.location.href='dashboard_admin.php?page=data_kegiatan';</script>";
    exit;
}

$sql = "SELECT * FROM kegiatan WHERE id = $id LIMIT 1";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('Data kegiatan tidak ditemukan'); window.location.href='dashboard_admin.php?page=data_kegiatan';</script>";
    exit;
}
$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul      = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi  = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $tempat     = mysqli_real_escape_string($conn, $_POST['tempat']);
    $tanggal_kegiatan = mysqli_real_escape_string($conn, $_POST['tanggal_kegiatan']);
    $tautan     = mysqli_real_escape_string($conn, $_POST['tautan']);
    $gambar_lama = $row['gambar'];
    $tanggal_input = date('Y-m-d H:i:s');
    $gambar_baru = $gambar_lama;

    if (empty($judul) || empty($deskripsi) || empty($tempat) || empty($tanggal_kegiatan)) {
        echo "<script>alert('Semua data wajib diisi!'); window.history.back();</script>";
        exit;
    }

    // Upload gambar jika ada
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $tmp_path = $_FILES['gambar']['tmp_name'];
        $name = $_FILES['gambar']['name'];
        $size = $_FILES['gambar']['size'];
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($ext, $allowed)) {
            echo "<script>alert('Format gambar tidak valid'); window.history.back();</script>";
            exit;
        }

        if ($size > 10 * 1024 * 1024) {
            echo "<script>alert('Ukuran gambar maksimal 10MB'); window.history.back();</script>";
            exit;
        }

        $uploadDir = '../upload/kegiatan/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
       $cleanName = preg_replace("/[^a-zA-Z0-9\.\-_]/", "_", pathinfo($name, PATHINFO_FILENAME));
        $newFileName = uniqid('kegiatan_', true) . '_' . $cleanName . '.' . $ext;

        $destPath = $uploadDir . $newFileName;

        if (move_uploaded_file($tmp_path, $destPath)) {
            // hapus gambar lama jika ada
            if ($gambar_lama && file_exists($uploadDir . $gambar_lama)) {
                unlink($uploadDir . $gambar_lama);
            }
            $gambar_baru = $newFileName;
        } else {
            echo "<script>alert('Gagal upload gambar'); window.history.back();</script>";
            exit;
        }
    }

    $update = "UPDATE kegiatan SET 
                judul = '$judul',
                deskripsi = '$deskripsi',
                tempat = '$tempat',
                tanggal_kegiatan = '$tanggal_kegiatan',
                tautan = '$tautan',
                gambar = '$gambar_baru',
                tanggal_input = '$tanggal_input'
                WHERE id = $id";

    if (mysqli_query($conn, $update)) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
        Swal.fire({
            title: 'Berhasil!',
            text: 'Kegiatan berhasil diperbaharui.',
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6',
            draggable: true
        }).then(() => {
            window.location.href = 'dashboard_admin.php?page=data_kegiatan';
        });
    </script>";
    } else {
        echo "<script>
        Swal.fire({
            title: 'Gagal!',
            text: 'Gagal memperbarui kegiatan.',
            icon: 'error',
            confirmButtonText: 'Coba Lagi',
            confirmButtonColor: '#d33'
        }).then(() => {
            window.history.back();
        });
    </script>";
    }
}
?>

<!-- Tampilan Form -->
<div class="bg-primary text-white px-4 px-md-5 pt-5 pb-4 shadow" style="border-radius: 0 0 40px 40px; min-height: 170px;">
    <div class="container">
        <h3 class="fw-semibold mb-1" style="font-size: 1.6rem;">Edit Kegiatan</h3>
        <p class="mb-0">Perbarui data kegiatan organisasi di sistem PIK-R.</p>
    </div>
</div>

<div class="container px-3 px-md-5 pb-5" style="margin-top: -30px;">
    <div class="card border-0 shadow-sm mx-auto" style="max-width: 700px;">
        <div class="card-body px-4 py-4">
            <form method="POST" enctype="multipart/form-data" novalidate>
                <h5 class="fw-semibold text-center mb-4 text-primary">Form Edit Kegiatan</h5>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Judul Kegiatan</label>
                    <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($row['judul']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="4" required><?= htmlspecialchars($row['deskripsi']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Tempat</label>
                    <input type="text" name="tempat" class="form-control" value="<?= htmlspecialchars($row['tempat']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Tanggal Kegiatan</label>
                    <input type="datetime-local" name="tanggal_kegiatan" class="form-control"
                        value="<?= date('Y-m-d\TH:i', strtotime($row['tanggal_kegiatan'])) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Tautan (Opsional)</label>
                    <input type="url" name="tautan" class="form-control" value="<?= htmlspecialchars($row['tautan']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Gambar Saat Ini</label><br>
                    <?php if (!empty($row['gambar']) && file_exists("../upload/kegiatan/{$row['gambar']}")): ?>
                        <img src="../upload/kegiatan/<?= $row['gambar'] ?>" style="max-width:150px;" class="rounded shadow-sm">
                    <?php else: ?>
                        <i class="text-muted">Tidak ada gambar</i>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Ganti Gambar (Opsional)</label>
                    <input type="file" name="gambar" class="form-control" accept=".jpg,.jpeg,.png,.gif">
                    <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar. Maksimal 10MB.</small>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="dashboard_admin.php?page=data_kegiatan" class="btn btn-secondary">
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
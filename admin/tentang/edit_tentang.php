<?php
include '../confiq/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

// Ambil ID dari URL
$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>alert('ID tidak ditemukan!'); window.location.href='dashboard_admin.php?page=data_tentang';</script>";
    exit;
}

// Ambil data lama
$query = mysqli_query($conn, "SELECT * FROM tentang_pikr WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='dashboard_admin.php?page=data_tentang';</script>";
    exit;
}

$kategori_aktif = $data['kategori'];

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $tanggal_input = date('Y-m-d H:i:s');

    $gambar = $data['gambar']; // default gambar lama

    // Cek apakah upload baru
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['gambar']['tmp_name'];
        $fileName = $_FILES['gambar']['name'];
        $fileSize = $_FILES['gambar']['size'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($ext, $allowed)) {
            echo "<script>alert('Format file tidak diizinkan!'); window.history.back();</script>";
            exit;
        }

        if ($fileSize > 10 * 1024 * 1024) {
            echo "<script>alert('Ukuran gambar maksimal 10MB!'); window.history.back();</script>";
            exit;
        }

        $newName = uniqid('tentang_', true) . '.' . $ext;
        $uploadDir = '../upload/tentang/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $dest = $uploadDir . $newName;

        if (!move_uploaded_file($fileTmp, $dest)) {
            echo "<script>alert('Gagal upload gambar!'); window.history.back();</script>";
            exit;
        }

        // Hapus gambar lama jika ada
        if (!empty($data['gambar']) && file_exists($uploadDir . $data['gambar'])) {
            unlink($uploadDir . $data['gambar']);
        }

        $gambar = $newName;
    }

    $update = mysqli_query($conn, "UPDATE tentang_pikr 
        SET kategori='$kategori', judul='$judul', deskripsi='$deskripsi', gambar='$gambar', tanggal_input='$tanggal_input'
        WHERE id='$id'");

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
            window.location.href = 'dashboard_admin.php?page=data_tentang&kategori=$kategori';
        });
    </script>";
    } else {
        echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Gagal update data!',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Kembali'
        }).then(() => {
            window.history.back();
        });
    </script>";
    }
}
?>

<!-- Header -->
<div class="bg-primary text-white px-4 px-md-5 pt-5 pb-4 shadow" style="border-radius: 0 0 40px 40px; min-height: 170px;">
    <div class="container">
        <h3 class="fw-semibold mb-1" style="font-size: 1.6rem;">Edit Data <?= ucwords($kategori_aktif) ?></h3>
        <p class="mb-0">Perbarui informasi data <?= $kategori_aktif ?> sesuai kebutuhan.</p>
    </div>
</div>

<!-- Form -->
<div class="container px-3 px-md-5 pb-5" style="margin-top: -30px; font-family: 'Roboto', sans-serif;">
    <div class="card border-0 shadow-sm mx-auto" style="max-width: 700px;">
        <div class="card-body px-4 py-4">
            <form method="POST" enctype="multipart/form-data">
                <h5 class="fw-semibold text-center mb-4 text-primary">Form Edit <?= ucwords($kategori_aktif) ?> PIK-R</h5>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kategori</label>
                    <select name="kategori" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="profil" <?= $kategori_aktif == 'profil' ? 'selected' : '' ?>>Profil</option>
                        <option value="prestasi" <?= $kategori_aktif == 'prestasi' ? 'selected' : '' ?>>Prestasi</option>
                        <option value="fasilitas" <?= $kategori_aktif == 'fasilitas' ? 'selected' : '' ?>>Fasilitas</option>
                        <option value="galeri" <?= $kategori_aktif == 'galeri' ? 'selected' : '' ?>>Galeri</option>
                        <option value="tentang" <?= $kategori_aktif == 'tentang' ? 'selected' : '' ?>>Tentang</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Judul</label>
                    <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($data['judul']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="5"><?= htmlspecialchars($data['deskripsi']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Gambar (Opsional)</label>
                    <input type="file" name="gambar" class="form-control" accept=".jpg,.jpeg,.png,.gif">
                    <?php if ($data['gambar']) : ?>
                        <div class="mt-2">
                            <img src="../upload/tentang/<?= $data['gambar'] ?>" width="100" class="rounded shadow-sm">
                            <p class="text-muted small mt-1">Gambar saat ini</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="dashboard_admin.php?page=data_tentang&kategori=<?= $kategori_aktif ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
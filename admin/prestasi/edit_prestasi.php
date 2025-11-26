<?php
include '../confiq/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

// Ambil ID dari URL
$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>alert('ID tidak ditemukan'); window.location.href='dashboard_admin.php?page=data_prestasi';</script>";
    exit;
}

// Ambil data lama
$result = mysqli_query($conn, "SELECT * FROM prestasi WHERE id = '$id'");
$data = mysqli_fetch_assoc($result);
if (!$data) {
    echo "<script>alert('Data tidak ditemukan'); window.location.href='dashboard_admin.php?page=data_prestasi';</script>";
    exit;
}

// Proses update jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul     = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $tanggal_input = date('Y-m-d H:i:s');

    // Validasi
    if (empty($judul) || empty($deskripsi)) {
        echo "<script>alert('Judul dan Deskripsi wajib diisi'); window.history.back();</script>";
        exit;
    }

    // Cek apakah ada gambar baru
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $tmp     = $_FILES['gambar']['tmp_name'];
        $nama    = $_FILES['gambar']['name'];
        $ukuran  = $_FILES['gambar']['size'];
        $ext     = strtolower(pathinfo($nama, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($ext, $allowed)) {
            echo "<script>alert('Format gambar tidak valid'); window.history.back();</script>";
            exit;
        }

        if ($ukuran > 5 * 1024 * 1024) {
            echo "<script>alert('Ukuran gambar maksimal 5MB'); window.history.back();</script>";
            exit;
        }

        $uploadDir = '../upload/prestasi/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $gambarBaru = uniqid('prestasi_', true) . '.' . $ext;
        $path = $uploadDir . $gambarBaru;

        // Upload gambar baru
        if (move_uploaded_file($tmp, $path)) {
            // Hapus gambar lama
            if (!empty($data['gambar']) && file_exists('../' . $data['gambar'])) {
                unlink('../' . $data['gambar']);
            }
            $gambar = 'upload/prestasi/' . $gambarBaru;
        } else {
            echo "<script>alert('Gagal mengupload gambar'); window.history.back();</script>";
            exit;
        }
    } else {
        $gambar = $data['gambar']; // Gunakan gambar lama
    }

    // Update DB
    $query = "UPDATE prestasi SET judul = '$judul', deskripsi = '$deskripsi', gambar = '$gambar', tanggal_input = '$tanggal_input' WHERE id = '$id'";

    if (mysqli_query($conn, $query)) {
        echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data prestasi berhasil diperbarui.',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'dashboard_admin.php?page=data_prestasi';
            }
        });
    </script>";
    } else {
        echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Gagal menyimpan perubahan.',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Coba Lagi'
        }).then((result) => {
            if (result.isConfirmed) {
                window.history.back();
            }
        });
    </script>";
    }
}
?>

<!-- Header -->
<div class="bg-primary text-white px-4 px-md-5 pt-5 pb-4 shadow" style="border-radius: 0 0 40px 40px; min-height: 170px;">
    <div class="container">
        <h3 class="fw-semibold mb-1" style="font-size: 1.6rem;">Edit Prestasi</h3>
        <p class="mb-0">Perbarui data prestasi siswa atau organisasi PIK-R.</p>
    </div>
</div>

<!-- Form -->
<div class="container px-3 px-md-5 pb-5" style="margin-top: -30px;">
    <div class="card border-0 shadow-sm mx-auto" style="max-width: 700px;">
        <div class="card-body px-4 py-4">
            <form method="POST" enctype="multipart/form-data" novalidate>
                <h5 class="fw-semibold text-center mb-4 text-primary">Form Edit Prestasi</h5>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Judul Prestasi</label>
                    <input type="text" name="judul" class="form-control" required value="<?= htmlspecialchars($data['judul']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="4" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Gambar Saat Ini</label><br>
                    <?php if (!empty($data['gambar']) && file_exists('../' . $data['gambar'])) : ?>
                        <img src="../<?= $data['gambar'] ?>" width="100" class="img-thumbnail shadow-sm mb-2">
                    <?php else : ?>
                        <p class="text-muted">Tidak ada gambar.</p>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Upload Gambar Baru (opsional)</label>
                    <input type="file" name="gambar" class="form-control" accept=".jpg,.jpeg,.png,.gif">
                    <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="dashboard_admin.php?page=data_prestasi" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
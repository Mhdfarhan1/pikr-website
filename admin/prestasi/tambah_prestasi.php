<?php
include '../confiq/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $tanggal_input = date('Y-m-d H:i:s');
    $gambar = '';

    // Validasi input
    if (empty($judul)) {
        echo "<script>alert('Judul wajib diisi'); window.history.back();</script>";
        exit;
    }

    if (empty($deskripsi)) {
        echo "<script>alert('Deskripsi wajib diisi'); window.history.back();</script>";
        exit;
    }

    // Upload gambar
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

        if (move_uploaded_file($tmp, $path)) {
            $gambar = 'upload/prestasi/' . $gambarBaru;
        } else {
            echo "<script>alert('Gagal mengupload gambar'); window.history.back();</script>";
            exit;
        }
    } else {
        echo "<script>alert('Gambar wajib diunggah'); window.history.back();</script>";
        exit;
    }

    // Simpan ke DB
    $query = "INSERT INTO prestasi (judul, deskripsi, gambar, tanggal_input)
              VALUES ('$judul', '$deskripsi', '$gambar', '$tanggal_input')";

    if (mysqli_query($conn, $query)) {
        echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data prestasi berhasil ditambahkan.',
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
            text: 'Gagal menyimpan prestasi.',
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
        <h3 class="fw-semibold mb-1" style="font-size: 1.6rem;">Tambah Prestasi</h3>
        <p class="mb-0">Unggah prestasi siswa atau organisasi PIK-R.</p>
    </div>
</div>

<!-- Form -->
<div class="container px-3 px-md-5 pb-5" style="margin-top: -30px;">
    <div class="card border-0 shadow-sm mx-auto" style="max-width: 700px;">
        <div class="card-body px-4 py-4">
            <form method="POST" enctype="multipart/form-data" novalidate>
                <h5 class="fw-semibold text-center mb-4 text-primary">Form Tambah Prestasi</h5>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Judul Prestasi</label>
                    <input type="text" name="judul" class="form-control" required placeholder="Contoh: Juara 1 Duta Genre">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="4" placeholder="Deskripsi singkat prestasi..." required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Upload Gambar</label>
                    <input type="file" name="gambar" class="form-control" required accept=".jpg,.jpeg,.png,.gif">
                    <small class="text-muted">Format: JPG, JPEG, PNG, GIF. Maksimal 5MB.</small>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="dashboard_admin.php?page=data_prestasi" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
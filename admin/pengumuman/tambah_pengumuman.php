<?php
include '../confiq/koneksi.php';
date_default_timezone_set('Asia/Jakarta'); // Penting agar waktu sesuai WIB

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul   = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi     = mysqli_real_escape_string($conn, $_POST['isi']);
    $tautan  = mysqli_real_escape_string($conn, $_POST['tautan']);
    $tanggal = date('Y-m-d H:i:s');
    $gambar  = '';

    if (empty($judul) || empty($isi)) {
        echo "<script>alert('Judul dan isi wajib diisi!'); window.history.back();</script>";
        exit;
    }

    // Upload gambar
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath    = $_FILES['gambar']['tmp_name'];
        $fileName       = $_FILES['gambar']['name'];
        $fileSize       = $_FILES['gambar']['size'];
        $fileExtension  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExt     = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($fileExtension, $allowedExt)) {
            echo "<script>alert('Format file tidak diizinkan!'); window.history.back();</script>";
            exit;
        }

        if ($fileSize > 10 * 1024 * 1024) {
            echo "<script>alert('Ukuran file maksimal 10MB!'); window.history.back();</script>";
            exit;
        }

        $newFileName = uniqid('pengumuman_', true) . '.' . $fileExtension;
        $uploadDir   = '../upload/pengumuman/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $destPath = $uploadDir . $newFileName;

        if (!move_uploaded_file($fileTmpPath, $destPath)) {
            echo "<script>alert('Gagal upload gambar!'); window.history.back();</script>";
            exit;
        }

        $gambar = $newFileName;
    }

    // Simpan ke database
    $query = "INSERT INTO pengumuman (judul, isi, tautan, tanggal, gambar) 
              VALUES ('$judul', '$isi', '$tautan', '$tanggal', '$gambar')";

    if (mysqli_query($conn, $query)) {
        echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Pengumuman berhasil ditambahkan!',
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
            text: 'Gagal menyimpan ke database!',
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
        <h3 class="fw-semibold mb-1" style="font-size: 1.6rem;">Tambah Pengumuman</h3>
        <p class="mb-0">Masukkan informasi pengumuman terbaru untuk ditampilkan ke publik.</p>
    </div>
</div>

<!-- Form Tambah Pengumuman -->
<div class="container px-3 px-md-5 pb-5" style="margin-top: -30px; font-family: 'Roboto', sans-serif;">
    <div class="card border-0 shadow-sm mx-auto" style="max-width: 700px;">
        <div class="card-body px-4 py-4">
            <form method="POST" enctype="multipart/form-data" novalidate>
                <h5 class="fw-semibold text-center mb-4 text-primary">Form Tambah Pengumuman</h5>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Judul Pengumuman</label>
                    <input type="text" name="judul" class="form-control" placeholder="Judul pengumuman" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Isi Pengumuman</label>
                    <textarea class="form-control" name="isi" rows="5" placeholder="Isi lengkap pengumuman..." required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Tautan (Opsional)</label>
                    <input type="url" name="tautan" class="form-control" placeholder="https://tautan.com/link-pengumuman">
                    <small class="text-muted">Tautan bisa berupa link Google Drive, Bit.ly, atau lainnya. Boleh dikosongkan.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Gambar (Opsional)</label>
                    <input class="form-control" type="file" name="gambar" accept=".jpg,.jpeg,.png,.gif">
                    <small class="text-muted">Format gambar: jpg, jpeg, png, gif. Maksimum 10MB.</small>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="dashboard_admin.php?page=data_pengumuman" class="btn btn-secondary">
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
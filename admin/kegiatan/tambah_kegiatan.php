<?php
include '../confiq/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul     = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $tempat    = mysqli_real_escape_string($conn, $_POST['tempat']);
    $tanggal_kegiatan = mysqli_real_escape_string($conn, $_POST['tanggal_kegiatan']);
    $tautan    = mysqli_real_escape_string($conn, $_POST['tautan']);
    $tanggal_input = date('Y-m-d H:i:s');
    $gambar    = '';

    // Validasi wajib
    if (empty($judul) || empty($deskripsi) || empty($tempat) || empty($tanggal_kegiatan)) {
        echo "<script>alert('Harap lengkapi semua kolom yang wajib diisi'); window.history.back();</script>";
        exit;
    }

    // Upload Gambar jika ada
    if (!empty($_FILES['gambar']['tmp_name']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $tmp     = $_FILES['gambar']['tmp_name'];
        $nama_asli = $_FILES['gambar']['name'];
        $ukuran  = $_FILES['gambar']['size'];
        $ext     = strtolower(pathinfo($nama_asli, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($ext, $allowed)) {
            echo "<script>alert('Format gambar tidak diizinkan.'); window.history.back();</script>";
            exit;
        }

        if ($ukuran > 10 * 1024 * 1024) {
    echo "<script>alert('Ukuran gambar maksimal 10MB.'); window.history.back();</script>";
    exit;
}


        $uploadDir = '../upload/kegiatan/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        // Bersihkan dan buat nama file baru yang aman
$baseName = pathinfo($nama_asli, PATHINFO_FILENAME);                      // Ambil nama tanpa ekstensi
$baseName = preg_replace("/[^a-zA-Z0-9_-]/", "_", $baseName);             // Ganti karakter selain huruf, angka, _ atau - dengan _
$baseName = substr($baseName, 0, 30);                                     // Batasi panjang nama
$timestamp = date('Ymd_His');                                             // Format waktu yang mudah dibaca
$newFileName = "kegiatan_{$timestamp}_{$baseName}.{$ext}";               // Gabungkan semua bagian nama
        $destPath = $uploadDir . $newFileName;

        if (!@move_uploaded_file($tmp, $destPath)) {
    $errorMsg = 'Gagal memindahkan file gambar.';

    // Tambahan pengecekan error upload
    switch ($_FILES['gambar']['error']) {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            $errorMsg = 'Ukuran gambar melebihi batas yang diizinkan.';
            break;
        case UPLOAD_ERR_PARTIAL:
            $errorMsg = 'Gambar hanya terunggah sebagian.';
            break;
        case UPLOAD_ERR_NO_FILE:
            $errorMsg = 'Tidak ada gambar yang diunggah.';
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            $errorMsg = 'Folder temporary tidak tersedia.';
            break;
        case UPLOAD_ERR_CANT_WRITE:
            $errorMsg = 'Gagal menulis file ke disk.';
            break;
        case UPLOAD_ERR_EXTENSION:
            $errorMsg = 'Ekstensi PHP menghentikan upload.';
            break;
        default:
            $errorMsg = 'Upload gagal karena alasan yang tidak diketahui.';
            break;
    }

    echo "<script>alert('{$errorMsg}'); window.history.back();</script>";
    exit;
}

    // ✅ Tambahkan ini agar disimpan di database
    $gambar = $newFileName;
    
    }

    // Simpan ke database
    $query = "INSERT INTO kegiatan (judul, deskripsi, tempat, tanggal_kegiatan, gambar, tautan, tanggal_input)
              VALUES ('$judul', '$deskripsi', '$tempat', '$tanggal_kegiatan', '$gambar', '$tautan', '$tanggal_input')";

    if (mysqli_query($conn, $query)) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
        Swal.fire({
            title: 'Berhasil!',
            text: 'Kegiatan berhasil ditambahkan.',
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            window.location.href = 'dashboard_admin.php?page=data_kegiatan';
        });
        </script>";
    } else {
        echo "<script>
        Swal.fire({
            title: 'Gagal!',
            text: 'Gagal menyimpan kegiatan ke database.',
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

<!-- Header -->
<div class="bg-primary text-white px-4 px-md-5 pt-5 pb-4 shadow" style="border-radius: 0 0 40px 40px; min-height: 170px;">
    <div class="container">
        <h3 class="fw-semibold mb-1" style="font-size: 1.6rem;">Tambah Kegiatan</h3>
        <p class="mb-0">Masukkan informasi kegiatan organisasi secara lengkap.</p>
    </div>
</div>

<!-- Form -->
<div class="container px-3 px-md-5 pb-5" style="margin-top: -30px;">
    <div class="card border-0 shadow-sm mx-auto" style="max-width: 700px;">
        <div class="card-body px-4 py-4">
            <form method="POST" enctype="multipart/form-data" novalidate>
                <h5 class="fw-semibold text-center mb-4 text-primary">Form Tambah Kegiatan</h5>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Judul Kegiatan</label>
                    <input type="text" name="judul" class="form-control" required placeholder="Judul kegiatan">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="4" required placeholder="Deskripsi lengkap kegiatan"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Tempat</label>
                    <input type="text" name="tempat" class="form-control" required placeholder="Lokasi kegiatan">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Tanggal Kegiatan</label>
                    <input type="datetime-local" name="tanggal_kegiatan" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Tautan (Opsional)</label>
                    <input type="url" name="tautan" class="form-control" placeholder="https://link.com/tautan-kegiatan">
                    <small class="text-muted">Tautan bisa kosong jika tidak ada.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Upload Gambar (Opsional)</label>
                    <input type="file" name="gambar" class="form-control" accept=".jpg,.jpeg,.png,.gif">
                    <small class="text-muted">Ukuran maksimal 10MB. Format: jpg, jpeg, png, gif.</small>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="dashboard_admin.php?page=data_kegiatan" class="btn btn-secondary">
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
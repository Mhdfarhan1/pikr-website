<?php
include '../confiq/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- Ambil input ---
    $judul = trim(mysqli_real_escape_string($conn, $_POST['judul'] ?? ''));
    $tanggal_upload = date('Y-m-d H:i:s');
    $gambar = '';

    // --- Validasi judul ---
    if (empty($judul)) {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Judul wajib diisi!',
                confirmButtonText: 'Kembali'
            }).then(() => window.history.back());
        </script>";
        exit;
    }

    // --- Validasi file gambar ---
    if (!isset($_FILES['gambar']) || $_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Gambar wajib diunggah!',
                text: 'Silakan pilih file gambar terlebih dahulu.'
            }).then(() => window.history.back());
        </script>";
        exit;
    }

    $tmp     = $_FILES['gambar']['tmp_name'];
    $nama    = $_FILES['gambar']['name'];
    $ukuran  = $_FILES['gambar']['size'];
    $ext     = strtolower(pathinfo($nama, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];

    // --- Validasi ekstensi ---
    if (!in_array($ext, $allowed)) {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Format tidak valid!',
                text: 'Gunakan format JPG, JPEG, PNG, atau GIF.'
            }).then(() => window.history.back());
        </script>";
        exit;
    }

    // --- Validasi ukuran (max 20MB) ---
    if ($ukuran > 20 * 1024 * 1024) {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Ukuran terlalu besar!',
                text: 'Ukuran gambar maksimal 20MB.'
            }).then(() => window.history.back());
        </script>";
        exit;
    }

    // --- Siapkan folder upload ---
    $uploadDir = '../upload/galeri/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    // --- Generate nama file unik ---
    $gambarBaru = strtolower(uniqid('galeri_', true)) . '.' . $ext;
    $path = $uploadDir . $gambarBaru;

    // --- Upload file ---
    if (!move_uploaded_file($tmp, $path)) {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Upload gagal!',
                text: 'Tidak dapat menyimpan gambar ke server. Periksa izin folder upload.'
            }).then(() => window.history.back());
        </script>";
        exit;
    }

    // --- Simpan ke database ---
    $stmt = mysqli_prepare($conn, "INSERT INTO galeri (judul, gambar, tanggal_upload) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $judul, $gambarBaru, $tanggal_upload);

    if (mysqli_stmt_execute($stmt)) {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data galeri berhasil ditambahkan.'
            }).then(() => window.location.href='dashboard_admin.php?page=data_galeri');
        </script>";
    } else {
        // Jika gagal simpan ke DB, hapus gambar
        if (file_exists($path)) unlink($path);
        $error = htmlspecialchars(mysqli_error($conn));
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal menyimpan!',
                text: 'Terjadi kesalahan: $error'
            }).then(() => window.history.back());
        </script>";
    }

    mysqli_stmt_close($stmt);
    exit;
}
?>

<!-- === FORM TAMBAH GALERI === -->
<div class="bg-primary text-white px-4 px-md-5 pt-5 pb-4 shadow"
     style="border-radius: 0 0 40px 40px; min-height: 170px;">
    <div class="container">
        <h3 class="fw-semibold mb-1" style="font-size: 1.6rem;">Tambah Galeri</h3>
        <p class="mb-0">Tambahkan gambar dan judul baru ke galeri.</p>
    </div>
</div>

<div style="height: 30px;"></div>

<div class="container mt-4">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Gambar Galeri</h6>
        </div>
        <div class="card-body">
           <form action="dashboard_admin.php?page=tambah_galeri" method="POST" enctype="multipart/form-data">

                <div class="mb-3">
                    <label for="judul" class="form-label fw-semibold">Judul Gambar</label>
                   <input type="text" class="form-control" id="judul" name="judul" placeholder="Masukkan judul gambar" required>
                </div>

                <div class="mb-3">
                    <label for="gambar" class="form-label fw-semibold">Pilih Gambar</label>
                    <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" required>
                    <small class="form-text text-muted">Format: JPG, JPEG, PNG, GIF. Maksimal 20MB.</small>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-upload"></i> Simpan Data Galeri
                </button>
                <a href="dashboard_admin.php?page=data_galeri" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Batal
                </a>
            </form>
        </div>
    </div>
</div>

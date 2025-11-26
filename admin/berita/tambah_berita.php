<?php
include '../confiq/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

// Cek jika tombol submit ditekan
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {

    // Ambil data
    $judul           = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi             = mysqli_real_escape_string($conn, $_POST['isi']);
    $penulis         = mysqli_real_escape_string($conn, $_POST['penulis']);
    $tanggal_terbit  = mysqli_real_escape_string($conn, $_POST['tanggal_terbit']);
    $tautan          = mysqli_real_escape_string($conn, $_POST['tautan']);
    $tanggal_input   = date('Y-m-d H:i:s');

    // Upload gambar
    $gambar = $_FILES['gambar']['name'] ?? '';
    $tmp    = $_FILES['gambar']['tmp_name'] ?? '';
    $uploadDir = '../upload/berita/';
    $namaGambar = '';

    if (!empty($gambar) && !empty($tmp)) {
        $namaGambar = uniqid() . '_' . basename($gambar);
        move_uploaded_file($tmp, $uploadDir . $namaGambar);
    }

    // Simpan ke database
    $sql = "INSERT INTO berita (judul, isi, gambar, penulis, tanggal_terbit, tautan, tanggal_input)
            VALUES ('$judul', '$isi', '$namaGambar', '$penulis', '$tanggal_terbit', '$tautan', '$tanggal_input')";

    if (mysqli_query($conn, $sql)) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
        Swal.fire({
            icon: 'success',
            title: '🎉 Berita Ditambahkan!',
            text: 'Data berita berhasil disimpan.',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'dashboard_admin.php?page=data_berita';
            }
        });
    </script>";
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal Menyimpan!',
            text: '" . mysqli_error($conn) . "',
            confirmButtonColor: '#d33'
        });
    </script>";
    }
}
?>

<!-- Header -->
<div class="bg-primary text-white px-5 pt-5 pb-4 mb-4 shadow" style="border-radius: 0 0 40px 40px; margin-top: -60px; min-height: 150px;">
    <h3 class="fw-semibold mb-1" style="font-size: 1.5rem; margin-top: 20px;">Tambah Berita</h3>
    <p class="mb-0">Silakan isi data berita yang ingin dipublikasikan.</p>
</div>

<!-- Form Tambah -->
<div class="container px-4 pb-5">
    <div class="card shadow border-0 rounded-4">
        <div class="card-body px-4 py-4">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Berita</label>
                    <input type="text" name="judul" id="judul" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="isi" class="form-label">Isi Berita</label>
                    <textarea name="isi" id="isi" class="form-control" rows="6" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="gambar" class="form-label">Gambar Berita (opsional)</label>
                    <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label for="penulis" class="form-label">Penulis</label>
                    <input type="text" name="penulis" id="penulis" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="tanggal_terbit" class="form-label">Tanggal Terbit</label>
                    <input type="datetime-local" name="tanggal_terbit" id="tanggal_terbit" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="tautan" class="form-label">Tautan Tambahan (opsional)</label>
                    <input type="url" name="tautan" id="tautan" class="form-control" placeholder="https://...">
                </div>

                <button type="submit" name="submit" class="btn btn-primary fw-semibold">
                    <i class="bi bi-save me-1"></i> Simpan
                </button>
                <a href="dashboard_admin.php?page=data_berita" class="btn btn-secondary ms-2">Kembali</a>
            </form>
        </div>
    </div>
</div>
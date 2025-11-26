<?php
include '../confiq/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

// Proses simpan data jika form disubmit
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $nama_lomba = mysqli_real_escape_string($conn, $_POST['nama_lomba']);

    $query = mysqli_query($conn, "INSERT INTO lomba (nama_lomba) VALUES ('$nama_lomba')");

    if ($query) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Lomba berhasil ditambahkan!',
                confirmButtonColor: '#3085d6'
            }).then(() => {
                window.location.href = 'dashboard_admin.php?page=data_lomba';
            });
        </script>";
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Gagal menambahkan lomba: " . mysqli_real_escape_string($conn, mysqli_error($conn)) . "',
                confirmButtonColor: '#d33'
            });
        </script>";
    }
}
?>

<!-- Header -->
<div class="bg-primary text-white px-5 pt-5 pb-4 mb-4 shadow" style="border-radius: 0 0 40px 40px; margin-top: -60px; min-height: 150px;">
    <h3 class="fw-semibold mb-1" style="font-size: 1.5rem; margin-top: 20px;">Tambah Lomba</h3>
    <p class="mb-0">Masukkan nama lomba yang akan tersedia untuk peserta.</p>
</div>

<!-- Form Tambah Lomba -->
<div class="container px-4 pb-5">
    <div class="card shadow border-0 rounded-4">
        <div class="card-body px-4 py-4">
            <form method="POST">
                <div class="mb-3">
                    <label for="nama_lomba" class="form-label">Nama Lomba</label>
                    <input type="text" name="nama_lomba" id="nama_lomba" class="form-control" placeholder="Contoh: Lomba Poster Digital" required>
                </div>

                <button type="submit" name="submit" class="btn btn-primary fw-semibold">
                    <i class="bi bi-save me-1"></i> Simpan
                </button>
                <a href="dashboard_admin.php?page=data_lomba" class="btn btn-secondary ms-2">Kembali</a>
            </form>
        </div>
    </div>
</div>
<?php
include '../confiq/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

// Cek jika tombol submit ditekan
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {

    // Ambil data dengan validasi aman
    $nama_instansi = isset($_POST['nama_instansi']) ? mysqli_real_escape_string($conn, $_POST['nama_instansi']) : '';
    $deskripsi     = isset($_POST['deskripsi']) ? mysqli_real_escape_string($conn, $_POST['deskripsi']) : '';
    $urutan        = isset($_POST['urutan']) ? (int) $_POST['urutan'] : 0;
    $status        = isset($_POST['status']) ? mysqli_real_escape_string($conn, $_POST['status']) : 'nonaktif';
    $tanggal_input = date('Y-m-d H:i:s');

    // Upload gambar
    $gambar = $_FILES['gambar']['name'] ?? '';
    $tmp    = $_FILES['gambar']['tmp_name'] ?? '';
    $uploadDir = '../upload/kerjasama/';
    $namaGambar = '';

    if (!empty($gambar) && !empty($tmp)) {
        $namaGambar = uniqid() . '_' . basename($gambar);
        move_uploaded_file($tmp, $uploadDir . $namaGambar);
    }

    // Simpan ke database
    $sql = "INSERT INTO kerja_sama (nama_instansi, deskripsi, gambar, urutan, status, tanggal_input)
            VALUES ('$nama_instansi', '$deskripsi', '$namaGambar', '$urutan', '$status', '$tanggal_input')";
    if (mysqli_query($conn, $sql)) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data kerja sama berhasil ditambahkan!',
            showConfirmButton: false,
            timer: 2000
        }).then(() => {
            window.location.href = 'dashboard_admin.php?page=data_kerjasama';
        });
    </script>";
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            html: 'Gagal menyimpan data:<br><pre>" . mysqli_error($conn) . "</pre>',
            confirmButtonColor: '#d33'
        });
    </script>";
    }
}
?>


<!-- Header -->
<div class="bg-primary text-white px-5 pt-5 pb-4 mb-4 shadow" style="border-radius: 0 0 40px 40px; margin-top: -60px; min-height: 150px;">
    <h3 class="fw-semibold mb-1" style="font-size: 1.5rem; margin-top: 20px;">Tambah Kerja Sama</h3>
    <p class="mb-0">Silakan isi data kerja sama yang baru.</p>
</div>

<!-- Form Tambah -->
<div class="container px-4 pb-5">
    <div class="card shadow border-0 rounded-4">
        <div class="card-body px-4 py-4">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nama_instansi" class="form-label">Nama Instansi</label>
                    <input type="text" name="nama_instansi" id="nama_instansi" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="gambar" class="form-label">Foto Instansi (opsional)</label>
                    <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label for="urutan" class="form-label">Urutan Tampil</label>
                    <input type="number" name="urutan" id="urutan" class="form-control" value="0" min="0" required>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="aktif" selected>Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>

                <button type="submit" name="submit" class="btn btn-primary fw-semibold">
                    <i class="bi bi-save me-1"></i> Simpan
                </button>
                <a href="dashboard_admin.php?page=data_kerjasama" class="btn btn-secondary ms-2">Kembali</a>
            </form>
        </div>
    </div>
</div>
<?php
include '../confiq/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_file = $_FILES['gambar']['name'];
    $tmp_file  = $_FILES['gambar']['tmp_name'];
    $ukuran    = $_FILES['gambar']['size'];
    $ext       = pathinfo($nama_file, PATHINFO_EXTENSION);
    $uploadDir = '../upload/logo/';
    $newName   = uniqid() . '.' . $ext;

    $urutan = intval($_POST['urutan']);
    $status = $_POST['status'];
    $tanggal = date('Y-m-d H:i:s');

    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $maxSize = 10 * 1024 * 1024; // 10MB

    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

    // Cek ekstensi
    if (!in_array(strtolower($ext), $allowed_ext)) {
        echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Hanya file gambar yang diperbolehkan (jpg, jpeg, png, gif, webp).',
            confirmButtonColor: '#d33'
        }).then(() => {
            window.history.back();
        });
        </script>";
        exit;
    }

    // Cek ukuran file
    if ($ukuran > $maxSize) {
        echo "<script>
        Swal.fire({
            icon: 'warning',
            title: 'Ukuran Terlalu Besar!',
            text: 'Ukuran file maksimal 10 MB.',
            confirmButtonColor: '#d33'
        }).then(() => {
            window.history.back();
        });
        </script>";
        exit;
    }

    // Upload file
    if (move_uploaded_file($tmp_file, $uploadDir . $newName)) {
        $simpan = mysqli_query($conn, "INSERT INTO logo (gambar, urutan, status, tanggal_upload) 
        VALUES ('$newName', '$urutan', '$status', '$tanggal')");

        if ($simpan) {
            echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Logo berhasil ditambahkan.',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                window.location.href = 'dashboard_admin.php?page=data_logo';
            });
            </script>";
        } else {
            echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Gagal menyimpan ke database.',
                confirmButtonColor: '#d33'
            }).then(() => {
                window.history.back();
            });
            </script>";
        }
    } else {
        echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Upload Gagal!',
            text: 'Upload gambar gagal.',
            confirmButtonColor: '#d33'
        }).then(() => {
            window.history.back();
        });
        </script>";
    }
}
?>

<!-- Header -->
<div class="bg-primary text-white px-5 pt-5 pb-4 mb-4 shadow" style="border-radius: 0 0 40px 40px; margin-top: -60px; min-height: 150px;">
    <h3 class="fw-semibold mb-1" style="font-size: 1.5rem; margin-top: 20px;">Tambah Logo Baru</h3>
    <p class="mb-0">Unggah logo baru untuk ditampilkan di halaman utama.</p>
</div>

<!-- Form Tambah Logo -->
<div class="container px-4 pb-5">
    <div class="card shadow border-0 rounded-4">
        <div class="card-body px-4 py-4">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="gambar" class="form-label fw-semibold">Pilih Gambar Logo</label>
                    <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*" required>
                    <small class="text-muted">Maksimal 10MB. Format: jpg, png, webp, dll.</small>
                </div>

                <div class="mb-3">
                    <label for="urutan" class="form-label fw-semibold">Urutan Tampil</label>
                    <input type="number" name="urutan" id="urutan" class="form-control" min="1" required>
                </div>

                <div class="mb-4">
                    <label for="status" class="form-label fw-semibold">Status Tampil</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="dashboard_admin.php?page=data_logo" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload"></i> Simpan Logo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

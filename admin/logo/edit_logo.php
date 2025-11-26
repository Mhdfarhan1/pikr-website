<?php
include '../confiq/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>alert('ID tidak ditemukan.'); window.location.href='dashboard_admin.php?page=data_logo';</script>";
    exit;
}

// Ambil data logo
$query = mysqli_query($conn, "SELECT * FROM logo WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);
if (!$data) {
    echo "<script>alert('Data logo tidak ditemukan.'); window.location.href='dashboard_admin.php?page=data_logo';</script>";
    exit;
}

// Handle submit
if (isset($_POST['submit'])) {
    $urutan = $_POST['urutan'];
    $status = $_POST['status'];
    $gambarLama = $data['gambar'];
    $tanggal = date('Y-m-d H:i:s');

    // Cek jika ada gambar baru
    if ($_FILES['gambar']['name'] != '') {
        $namaFile = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];
        $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
        $newName = uniqid() . '.' . $ext;
        $uploadDir = '../upload/logo/';
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($ext, $allowed)) {
            if (move_uploaded_file($tmp, $uploadDir . $newName)) {
                // Hapus gambar lama
                if (file_exists($uploadDir . $gambarLama)) {
                    unlink($uploadDir . $gambarLama);
                }
                $gambar = $newName;
            } else {
                echo "<script>alert('Gagal upload gambar baru.');</script>";
                $gambar = $gambarLama;
            }
        } else {
            echo "<script>alert('Format gambar tidak didukung.');</script>";
            $gambar = $gambarLama;
        }
    } else {
        $gambar = $gambarLama;
    }

    // Update database
    $update = mysqli_query($conn, "UPDATE logo SET gambar='$gambar', urutan='$urutan', status='$status', tanggal_upload='$tanggal' WHERE id='$id'");

    if ($update) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Logo berhasil diperbarui.',
            showConfirmButton: false,
            timer: 2000
        }).then(() => {
            window.location.href = 'dashboard_admin.php?page=data_logo';
        });
    </script>";
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Gagal memperbarui logo.',
            confirmButtonColor: '#d33'
        });
    </script>";
    }
}
?>

<!-- Header -->
<div class="bg-primary text-white px-5 pt-5 pb-4 mb-4 shadow" style="border-radius: 0 0 40px 40px; margin-top: -60px; min-height: 150px;">
    <h3 class="fw-semibold mb-1" style="font-size: 1.5rem; margin-top: 20px;">Edit Logo</h3>
    <p class="mb-0">Edit logo sesuai untuk tampilan utama.</p>
</div>

<!-- Tampilan Form -->
<div class="container py-4">
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-white border-bottom py-3 px-4">
            <h5 class="fw-bold mb-0">Edit Logo</h5>
        </div>
        <div class="card-body px-4">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Gambar Saat Ini:</label><br>
                    <img src="../upload/logo/<?= htmlspecialchars($data['gambar']) ?>" width="100" class="rounded shadow-sm">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Ganti Gambar (opsional):</label>
                    <input type="file" name="gambar" class="form-control">
                    <small class="text-muted">Format gambar: JPG, PNG, WEBP. Biarkan kosong jika tidak ingin mengganti.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Urutan Tampil:</label>
                    <input type="number" name="urutan" class="form-control" value="<?= htmlspecialchars($data['urutan']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Status Tampil:</label>
                    <select name="status" class="form-select" required>
                        <option value="1" <?= $data['status'] == '1' ? 'selected' : '' ?>>Tampilkan</option>
                        <option value="0" <?= $data['status'] == '0' ? 'selected' : '' ?>>Sembunyikan</option>
                    </select>
                </div>

                <button type="submit" name="submit" class="btn btn-primary fw-semibold"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
                <a href="dashboard_admin.php?page=data_logo" class="btn btn-secondary fw-semibold ms-2">Batal</a>
            </form>
        </div>
    </div>
</div>
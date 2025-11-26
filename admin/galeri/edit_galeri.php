<?php
include '../confiq/koneksi.php';
date_default_timezone_set('Asia/Jakarta');
?>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'ID galeri tidak valid!'
        }).then(() => {
            window.location.href='dashboard_admin.php?page=data_galeri';
        });
    </script>";
    exit;
}

$sql = mysqli_query($conn, "SELECT * FROM galeri WHERE id = $id LIMIT 1");
if (mysqli_num_rows($sql) == 0) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Data galeri tidak ditemukan!'
        }).then(() => {
            window.location.href='dashboard_admin.php?page=data_galeri';
        });
    </script>";
    exit;
}

$row = mysqli_fetch_assoc($sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $tanggal_upload = date('Y-m-d H:i:s');
    $gambarLama = $row['gambar'];
    $gambarBaru = $gambarLama;

    if (empty($judul)) {
        echo "<script>
            Swal.fire({
                icon: 'warning',
                title: 'Judul wajib diisi!'
            }).then(() => {
                window.history.back();
            });
        </script>";
        exit;
    }

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['gambar']['tmp_name'];
        $fileName = $_FILES['gambar']['name'];
        $fileSize = $_FILES['gambar']['size'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($fileExt, $allowed)) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Format gambar tidak diizinkan!',
                    text: 'Hanya jpg, jpeg, png, gif.'
                }).then(() => {
                    window.history.back();
                });
            </script>";
            exit;
        }

        if ($fileSize > 20 * 1024 * 1024) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Ukuran gambar terlalu besar!',
                    text: 'Maksimal 20MB.'
                }).then(() => {
                    window.history.back();
                });
            </script>";
            exit;
        }

        $uploadDir = '../upload/galeri/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $newFileName = uniqid('galeri_', true) . '.' . $fileExt;
        $targetPath = $uploadDir . $newFileName;

        if (!move_uploaded_file($fileTmp, $targetPath)) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal mengupload gambar baru!'
                }).then(() => {
                    window.history.back();
                });
            </script>";
            exit;
        }

        if (!empty($gambarLama) && file_exists($uploadDir . $gambarLama)) {
            unlink($uploadDir . $gambarLama);
        }

        $gambarBaru = $newFileName;
    }

    $update = mysqli_query($conn, "UPDATE galeri SET judul='$judul', gambar='$gambarBaru', tanggal_upload='$tanggal_upload' WHERE id=$id");

    if ($update) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Galeri berhasil diperbarui!'
            }).then(() => {
                window.location.href='dashboard_admin.php?page=data_galeri';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal memperbarui galeri!'
            }).then(() => {
                window.history.back();
            });
        </script>";
    }
}
?>

<!-- Form Edit Galeri -->
<div class="container mt-5 mb-4">
    <div class="card shadow border-0 rounded-4" style="max-width: 700px; margin: auto;">
        <div class="card-body p-4">
            <h4 class="fw-bold text-center mb-4 text-primary">Edit Galeri</h4>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Judul Foto</label>
                    <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($row['judul']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Gambar Saat Ini</label><br>
                    <?php if (!empty($row['gambar']) && file_exists('../upload/galeri/' . $row['gambar'])) : ?>
                        <img src="../upload/galeri/<?= $row['gambar'] ?>" alt="Gambar" class="img-thumbnail" style="max-height: 200px;">
                    <?php else: ?>
                        <p><i>Tidak ada gambar</i></p>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Ganti Gambar (opsional)</label>
                    <input type="file" name="gambar" class="form-control" accept=".jpg,.jpeg,.png,.gif">
                    <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar. Maks 5MB.</small>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="dashboard_admin.php?page=data_galeri" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

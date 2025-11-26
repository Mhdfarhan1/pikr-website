<?php
include '../confiq/koneksi.php';


// Bagian ini akan dijalankan jika request adalah POST (saat form disubmit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    // Validasi sederhana
    if (empty($nama) || empty($jabatan) || empty($deskripsi)) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Data Tidak Lengkap',
                text: 'Pastikan semua data wajib diisi!',
            }).then(() => {
                window.history.back();
            });
        </script>";
        exit;
    }

    // Upload foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['foto']['tmp_name'];
        $fileName = $_FILES['foto']['name'];
        $fileSize = $_FILES['foto']['size'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExtension, $allowedfileExtensions)) {
            // Batasi ukuran file max 10MB
            if ($fileSize > 10 * 1024 * 1024) {
                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Ukuran Terlalu Besar',
                        text: 'Ukuran file maksimal 10MB.',
                    }).then(() => {
                        window.history.back();
                    });
                </script>";
                exit;
            }

            $newFileName = uniqid('frofile_', true) . '.' . $fileExtension;
            $uploadFileDir = '../assets/img/frofile/'; // Pastikan folder ini ada dan writable (izin 0755 atau 0777)
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $query = "INSERT INTO frofile (nama, jabatan, deskripsi, foto)
                            VALUES ('$nama', '$jabatan', '$deskripsi', '$newFileName')";

                if (mysqli_query($conn, $query)) {
                    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data frofile berhasil ditambahkan.',
                        }).then(() => {
                            window.location.href='dashboard_admin.php?page=data_frofile';
                        });
                    </script>";
                    exit;
                } else {
                    unlink($dest_path); // Hapus file yang sudah diupload jika gagal simpan ke DB
                    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Simpan',
                            text: 'Terjadi kesalahan saat menyimpan ke database: " . mysqli_error($conn) . "', // Tampilkan error DB untuk debugging
                        }).then(() => {
                            window.history.back();
                        });
                    </script>";
                    exit;
                }
            } else {
                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Upload Gagal',
                        text: 'Gagal mengupload foto. Pastikan folder " . htmlspecialchars($uploadFileDir) . " memiliki izin tulis (0755).',
                    }).then(() => {
                        window.history.back();
                    });
                </script>";
                exit;
            }
        } else {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Format Tidak Didukung',
                    text: 'Gunakan format jpg, jpeg, png, atau gif.',
                }).then(() => {
                    window.history.back();
                });
            </script>";
            exit;
        }
    } else {
        // Ini akan dieksekusi jika $_FILES['foto'] tidak diset atau ada error upload lainnya
        $error_message = 'Silakan upload foto terlebih dahulu.';
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            switch ($_FILES['foto']['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $error_message = 'Ukuran file melebihi batas yang diizinkan.';
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $error_message = 'File hanya terunggah sebagian.';
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $error_message = 'Tidak ada file yang diunggah.';
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $error_message = 'Folder temporary hilang.';
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $error_message = 'Gagal menulis file ke disk.';
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $error_message = 'Ekstensi PHP menghentikan unggahan file.';
                    break;
            }
        }
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Upload Foto Gagal',
                text: '" . htmlspecialchars($error_message) . "',
            }).then(() => {
                window.history.back();
            });
        </script>";
        exit;
    }
}

// Bagian ini akan dijalankan jika request adalah GET (saat halaman pertama kali diakses)
// Ini adalah form HTML yang akan ditampilkan
?>
<div class="bg-primary text-white px-4 px-md-5 pt-5 pb-4 shadow" style="border-radius: 0 0 40px 40px; min-height: 170px;">
    <div class="container">
        <h3 class="fw-semibold mb-1" style="font-size: 1.6rem;">Tambah Frofile</h3>
        <p class="mb-0">Tambahkan data kepala sekolah / pembina pada sistem PIK-R.</p>
    </div>
</div>

<div style="height: 30px;"></div> 

<div class="container mt-4"> <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Data Frofile</h6>
        </div>
        <div class="card-body">
            <form action="dashboard_admin.php?page=tambah_frofile" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" required>
                </div>
                <div class="mb-3">
                    <label for="jabatan" class="form-label">Jabatan</label>
                    <input type="text" class="form-control" id="jabatan" name="jabatan" required>
                </div>
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="foto" class="form-label">Foto</label>
                    <input type="file" class="form-control" id="foto" name="foto" accept="image/*" required>
                    <small class="form-text text-muted">Format: JPG, JPEG, PNG, GIF. Maksimal 10MB.</small>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Data</button>
                <a href="dashboard_admin.php?page=data_frofile" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
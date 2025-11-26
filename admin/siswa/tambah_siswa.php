<?php
include '../confiq/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nis = mysqli_real_escape_string($conn, trim($_POST['nis']));
    $nama_siswa = mysqli_real_escape_string($conn, trim($_POST['nama']));
    $kelas = mysqli_real_escape_string($conn, trim($_POST['kelas']));
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';

    if (!in_array($jenis_kelamin, ['L', 'P'])) {
        echo "<script>alert('Jenis kelamin harus dipilih dengan benar.'); window.history.back();</script>";
        exit;
    }
    if (empty($nis) || empty($nama_siswa) || empty($kelas)) {
        echo "<script>alert('Semua kolom harus diisi.'); window.history.back();</script>";
        exit;
    }

    // Cek duplikat NIS
    $cek_nis = mysqli_query($conn, "SELECT nis FROM siswa WHERE nis = '$nis'");
    if (mysqli_num_rows($cek_nis) > 0) {
        echo "<script>alert('NIS sudah terdaftar.'); window.history.back();</script>";
        exit;
    }

    // Simpan dulu ke users
    $password_hash = password_hash($nis, PASSWORD_DEFAULT);
    $simpan_user = mysqli_query($conn, "INSERT INTO users (username, password, role, nama_lengkap) 
    VALUES ('$nis', '$password_hash', 'siswa', '$nama_siswa')");

    if (!$simpan_user) {
        $error = mysqli_error($conn);
        echo "<script>alert('Gagal simpan akun pengguna: $error'); window.history.back();</script>";
        exit;
    }

    // Ambil id_user dari insert terakhir
    $id_user = mysqli_insert_id($conn);

    // Simpan ke siswa dengan id_user
    $simpan_siswa = mysqli_query($conn, "INSERT INTO siswa (nis, nama_siswa, kelas, jenis_kelamin, id_user) VALUES ('$nis', '$nama_siswa', '$kelas', '$jenis_kelamin', $id_user)");

    if ($simpan_siswa) {
        echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data siswa berhasil ditambahkan.',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'dashboard_admin.php?page=data_siswa';
            }
        });
    </script>";
    } else {
        $error = mysqli_error($conn);
        echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            html: 'Gagal menambahkan siswa:<br><small>" . htmlspecialchars($error) . "</small>',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Kembali'
        }).then(() => {
            window.history.back();
        });
    </script>";
    }
    exit;
}
?>


<!-- Tampilan Form -->
<div class="bg-primary text-white px-4 px-md-5 pt-5 pb-4 shadow"
    style="border-radius: 0 0 40px 40px; min-height: 170px;">
    <div class="container">
        <h3 class="fw-semibold mb-1" style="font-size: 1.6rem; margin-top: 20px;">Tambah Siswa</h3>
        <p class="mb-0">Isi data lengkap siswa untuk menambah keanggotaan baru dalam sistem PIK-R.</p>
    </div>
</div>

<div class="container px-3 px-md-5 pb-5" style="margin-top: -30px;">
    <div class="card border-0 shadow-sm mx-auto" style="max-width: 600px;">
        <div class="card-body px-4 py-4">
            <h5 class="fw-semibold text-center mb-4 text-primary">Form Tambah Siswa</h5>
            <form method="POST" novalidate>
                <div class="mb-3">
                    <label for="nis" class="form-label fw-semibold">NIS</label>
                    <input type="text" name="nis" id="nis" class="form-control" placeholder="Masukkan NIS siswa" required>
                </div>
                <div class="mb-3">
                    <label for="nama" class="form-label fw-semibold">Nama Siswa</label>
                    <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
                </div>
                <div class="mb-3">
                    <label for="kelas" class="form-label fw-semibold">Kelas</label>
                    <input type="text" name="kelas" id="kelas" class="form-control" placeholder="Contoh: X IPA 2" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Jenis Kelamin</label>
                    <div class="d-flex flex-wrap justify-content-left gap-4 mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" id="laki" value="L" required>
                            <label class="form-check-label" for="laki">Laki-laki</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" id="perempuan" value="P" required>
                            <label class="form-check-label" for="perempuan">Perempuan</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="dashboard_admin.php?page=data_siswa" class="btn btn-secondary">
                        <i class="mdi mdi-arrow-left"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
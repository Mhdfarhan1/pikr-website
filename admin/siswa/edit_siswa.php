<?php
include '../confiq/koneksi.php';

$id = $_GET['id'] ?? 0;
$id = intval($id);

$query = mysqli_query($conn, "
    SELECT siswa.*, users.nama_lengkap 
    FROM siswa 
    LEFT JOIN users ON siswa.id_user = users.id 
    WHERE siswa.id = $id
");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data siswa tidak ditemukan.'); window.location.href='dashboard_admin.php?page=data_siswa';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nis = mysqli_real_escape_string($conn, trim($_POST['nis']));
    $nama = mysqli_real_escape_string($conn, trim($_POST['nama']));
    $kelas = mysqli_real_escape_string($conn, trim($_POST['kelas']));
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';

    if (!in_array($jenis_kelamin, ['L', 'P'])) {
        echo "<script>alert('Jenis kelamin tidak valid.'); window.history.back();</script>";
        exit;
    }

    $update_siswa = mysqli_query($conn, "UPDATE siswa SET nis='$nis', nama_siswa='$nama', kelas='$kelas', jenis_kelamin='$jenis_kelamin' WHERE id=$id");
    $update_user = mysqli_query($conn, "UPDATE users SET nama_lengkap='$nama', username='$nis' WHERE id={$data['id_user']}");

    if ($update_siswa && $update_user) {
        echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data siswa berhasil diperbarui.',
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
            html: 'Gagal memperbarui data:<br><small>$error</small>',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Coba Lagi'
        });
    </script>";
    }
}
?>

<!-- Header -->
<div class="bg-primary text-white px-4 px-md-5 pt-5 pb-4 shadow" style="border-radius: 0 0 40px 40px; min-height: 170px;">
    <div class="container">
        <h3 class="fw-semibold mb-1" style="font-size: 1.6rem; margin-top: 20px;">Edit Siswa</h3>
        <p class="mb-0">Perbarui data siswa yang terdaftar dalam sistem PIK-R.</p>
    </div>
</div>

<!-- Form Edit -->
<div class="container px-3 px-md-5 pb-5" style="margin-top: -30px;">
    <div class="card border-0 shadow-sm mx-auto" style="max-width: 600px;">
        <div class="card-body px-4 py-4">
            <h5 class="fw-semibold text-center mb-4 text-primary">Form Edit Siswa</h5>
            <form method="POST" novalidate>
                <div class="mb-3">
                    <label for="nis" class="form-label fw-semibold">NIS</label>
                    <input type="text" name="nis" id="nis" class="form-control" required value="<?= htmlspecialchars($data['nis']) ?>">
                </div>
                <div class="mb-3">
                    <label for="nama" class="form-label fw-semibold">Nama Siswa</label>
                    <input type="text" name="nama" id="nama" class="form-control" required value="<?= htmlspecialchars($data['nama_lengkap'] ?? $data['nama_siswa']) ?>">
                </div>
                <div class="mb-3">
                    <label for="kelas" class="form-label fw-semibold">Kelas</label>
                    <input type="text" name="kelas" id="kelas" class="form-control" required value="<?= htmlspecialchars($data['kelas']) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Jenis Kelamin</label>
                    <div class="d-flex flex-wrap justify-content-left gap-4 mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" id="laki" value="L" <?= $data['jenis_kelamin'] === 'L' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="laki">Laki-laki</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" id="perempuan" value="P" <?= $data['jenis_kelamin'] === 'P' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="perempuan">Perempuan</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="dashboard_admin.php?page=data_siswa" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary text-white">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
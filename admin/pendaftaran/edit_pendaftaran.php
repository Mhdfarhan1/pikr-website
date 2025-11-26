<?php
include '../confiq/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>alert('ID tidak ditemukan!'); window.location.href='dashboard_admin.php?page=data_pendaftaran';</script>";
    exit;
}

// Ambil data pendaftaran
$query = mysqli_query($conn, "SELECT * FROM pendaftaran_lomba WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='dashboard_admin.php?page=data_pendaftaran';</script>";
    exit;
}

// Proses update
if (isset($_POST['update'])) {
    $nama_peserta = mysqli_real_escape_string($conn, $_POST['nama_peserta']);
    $kelas = mysqli_real_escape_string($conn, $_POST['kelas']);
    $kontak = mysqli_real_escape_string($conn, $_POST['kontak']);
    $id_lomba = (int) $_POST['id_lomba'];
    $anggota_kelompok = mysqli_real_escape_string($conn, $_POST['anggota_kelompok']);
    $tanggal_daftar = date('Y-m-d H:i:s');

    $update = mysqli_query($conn, "UPDATE pendaftaran_lomba SET 
        nama_peserta = '$nama_peserta',
        kelas = '$kelas',
        kontak = '$kontak',
        id_lomba = '$id_lomba',
        anggota_kelompok = '$anggota_kelompok',
        tanggal_daftar = '$tanggal_daftar'
        WHERE id = '$id'");
    if ($update) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Data pendaftaran berhasil diperbarui!',
        showConfirmButton: false,
        timer: 2000
    }).then(() => {
        window.location.href = 'dashboard_admin.php?page=data_pendaftaran';
    });
</script>";
    } else {
        $error = mysqli_error($conn);
        echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Gagal mengupdate data: $error'
        });
    </script>";
    }
}
?>

<!-- Header -->
<div class="bg-primary text-white px-5 pt-5 pb-4 mb-4 shadow" style="border-radius: 0 0 40px 40px; margin-top: -60px; min-height: 150px;">
    <h3 class="fw-semibold mb-1" style="font-size: 1.5rem; margin-top: 20px;">Edit Pendaftaran Lomba</h3>
    <p class="mb-0">Perbarui data pendaftaran peserta lomba.</p>
</div>

<!-- Form -->
<div class="container px-4 pb-5">
    <div class="card shadow border-0 rounded-4">
        <div class="card-body px-4 py-4">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Peserta</label>
                    <input type="text" name="nama_peserta" value="<?= htmlspecialchars($data['nama_peserta']) ?>" required class="form-control shadow-sm">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kelas</label>
                    <input type="text" name="kelas" value="<?= htmlspecialchars($data['kelas']) ?>" required class="form-control shadow-sm">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kontak</label>
                    <input type="text" name="kontak" value="<?= htmlspecialchars($data['kontak']) ?>" required class="form-control shadow-sm">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Pilih Lomba</label>
                    <select name="id_lomba" class="form-select shadow-sm" required>
                        <option value="">-- Pilih Lomba --</option>
                        <?php
                        $lomba = mysqli_query($conn, "SELECT * FROM lomba");
                        while ($row = mysqli_fetch_assoc($lomba)) {
                            $selected = ($row['id_lomba'] == $data['id_lomba']) ? 'selected' : '';
                            echo "<option value='{$row['id_lomba']}' $selected>{$row['nama_lomba']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Anggota Kelompok (jika ada)</label>
                    <textarea name="anggota_kelompok" class="form-control shadow-sm" rows="3"><?= htmlspecialchars($data['anggota_kelompok']) ?></textarea>
                </div>

                <button type="submit" name="update" class="btn btn-primary fw-semibold shadow-sm px-4">
                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                </button>
                <a href="dashboard_admin.php?page=data_pendaftaran" class="btn btn-secondary shadow-sm">Kembali</a>
            </form>
        </div>
    </div>
</div>
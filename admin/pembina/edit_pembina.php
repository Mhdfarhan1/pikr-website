<?php
include '../confiq/koneksi.php';

$id = $_GET['id'] ?? 0;
$id = intval($id);

// Ambil data pembina berdasarkan id
$query = mysqli_query($conn, "
    SELECT pembina.*, users.nama_lengkap 
    FROM pembina 
    LEFT JOIN users ON pembina.id_user = users.id 
    WHERE pembina.id_guru = $id
");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data pembina tidak ditemukan.'); window.location.href='dashboard_admin.php?page=data_pembina';</script>";
    exit;
}

// Handle update jika disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nip = mysqli_real_escape_string($conn, trim($_POST['nip']));
    $nama = mysqli_real_escape_string($conn, trim($_POST['nama']));
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $alamat = mysqli_real_escape_string($conn, trim($_POST['alamat']));
    $no_hp = mysqli_real_escape_string($conn, trim($_POST['no_hp']));

    if (!in_array($jenis_kelamin, ['L', 'P'])) {
        echo "<script>alert('Jenis kelamin tidak valid.'); window.history.back();</script>";
        exit;
    }

    $update_guru = mysqli_query($conn, "UPDATE pembina SET nip='$nip', nama_guru='$nama', jenis_kelamin='$jenis_kelamin', alamat='$alamat', no_hp='$no_hp' WHERE id_guru=$id");
    $update_user = mysqli_query($conn, "UPDATE users SET nama_lengkap='$nama', username='$nip' WHERE id={$data['id_user']}");

    if ($update_guru && $update_user) {
        echo "<script>alert('Data pembina berhasil diperbarui.'); window.location.href='dashboard_admin.php?page=data_pembina';</script>";
    } else {
        $error = mysqli_error($conn);
        echo "<script>alert('Gagal memperbarui data: $error');</script>";
    }
}
?>

<!-- Header -->
<div class="bg-primary text-white px-4 px-md-5 pt-5 pb-4 shadow" style="border-radius: 0 0 40px 40px; min-height: 170px;">
    <div class="container">
        <h3 class="fw-semibold mb-1" style="font-size: 1.6rem; margin-top: 20px;">Edit Pembina</h3>
        <p class="mb-0">Perbarui data guru pembina yang terdaftar di sistem PIK-R.</p>
    </div>
</div>

<!-- Form Edit -->
<div class="container px-3 px-md-5 pb-5" style="margin-top: -30px;">
    <div class="card border-0 shadow-sm mx-auto" style="max-width: 600px;">
        <div class="card-body px-4 py-4">
            <h5 class="fw-semibold text-center mb-4 text-primary">Form Edit Pembina</h5>
            <form method="POST" novalidate>
                <div class="mb-3">
                    <label for="nip" class="form-label fw-semibold">NIP</label>
                    <input type="text" name="nip" id="nip" class="form-control" required value="<?= htmlspecialchars($data['nip']) ?>">
                </div>
                <div class="mb-3">
                    <label for="nama" class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" class="form-control" required value="<?= htmlspecialchars($data['nama_lengkap'] ?? $data['nama_guru']) ?>">
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
                <div class="mb-3">
                    <label for="alamat" class="form-label fw-semibold">Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control" required><?= htmlspecialchars($data['alamat']) ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="no_hp" class="form-label fw-semibold">No HP</label>
                    <input type="text" name="no_hp" id="no_hp" class="form-control" required value="<?= htmlspecialchars($data['no_hp']) ?>">
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="dashboard_admin.php?page=data_pembina" class="btn btn-secondary">
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
<?php
include '../confiq/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $nip = mysqli_real_escape_string($conn, $_POST['nip']);
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);

    if (!in_array($jenis_kelamin, ['L', 'P']) || empty($nama) || empty($nip)) {
        echo "<script>alert('Pastikan semua data diisi dengan benar!'); window.history.back();</script>";
        exit;
    }

    // Cek NIP duplikat
    $cek = mysqli_query($conn, "SELECT * FROM pembina WHERE nip = '$nip'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('NIP sudah terdaftar!'); window.history.back();</script>";
        exit;
    }

    // Buat akun user terlebih dahulu
    $password = password_hash($nip, PASSWORD_DEFAULT);
    $buat_user = mysqli_query($conn, "INSERT INTO users (username, password, role, nama_lengkap) VALUES ('$nip', '$password', 'pembina', '$nama')");

    if ($buat_user) {
        $id_user = mysqli_insert_id($conn);
        $simpan = mysqli_query($conn, "INSERT INTO pembina (id_user, nama_guru, nip, jenis_kelamin, alamat, no_hp) 
                                       VALUES ($id_user, '$nama', '$nip', '$jenis_kelamin', '$alamat', '$no_hp')");

        if ($simpan) {
            echo "<script>alert('Data pembina berhasil ditambahkan'); window.location.href='dashboard_admin.php?page=data_pembina';</script>";
        } else {
            echo "<script>alert('Gagal menyimpan data pembina'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Gagal membuat akun user'); window.history.back();</script>";
    }
}
?>

<!-- Header -->
<div class="bg-primary text-white px-4 px-md-5 pt-5 pb-4 shadow" style="border-radius: 0 0 40px 40px; min-height: 170px;">
    <div class="container">
        <h3 class="fw-semibold mb-1" style="font-size: 1.6rem;">Tambah Pembina</h3>
        <p class="mb-0">Lengkapi data pembina baru untuk ditambahkan ke sistem PIK-R.</p>
    </div>
</div>

<!-- Form -->
<div class="container px-3 px-md-5 pb-5" style="margin-top: -30px;">
    <div class="card border-0 shadow-sm mx-auto" style="max-width: 700px;">
        <div class="card-body px-4 py-4">
            <form method="POST" novalidate>
                <h5 class="fw-semibold text-center mb-4 text-primary">Form Tambah Pembina</h5>

                <div class="mb-3">
                    <label class="form-label fw-semibold">NIP</label>
                    <input type="text" name="nip" class="form-control" placeholder="Masukkan NIP" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama</label>
                    <input type="text" name="nama" class="form-control" placeholder="Nama lengkap pembina" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Jenis Kelamin</label>
                    <div class="d-flex gap-4 mt-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" value="L" id="lk" required>
                            <label class="form-check-label" for="lk">Laki-laki</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" value="P" id="pr" required>
                            <label class="form-check-label" for="pr">Perempuan</label>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Alamat</label>
                    <textarea class="form-control" name="alamat" rows="2" placeholder="Alamat lengkap"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">No. HP</label>
                    <input type="text" name="no_hp" class="form-control" placeholder="Nomor WhatsApp / Telp. aktif">
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="dashboard_admin.php?page=data_pembina" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
include '../confiq/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

if (isset($_POST['simpan'])) {
    $nama       = $_POST['nama'];
    $jabatan    = $_POST['jabatan'];
    $deskripsi  = $_POST['deskripsi'];
    $urutan     = $_POST['urutan'];
    $status     = $_POST['status'];
    $tanggal    = date('Y-m-d H:i:s');

    // Upload foto
    $foto = $_FILES['foto']['name'];
    $tmp  = $_FILES['foto']['tmp_name'];
    $path = "../upload/struktur/";

    if (!empty($foto)) {
        $ext = pathinfo($foto, PATHINFO_EXTENSION);
        $nama_foto = 'struktur_' . time() . '.' . $ext;
        move_uploaded_file($tmp, $path . $nama_foto);
    } else {
        $nama_foto = null;
    }

    $simpan = mysqli_query($conn, "INSERT INTO struktur_organisasi (nama, jabatan, foto, deskripsi, urutan, status, tanggal_input)
                                    VALUES ('$nama', '$jabatan', '$nama_foto', '$deskripsi', '$urutan', '$status', '$tanggal')");

    if ($simpan) {
        echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data struktur berhasil ditambahkan!',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'dashboard_admin.php?page=data_struktur';
        });
    </script>";
    } else {
        echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Gagal menyimpan data!',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Kembali'
        });
    </script>";
    }
}
?>

<!-- Header -->
<div class="bg-primary text-white px-5 pt-5 pb-4 mb-4 shadow" style="border-radius: 0 0 40px 40px; margin-top: -60px; min-height: 150px;">
    <h3 class="fw-semibold mb-1" style="font-size: 1.5rem; margin-top: 20px;">Tambah Anggota Struktur</h3>
    <p class="mb-0">Form untuk menambahkan data struktur organisasi PIK-R.</p>
</div>

<!-- Form -->
<div class="container px-4 pb-5">
    <div class="card border-0 shadow rounded-4">
        <div class="card-body px-4 py-4">

            <form method="post" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nama</label>
                        <input type="text" name="nama" required class="form-control shadow-sm">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Jabatan</label>
                        <input type="text" name="jabatan" required class="form-control shadow-sm">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-semibold">Deskripsi (opsional)</label>
                        <textarea name="deskripsi" class="form-control shadow-sm" rows="3"></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Urutan Tampil</label>
                        <input type="number" name="urutan" value="0" class="form-control shadow-sm">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select shadow-sm">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Foto</label>
                        <input type="file" name="foto" accept="image/*" class="form-control shadow-sm">
                        <small class="text-muted">Format: JPG/PNG. Ukuran max: 2MB.</small>
                    </div>
                </div>

                <button type="submit" name="simpan" class="btn btn-primary fw-semibold shadow-sm px-4">
                    <i class="bi bi-save"></i> Simpan
                </button>
                <a href="dashboard_admin.php?page=data_struktur" class="btn btn-secondary shadow-sm">Kembali</a>
            </form>

        </div>
    </div>
</div>
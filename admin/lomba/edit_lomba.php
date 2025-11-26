<?php
include '../../confiq/koneksi.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<script>alert('ID tidak ditemukan!'); window.location.href='../dashboard_admin.php?page=data_lomba';</script>";
    exit;
}

// Ambil data dari database
$query = mysqli_query($conn, "SELECT * FROM lomba WHERE id_lomba = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='../dashboard_admin.php?page=data_lomba';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lomba = htmlspecialchars($_POST['nama_lomba']);

    $update = mysqli_query($conn, "UPDATE lomba SET nama_lomba = '$nama_lomba' WHERE id_lomba = '$id'");

    if ($update) {
        echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data lomba berhasil diperbarui!',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = '../dashboard_admin.php?page=data_lomba';
        });
    </script>";
    } else {
        echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Gagal memperbarui data!',
            confirmButtonText: 'Coba Lagi'
        });
    </script>";
    }
}
?>

<div class="bg-primary text-white px-5 pt-5 pb-4 mb-4 shadow" style="border-radius: 0 0 40px 40px; margin-top: -60px; min-height: 150px;">
    <h3 class="fw-semibold mb-1" style="font-size: 1.5rem; margin-top: 20px;">Edit Lomba</h3>
    <p class="mb-0">Perbarui data lomba yang tersedia.</p>
</div>


<div class="card mt-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Edit Data Lomba</h5>
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="mb-3">
                <label for="nama_lomba" class="form-label">Nama Lomba</label>
                <input type="text" name="nama_lomba" id="nama_lomba" class="form-control" required value="<?= htmlspecialchars($data['nama_lomba']) ?>">
            </div>
            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
            <a href="../dashboard_admin.php?page=data_lomba" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
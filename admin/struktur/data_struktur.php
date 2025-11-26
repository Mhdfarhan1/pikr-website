<?php
include '../confiq/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

$query = mysqli_query($conn, "SELECT * FROM struktur_organisasi ORDER BY urutan ASC, tanggal_input DESC");
?>

<!-- Header -->
<div class="bg-primary text-white px-5 pt-5 pb-4 mb-4 shadow" style="border-radius: 0 0 40px 40px; margin-top: -60px; min-height: 150px;">
    <h3 class="fw-semibold mb-1" style="font-size: 1.5rem; margin-top: 20px;">Struktur Organisasi</h3>
    <p class="mb-0">Data struktur organisasi PIK-R yang akan tampil di halaman utama.</p>
</div>

<!-- Konten -->
<div class="container px-4 pb-5">
    <div class="card shadow border-0 rounded-4">

        <!-- Tombol Tambah -->
        <div class="card-header bg-white d-flex justify-content-between align-items-center px-4 py-3 border-bottom">
            <h6 class="mb-0 fw-bold text-dark">Galeri Struktur</h6>
            <a href="dashboard_admin.php?page=tambah_struktur" class="btn btn-sm btn-primary fw-semibold shadow-sm">
                <i class="bi bi-plus-circle"></i> Tambah Anggota
            </a>
        </div>

        <!-- Galeri -->
        <div class="card-body px-4 py-4">
            <div class="row justify-content-center align-items-start text-center">

                <?php if (mysqli_num_rows($query) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <img src="../upload/struktur/<?= htmlspecialchars($row['foto']) ?>" class="img-fluid grayscale-hover rounded shadow-sm mb-2" alt="Struktur"
                                 style="max-height: 160px;">
                            <h6 class="fw-semibold mb-0"><?= htmlspecialchars($row['nama']) ?></h6>
                            <small class="text-muted d-block mb-1"><?= htmlspecialchars($row['jabatan']) ?></small>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="dashboard_admin.php?page=edit_struktur&id=<?= $row['id'] ?>" class="btn btn-sm btn-warning text-white shadow-sm" style="font-size: 0.75rem;">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <a href="#" class="btn btn-sm btn-danger text-white shadow-sm btn-hapus-struktur" data-id="<?= $row['id'] ?>" style="font-size: 0.75rem;">
                                    <i class="bi bi-trash"></i> Hapus
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center text-muted py-3">
                        Belum ada data struktur ditambahkan.
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<!-- SweetAlert & Script Hapus -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.btn-hapus-struktur').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const id = this.dataset.id;

            Swal.fire({
                title: 'Yakin hapus anggota ini?',
                text: "Data akan dihapus permanen dari sistem!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'struktur/hapus_struktur.php?id=' + id;
                }
            });
        });
    });
</script>

<!-- Styling tambahan -->
<style>
    .grayscale-hover {
        filter: grayscale(100%);
        transition: 0.3s ease;
    }

    .grayscale-hover:hover {
        filter: grayscale(0%);
        transform: scale(1.05);
    }
</style>

<?php
include '../confiq/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

// Ambil data kerja sama dari database
$query = mysqli_query($conn, "SELECT * FROM kerja_sama ORDER BY tanggal_input DESC");
?>

<!-- Header -->
<div class="bg-primary text-white px-5 pt-5 pb-4 mb-4 shadow" style="border-radius: 0 0 40px 40px; margin-top: -60px; min-height: 150px;">
    <h3 class="fw-semibold mb-1" style="font-size: 1.5rem; margin-top: 20px;">Data Kerja Sama</h3>
    <p class="mb-0">Menampilkan daftar kerja sama yang dilakukan oleh PIK-R.</p>
</div>

<!-- Konten -->
<div class="container px-4 pb-5">
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center px-4 py-3 border-bottom">
            <h6 class="mb-0 fw-bold text-dark">Daftar Kerja Sama</h6>
            <a href="dashboard_admin.php?page=tambah_kerjasama" class="btn btn-sm btn-primary fw-semibold shadow-sm">
                <i class="bi bi-plus-circle"></i> Tambah Kerja Sama
            </a>
        </div>

        <div class="card-body px-4 py-4">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered table-hover table-striped">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th>Foto</th>
                            <th>Tanggal Input</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        while ($data = mysqli_fetch_assoc($query)) : ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= htmlspecialchars($data['nama_instansi']) ?></td>
                                <td><?= htmlspecialchars($data['deskripsi']) ?></td>
                                <td class="text-center">
                                    <?php if ($data['gambar']) : ?>
                                        <img src="../upload/kerjasama/<?= htmlspecialchars($data['gambar']) ?>" alt="Foto" style="max-height: 60px;">
                                    <?php else : ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d M Y, H:i', strtotime($data['tanggal_input'])) ?></td>
                                <td class="text-center">
                                    <a href="dashboard_admin.php?page=edit_kerjasama&id=<?= $data['id'] ?>" class="btn btn-sm btn-warning text-white">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger btn-hapus" data-id="<?= $data['id'] ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert & Script Hapus -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.btn-hapus').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;

            Swal.fire({
                title: 'Yakin hapus data ini?',
                text: "Data akan dihapus permanen dari sistem!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'kerjasama/hapus_kerjasama.php?id=' + id;
                }
            });
        });
    });
</script>

<!-- DataTables CDN -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>
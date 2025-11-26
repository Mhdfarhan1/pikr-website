<?php
include '../confiq/koneksi.php';

// Ambil data lomba
$query = mysqli_query($conn, "SELECT * FROM lomba ORDER BY id_lomba DESC");
?>

<!-- Header -->
<div class="bg-primary text-white px-5 pt-5 pb-4 mb-4 shadow" style="border-radius: 0 0 40px 40px; margin-top: -60px; min-height: 150px;">
    <h3 class="fw-semibold mb-1" style="font-size: 1.5rem; margin-top: 20px;">Data Lomba</h3>
    <p class="mb-0">Berikut adalah daftar lomba yang tersedia untuk didaftarkan oleh peserta.</p>
</div>

<!-- Konten -->
<div class="container px-4 pb-5">
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center px-4 py-3 border-bottom">
            <h6 class="mb-0 fw-bold text-dark">Daftar Lomba</h6>
            <a href="dashboard_admin.php?page=tambah_lomba" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Lomba
            </a>
        </div>

        <div class="card-body px-4 py-4">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered table-hover table-striped">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama Lomba</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        while ($data = mysqli_fetch_assoc($query)) : ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= htmlspecialchars($data['nama_lomba']) ?></td>
                                <td class="text-center">
                                    <a href="dashboard_admin.php?page=edit_lomba&id=<?= $data['id_lomba'] ?>" class="btn btn-sm btn-warning text-white">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger btn-hapus" data-id="<?= $data['id_lomba'] ?>">
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
                title: 'Yakin hapus lomba ini?',
                text: "Data tidak bisa dikembalikan setelah dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'lomba/hapus_lomba.php?id=' + id;
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

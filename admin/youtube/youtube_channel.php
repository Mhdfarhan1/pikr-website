<?php
include '../confiq/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

// Ambil data video YouTube dari database
$query = mysqli_query($conn, "SELECT * FROM youtube ORDER BY tanggal_input DESC");
?>

<!-- Header -->
<div class="bg-primary text-white px-5 pt-5 pb-4 mb-4 shadow" style="border-radius: 0 0 40px 40px; margin-top: -60px; min-height: 150px;">
    <h3 class="fw-semibold mb-1" style="font-size: 1.5rem; margin-top: 20px;">Data YouTube</h3>
    <p class="mb-0">Menampilkan daftar video terbaru di channel YouTube PIK-R Request.</p>
</div>

<!-- Konten -->
<div class="container px-4 pb-5">
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center px-4 py-3 border-bottom">
            <h6 class="mb-0 fw-bold text-dark">Daftar Video</h6>
            <a href="dashboard_admin.php?page=tambah_youtube" class="btn btn-sm btn-primary fw-semibold shadow-sm">
                <i class="bi bi-plus-circle"></i> Tambah Video
            </a>
        </div>

        <div class="card-body px-4 py-4">
            <div class="table-responsive">
                <table id="dataTableYoutube" class="table table-bordered table-hover table-striped">
                    <thead class="table-success text-center">
                        <tr>
                            <th>No</th>
                            <th>Judul Video</th>
                            <th>Link YouTube</th>
                            <th>Tgl Upload</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        while ($data = mysqli_fetch_assoc($query)) : ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= htmlspecialchars($data['judul']) ?></td>
                                <td class="text-center">
                                    <?php if ($data['link']) : ?>
                                        <a href="<?= htmlspecialchars($data['link']) ?>" target="_blank" class="btn btn-sm btn-info text-white">
                                            <i class="bi bi-youtube"></i> Tonton
                                        </a>
                                    <?php else : ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d M Y, H:i', strtotime($data['tanggal_upload'])) ?></td>
                                <td class="text-center">
                                    <a href="dashboard_admin.php?page=edit_youtube&id=<?= $data['id'] ?>" class="btn btn-sm btn-warning text-white">
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

<!-- SweetAlert + jQuery + DataTables -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#dataTableYoutube').DataTable({
            language: { url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" }
        });

        // Hapus pakai SweetAlert
        $('.btn-hapus').click(function () {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Yakin hapus video ini?',
                text: "Data akan dihapus permanen dari sistem!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'youtube/hapus_youtube.php?id=' + id;
                }
            });
        });
    });
</script>

<?php
include '../confiq/koneksi.php';
date_default_timezone_set('Asia/Jakarta');
$query = mysqli_query($conn, "SELECT * FROM galeri ORDER BY tanggal_upload DESC");
?>

<!-- Header -->
<div class="bg-primary text-white px-5 pt-5 pb-4 mb-4 shadow" style="border-radius: 0 0 40px 40px; margin-top: -60px; min-height: 150px;">
    <h3 class="fw-semibold mb-1" style="font-size: 1.5rem; margin-top: 20px;">Data Galeri</h3>
    <p class="mb-0">Kelola dokumentasi kegiatan dan galeri foto PIK-R.</p>
</div>

<!-- Konten -->
<div class="container px-4 pb-5">
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 px-4 border-bottom">
            <h6 class="mb-2 fw-bold text-dark">Tabel Data Galeri</h6>
            <a href="dashboard_admin.php?page=tambah_galeri" class="btn btn-sm btn-primary fw-semibold shadow-sm">
                <i class="bi bi-plus-circle"></i> Tambah Galeri
            </a>
        </div>

        <div class="card-body px-4">
            <div class="table-responsive">
                <table id="tabel-galeri" class="table table-hover table-striped table-bordered rounded-3 overflow-hidden">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Judul</th>
                            <th>Gambar</th>
                            <th>Tanggal Upload</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle">
                        <?php $no = 1; ?>
                        <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['judul']) ?></td>
                                <td>
                                    <?php if (!empty($row['gambar']) && file_exists('../upload/galeri/' . $row['gambar'])) : ?>
                                        <img src="../upload/galeri/<?= $row['gambar'] ?>" width="70" class="rounded shadow-sm">
                                    <?php else : ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d-m-Y H:i', strtotime($row['tanggal_upload'])) ?></td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="dashboard_admin.php?page=edit_galeri&id=<?= $row['id'] ?>" class="btn btn-edit btn-action shadow-sm">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        <a href="#" class="btn btn-delete btn-action shadow-sm text-white" data-id="<?= $row['id'] ?>">
                                            <i class="bi bi-trash"></i> Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        <?php if (mysqli_num_rows($query) == 0) : ?>
                            <tr>
                                <td colspan="5" class="text-muted text-center">Belum ada data galeri.</td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- JS & CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Script DataTables + SweetAlert -->
<script>
    $(document).ready(function() {
        $('#tabel-galeri').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
                searchPlaceholder: "Cari galeri...",
                search: "_INPUT_"
            },
            pageLength: 10
        });

        $('.btn-delete').click(function(e) {
            e.preventDefault();
            const id = $(this).data('id');

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data galeri akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'galeri/hapus_galeri.php?id=' + id;
                }
            });
        });
    });
</script>

<!-- Styling -->
<style>
    #tabel-galeri thead th {
        background: linear-gradient(135deg, #3b82f6, #6366f1);
        color: #fff;
        font-size: 0.85rem;
        text-transform: uppercase;
        font-weight: 600;
    }

    #tabel-galeri tbody td {
        font-size: 0.85rem;
        vertical-align: middle;
        word-wrap: break-word;
        white-space: normal;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .btn-edit {
        background-color: #fbbf24;
        color: #fff;
    }

    .btn-edit:hover {
        background-color: #f59e0b;
    }

    .btn-delete {
        background-color: #ef4444;
        color: #fff;
    }

    .btn-delete:hover {
        background-color: #dc2626;
    }
</style>

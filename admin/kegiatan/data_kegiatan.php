<?php
date_default_timezone_set('Asia/Jakarta');
include '../confiq/koneksi.php';
$query = mysqli_query($conn, "SELECT * FROM kegiatan ORDER BY tanggal_kegiatan DESC");
?>

<!-- Header -->
<div class="bg-primary text-white px-5 pt-5 pb-4 mb-4 shadow" style="border-radius: 0 0 40px 40px; margin-top: -60px; min-height: 150px;">
    <h3 class="fw-semibold mb-1" style="font-size: 1.5rem; margin-top: 20px;">Data Kegiatan</h3>
    <p class="mb-0">Kelola seluruh informasi kegiatan di sistem PIK-R.</p>
</div>

<!-- Konten -->
<div class="container px-4 pb-5">
    <div class="card shadow border-0 rounded-4">
        <!-- Header Card: Title + Aksi -->
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2 py-3 px-4 border-bottom">
            <h6 class="mb-2 fw-bold text-dark">Tabel Data Kegiatan</h6>
            <div class="d-flex flex-wrap gap-2">
                <!-- Tombol PDF akan muncul otomatis via DataTables -->
                <a href="dashboard_admin.php?page=tambah_kegiatan" class="btn btn-sm btn-primary fw-semibold shadow-sm">
                    <i class="bi bi-plus-circle"></i> Tambah Kegiatan
                </a>
            </div>
        </div>

        <!-- Body Card: Tabel -->
        <div class="card-body px-4">
            <div class="table-responsive">
                <table id="tabel-kegiatan" class="table table-hover table-striped table-bordered rounded-3 overflow-hidden w-100">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Judul</th>
                            <th>Tanggal Kegiatan</th>
                            <th>Tempat</th>
                            <th>Gambar</th>
                            <th>Tautan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle">
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($query)) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['judul']) ?></td>
                                <td><?= date('d-m-Y H:i', strtotime($row['tanggal_kegiatan'])) ?></td>
                                <td><?= htmlspecialchars($row['tempat']) ?></td>
                                <td>
                                    <?php if (!empty($row['gambar'])) : ?>
                                       <img src="../upload/kegiatan/<?= $row['gambar'] ?>" width="70" class="rounded shadow-sm">
                                    <?php else : ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-break">
                                    <?php if (!empty($row['tautan'])) : ?>
                                        <a href="<?= htmlspecialchars($row['tautan']) ?>" target="_blank" class="text-primary fw-semibold text-decoration-underline"><?= htmlspecialchars($row['tautan']) ?></a>
                                    <?php else : ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="dashboard_admin.php?page=edit_kegiatan&id=<?= $row['id'] ?>" class="btn btn-edit btn-action shadow-sm">
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
                            <tr><td colspan="7" class="text-muted text-center">Belum ada data kegiatan.</td></tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- CSS DataTables & SweetAlert -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />

<!-- JS DataTables, Buttons, SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Inisialisasi DataTable -->
<script>
    $(document).ready(function() {
        $('#tabel-kegiatan').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'pdfHtml5',
                    text: '<i class="bi bi-file-earmark-pdf-fill"></i> Ekspor PDF',
                    className: 'btn btn-danger btn-sm fw-semibold shadow-sm',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    title: 'Laporan Data Kegiatan PIK-R',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 5] // tanpa gambar & aksi
                    }
                }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
                searchPlaceholder: "Cari kegiatan...",
                search: "_INPUT_"
            },
            pageLength: 10
        });

        // Aksi Hapus
        $('.btn-delete').click(function(e) {
            e.preventDefault();
            const id = $(this).data('id');

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data kegiatan akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'kegiatan/hapus_kegiatan.php?id=' + id;
                }
            });
        });
    });
</script>

<!-- Styling -->
<style>
    #tabel-kegiatan thead th {
        background: linear-gradient(135deg, #3b82f6, #6366f1);
        color: #fff;
        font-size: 0.85rem;
        text-transform: uppercase;
        font-weight: 600;
    }

    #tabel-kegiatan tbody td {
        font-size: 0.85rem;
        vertical-align: middle;
        white-space: normal;
        word-wrap: break-word;
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

    .dt-buttons {
        margin-bottom: 15px;
    }
</style>

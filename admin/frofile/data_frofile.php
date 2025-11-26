<?php
include '../confiq/koneksi.php';

// Ambil data frofile
$query = mysqli_query($conn, "SELECT * FROM frofile");
?>

<!-- CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />

<!-- Header -->
<div class="bg-primary text-white px-5 pt-5 pb-4 mb-4 shadow" style="border-radius: 0 0 40px 40px; margin-top: -60px; min-height: 150px;">
    <h3 class="fw-semibold mb-1" style="font-size: 1.5rem; margin-top: 20px;">Data Frofile</h3>
    <p class="mb-0">Kelola data kepala sekolah & pembina PIK-R.</p>
</div>

<!-- Konten -->
<div class="container px-4 pb-5">
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 px-4 border-bottom">
            <h6 class="mb-2 fw-bold text-dark">Tabel Data Frofile</h6>
            <a href="dashboard_admin.php?page=tambah_frofile" class="btn btn-sm btn-primary fw-semibold shadow-sm">
                <i class="bi bi-person-plus-fill"></i> Tambah Frofile
            </a>
        </div>

        <div class="card-body px-4">
            <div class="table-responsive">
                <table id="tabel-frofile" class="table table-hover table-striped table-bordered rounded-3 overflow-hidden" style="width:100%;">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle">
                        <?php $no = 1; ?>
                        <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <img src="../assets/img/frofile/<?= htmlspecialchars($row['foto']) ?>" alt="<?= htmlspecialchars($row['nama']) ?>" style="width: 60px; height: auto; border-radius: 8px;">
                                </td>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td><?= htmlspecialchars($row['jabatan']) ?></td>
                                <td class="text-start" style="max-width: 400px;"><?= nl2br(htmlspecialchars($row['deskripsi'])) ?></td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="dashboard_admin.php?page=edit_frofile&id=<?= $row['id'] ?>"
                                            class="btn btn-edit btn-action shadow-sm" title="Edit Frofile">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        <a href="#"
                                            class="btn btn-delete btn-action shadow-sm text-white"
                                            data-id="<?= $row['id'] ?>" title="Hapus Frofile">
                                            <i class="bi bi-trash"></i> Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        <?php if (mysqli_num_rows($query) == 0) : ?>
                            <tr>
                                <td colspan="6" class="text-muted text-center">Belum ada data frofile.</td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://unpkg.com/feather-icons"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $('#tabel-frofile').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
                searchPlaceholder: "Cari frofile...",
                search: "_INPUT_",
                lengthMenu: "_MENU_ entri per halaman",
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                infoFiltered: "(disaring dari _MAX_ total entri)"
            },
            pageLength: 10,
            lengthMenu: [
                [5, 10, 25, 50],
                [5, 10, 25, 50]
            ],
            dom: '<"row mb-3"<"col-sm-6"l><"col-sm-6 text-end"f>>' +
                '<"row"<"col-sm-12"tr>>' +
                '<"row mt-3"<"col-sm-6"i><"col-sm-6 text-end"p>>'
        });

        feather.replace();

        $('.btn-delete').click(function(e) {
            e.preventDefault();
            const id = $(this).data('id');

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data frofile akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'dashboard_admin.php?page=hapus_frofile&id=' + id;
                }
            });
        });
    });
</script>

<!-- Styling Modern -->
<style>
    #tabel-frofile thead th {
        background: linear-gradient(135deg, #3b82f6, #6366f1);
        color: #fff;
        font-size: 0.85rem;
        text-transform: uppercase;
        font-weight: 600;
        border: none;
        white-space: nowrap;
        /* supaya header tidak wrap */
    }

    #tabel-frofile tbody td {
        font-size: 0.85rem;
        vertical-align: middle;
        word-wrap: break-word;
        /* pecah kata kalau terlalu panjang */
        white-space: normal !important;
        /* supaya teks bisa wrap */
    }

    /* Khusus kolom Deskripsi (kolom ke-5) */
    #tabel-frofile tbody td:nth-child(5) {
        max-width: 400px;
        /* batasi lebar kolom */
        text-align: left;
        /* rata kiri agar enak dibaca */
        overflow-wrap: break-word;
        /* modern word wrap */
        white-space: normal;
        /* pastikan wrap */
    }

    #tabel-frofile tbody tr:hover {
        background-color: #f0f8ff;
        transition: all 0.3s ease;
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

    .dataTables_length select,
    .dataTables_filter input {
        border-radius: 0.5rem;
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .dataTables_filter input {
        width: auto;
    }
</style>
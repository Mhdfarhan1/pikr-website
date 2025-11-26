<?php
include '../confiq/koneksi.php';

// Ambil data siswa + nama lengkap dari tabel users
$query = mysqli_query($conn, "
    SELECT siswa.*, users.nama_lengkap 
    FROM siswa 
    LEFT JOIN users ON siswa.id_user = users.id
");
?>

<!-- CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />

<!-- Header -->
<div class="bg-primary text-white px-5 pt-5 pb-4 mb-4 shadow" style="border-radius: 0 0 40px 40px; margin-top: -60px; min-height: 150px;">
    <h3 class="fw-semibold mb-1" style="font-size: 1.5rem; margin-top: 20px;">Data Siswa</h3>
    <p class="mb-0">Lihat dan kelola seluruh data siswa yang terdaftar dalam sistem PIK-R.</p>
</div>

<!-- Konten -->
<div class="container px-4 pb-5">
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 px-4 border-bottom">
            <h6 class="mb-2 fw-bold text-dark">Tabel Data Siswa</h6>
            <a href="dashboard_admin.php?page=tambah_siswa" class="btn btn-sm btn-primary fw-semibold shadow-sm">
                <i class="bi bi-person-plus-fill"></i> Tambah Siswa
            </a>
        </div>

        <div class="card-body px-4">
            <div class="table-responsive">
                <table id="tabel-siswa" class="table table-hover table-striped table-bordered rounded-3 overflow-hidden" style="width:100%;">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Jenis Kelamin</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle">
                        <?php $no = 1; ?>
                        <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nis']) ?></td>
                                <td><?= htmlspecialchars($row['nama_lengkap'] ?? $row['nama_siswa']) ?></td>
                                <td><?= htmlspecialchars($row['kelas']) ?></td>
                                <td>
                                    <?php if ($row['jenis_kelamin'] === 'L') : ?>
                                        <span class="badge-gender badge-laki">Laki-laki</span>
                                    <?php elseif ($row['jenis_kelamin'] === 'P') : ?>
                                        <span class="badge-gender badge-perempuan">Perempuan</span>
                                    <?php else : ?>
                                        <span class="badge-gender badge-unknown">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="dashboard_admin.php?page=edit_siswa&id=<?= $row['id'] ?>"
                                            class="btn btn-edit btn-action shadow-sm" title="Edit Siswa">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        <a href="#" 
                                            class="btn btn-delete btn-action shadow-sm text-white"
                                            data-id="<?= $row['id'] ?>"
                                            title="Hapus Siswa">
                                            <i class="bi bi-trash"></i> Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        <?php if (mysqli_num_rows($query) == 0) : ?>
                            <tr>
                                <td colspan="6" class="text-muted text-center">Belum ada data siswa.</td>
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
    $(document).ready(function () {
        $('#tabel-siswa').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
                searchPlaceholder: "Cari siswa...",
                search: "_INPUT_",
                lengthMenu: "_MENU_ entri per halaman",
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                infoFiltered: "(disaring dari _MAX_ total entri)"
            },
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
            dom: '<"row mb-3"<"col-sm-6"l><"col-sm-6 text-end"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row mt-3"<"col-sm-6"i><"col-sm-6 text-end"p>>'
        });

        feather.replace();

        $('.btn-delete').click(function (e) {
            e.preventDefault();
            const id = $(this).data('id');

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data siswa akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'dashboard_admin.php?page=hapus_siswa&id=' + id;
                }
            });
        });
    });
</script>

<!-- Styling Modern -->
<style>
    #tabel-siswa thead th {
        background: linear-gradient(135deg, #3b82f6, #6366f1);
        color: #fff;
        font-size: 0.85rem;
        text-transform: uppercase;
        font-weight: 600;
        border: none;
    }

    #tabel-siswa tbody td {
        font-size: 0.85rem;
        vertical-align: middle;
    }

    #tabel-siswa tbody tr:hover {
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

    .badge-gender {
        font-size: 0.75rem;
        padding: 5px 12px;
        border-radius: 50px;
        font-weight: 500;
        color: #fff;
    }

    .badge-laki {
        background-color: #3b82f6;
    }

    .badge-perempuan {
        background-color: #ec4899;
    }

    .badge-unknown {
        background-color: #6b7280;
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

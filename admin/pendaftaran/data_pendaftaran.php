<?php
include '../confiq/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

$query = mysqli_query($conn, "SELECT pl.*, l.nama_lomba 
    FROM pendaftaran_lomba pl 
    JOIN lomba l ON pl.id_lomba = l.id_lomba 
    ORDER BY pl.tanggal_daftar DESC");
?>

<!-- Header -->
<div class="bg-primary text-white px-5 pt-5 pb-4 mb-4 shadow" style="border-radius: 0 0 40px 40px; margin-top: -60px; min-height: 150px;">
    <h3 class="fw-semibold mb-1" style="font-size: 1.5rem; margin-top: 20px;">Data Pendaftaran Lomba</h3>
    <p class="mb-0">Berikut adalah daftar peserta yang telah mendaftar dalam lomba PIK-R REQUEST.</p>
</div>

<!-- Konten -->
<div class="container px-4 pb-5">
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center px-4 py-3 border-bottom">
            <h6 class="mb-0 fw-bold text-dark">Daftar Peserta</h6>
        </div>

        <div class="card-body px-4 py-4">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered table-hover table-striped w-100">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama Peserta</th>
                            <th>Kelas</th>
                            <th>Kontak</th>
                            <th>Lomba</th>
                            <th>Nama Anggota (Opsional)</th>
                            <th>Tanggal Daftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($data = mysqli_fetch_assoc($query)) : ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= htmlspecialchars($data['nama_peserta']) ?></td>
                                <td><?= htmlspecialchars($data['kelas']) ?></td>
                                <td><?= htmlspecialchars($data['kontak']) ?></td>
                                <td><?= htmlspecialchars($data['nama_lomba']) ?></td>
                                <td><?= $data['anggota_kelompok'] ? htmlspecialchars($data['anggota_kelompok']) : '<span class="text-muted">-</span>' ?></td>
                                <td><?= date('d M Y, H:i', strtotime($data['tanggal_daftar'])) ?></td>
                                <td class="text-center">
                                    <a href="dashboard_admin.php?page=edit_pendaftaran&id=<?= $data['id'] ?>" class="btn btn-sm btn-warning text-white">
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

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.btn-hapus').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            Swal.fire({
                title: 'Yakin hapus data ini?',
                text: "Data tidak bisa dikembalikan setelah dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'pendaftaran/hapus_pendaftaran.php?id=' + id;
                }
            });
        });
    });
</script>

<!-- DataTables & Ekspor PDF -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<!-- Inisialisasi DataTables -->
<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        responsive: true,
        dom: "<'row mb-3'<'col-md-6'B><'col-md-6'f>>" +
             "<'row'<'col-12'tr>>" +
             "<'row mt-2'<'col-md-5'i><'col-md-7'p>>",
        buttons: [
            {
                extend: 'pdfHtml5',
                text: '<i class="bi bi-file-earmark-pdf-fill me-1"></i> Ekspor PDF',
                className: 'btn btn-danger fw-semibold btn-sm shadow-sm rounded-pill',
                orientation: 'landscape',
                pageSize: 'A4',
                title: 'Laporan Pendaftaran Lomba PIK-R',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6] // Tanpa kolom aksi (index ke-7)
                },
                customize: function(doc) {
                    doc.styles.title = {
                        alignment: 'center',
                        fontSize: 16,
                        bold: true,
                        margin: [0, 10, 0, 20]
                    };
                    doc.styles.tableHeader = {
                        fillColor: '#0d6efd',
                        color: 'white',
                        bold: true,
                        alignment: 'center'
                    };
                    doc.content[1].layout = {
                        hLineWidth: function(i, node) { return 0.5; },
                        vLineWidth: function(i, node) { return 0.5; },
                        hLineColor: function(i, node) { return '#aaa'; },
                        vLineColor: function(i, node) { return '#aaa'; }
                    };
                }
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
            searchPlaceholder: "Cari peserta...",
            search: "_INPUT_"
        },
        pageLength: 10
    });
});
</script>

<?php include '../layouts/heeder.php'; ?>
<?php include '../confiq/koneksi.php'; ?>

<!-- Font Roboto -->
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Roboto', sans-serif;
    }
</style>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function konfirmasiPendaftaran() {
        Swal.fire({
            html: `
                <div style="text-align: center;">
                    <h4 style="margin: 0 0 5px; font-weight: bold; color: #053e95ff;">PENDAFTARAN PIK-R REQUEST</h4>
                    <h6 style="margin: 0 0 5px; font-weight: bold; color: #023177ff;">
                        SMA NEGERI 1 TASIK PUTRI PUYU
                    </h6>
                    <p style="font-size: 14px; margin-top: 10px; line-height: 1.5;">
                        Kami mengundang Anda untuk berpartisipasi dalam kegiatan yang akan diselenggarakan oleh <strong>PIK-R REQUEST</strong>.<br>
                        Silakan isi formulir pendaftaran dengan data yang lengkap dan benar.<br>
                        Data Anda akan digunakan sebagai dasar validasi calon Anggota.<br>
                        Pastikan Anda telah membaca seluruh persyaratan sebelum melanjutkan.
                    </p>
                    <p style="font-size: 13px; margin-top: 15px; font-style: italic; color: #555;">
                        "Terima kasih atas partisipasi dan semangat Anda dalam berorganisasi."
                    </p>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Daftar Sekarang',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Formulir Sedang Dibuka...',
                    text: 'Silakan isi Google Form di tab baru yang muncul.',
                    timer: 3000,
                    showConfirmButton: false
                });

                setTimeout(() => {
                    window.open('https://forms.gle/STJvfScVx3PByyYK9', '_blank');
                }, 1000);
            }
        });
    }
</script>

<!-- Breadcrumb -->
<section class="py-3" style="margin-top: 100px;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="<?= $base_url ?>index.php" class="text-decoration-none text-primary fw-semibold">
                        <i class="bi bi-house-door-fill me-1"></i> Beranda
                    </a>
                </li>
                <li class="breadcrumb-item active text-muted" aria-current="page">Pendaftaran</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Konten Pendaftaran -->
<section class="container py-5">
    <div class="row">
        <!-- Konten Utama -->
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h4 class="fw-bold text-primary mb-3">
                        <i class="bi bi-calendar-event-fill me-2"></i> Formulir Pendaftaran Anggota
                    </h4>
                    <p class="text-muted" style="text-align: justify;">
                        <strong>Assalamu’alaikum warahmatullahi wabarakatuh</strong> 👋, <br>
                        Salam GenRe! Halo GenRe-ners!
                    </p>
                    <p class="text-muted" style="text-align: justify;">
                        Kami dengan bangga mengundang kamu untuk bergabung menjadi bagian dari <strong>Organisasi PIK-R SMAN 1 Tasik Putri Puyu</strong>. Organisasi ini hadir sebagai wadah pengembangan karakter, peningkatan keterampilan, serta mempererat solidaritas dan kebersamaan antar anggota remaja.
                    </p>
                    <p class="text-muted" style="text-align: justify;">
                        Yuk isi formulir pendaftaran di bawah ini dengan <strong>data yang lengkap dan benar</strong>. Informasi ini akan digunakan untuk proses seleksi dan validasi keanggotaan PIK-R.
                    </p>
                    <h6 class="fw-semibold">📝 Syarat Pendaftaran Anggota:</h6>
                    <ul class="text-muted mb-4">
                        <li>Siswa aktif SMAN 1 Tasik Putri Puyu</li>
                        <li>Siap berkontribusi dalam kegiatan PIK-R</li>
                        <li>Mendapat izin dari orang tua/wali</li>
                        <li>Mengisi formulir pendaftaran paling lambat sebelum batas waktu yang ditentukan</li>
                    </ul>
                    <button onclick="konfirmasiPendaftaran()" class="btn btn-primary px-4 py-2 rounded-3 shadow-sm">
                        <i class="bi bi-pencil-square me-2"></i>Isi Formulir Pendaftaran
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Info Kontak -->
            <div class="mb-4">
                <h6 class="fw-bold text-dark">
                    <i class="bi bi-info-circle-fill me-1"></i> Informasi Kontak
                </h6>
                <div class="bg-white p-3 rounded-3 shadow-sm">
                    <p class="mb-1"><i class="bi bi-envelope-fill me-2"></i> pikrequest@gmail.com</p>
                    <p class="mb-1"><i class="bi bi-telephone-fill me-2"></i> 0822-4850-8887</p>
                    <p class="mb-0"><i class="bi bi-geo-alt-fill me-2"></i> Jl. Husni Tamrin</p>
                </div>
            </div>

            <!-- Pengumuman -->
            <div>
                <h6 class="fw-bold text-dark d-flex align-items-center">
                    <i class="bi bi-megaphone-fill text-danger me-2 fs-5"></i> Pengumuman Terbaru
                </h6>
                <div class="bg-white p-3 rounded-3 shadow-sm" style="max-height: 300px; overflow-y: auto;">
                    <?php
                    $pengumuman = mysqli_query($conn, "SELECT * FROM pengumuman ORDER BY tanggal DESC LIMIT 2");
                    if (mysqli_num_rows($pengumuman) > 0):
                        while ($p = mysqli_fetch_assoc($pengumuman)): ?>
                            <div class="mb-3">
                                <h6 class="fw-semibold mb-1 text-primary small">
                                    <a href="<?= $base_url ?>detail/detail_pengumuman.php?id=<?= $p['id'] ?>" class="text-decoration-none text-primary">
                                        <?= htmlspecialchars($p['judul']) ?>
                                    </a>
                                </h6>
                                <div class="text-muted small">
                                    <i class="bi bi-calendar2-week me-1"></i>
                                    <?= date('d M Y, H:i', strtotime($p['tanggal'])) ?>
                                </div>
                            </div>
                            <hr class="my-2">
                        <?php endwhile;
                    else: ?>
                        <div class="text-muted text-center small">Belum ada pengumuman.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../layouts/footer.php'; ?>

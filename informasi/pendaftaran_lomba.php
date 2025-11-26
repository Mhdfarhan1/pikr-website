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
    function konfirmasiPendaftaranLomba() {
        Swal.fire({
            html: `
                <div style="text-align: center;">
                    <h4 style="margin-bottom: 5px; font-weight: bold; color: #053e95;">PENDAFTARAN LOMBA</h4>
                    <h6 style="margin-bottom: 5px; font-weight: bold; color: #023177;">
                        SMA NEGERI 1 TASIK PUTRI PUYU
                    </h6>
                    <p style="font-size: 14px; margin-top: 10px; line-height: 1.6;">
                        Dengan semangat GenRe, kami mengundang kamu untuk menunjukkan bakat terbaikmu!<br>
                        Bergabunglah dalam <strong>perlombaan menarik</strong> yang diselenggarakan oleh <strong>PIK-R REQUEST</strong> dan jadilah bagian dari siswa yang aktif, kreatif, serta inspiratif.
                    </p>
                    <p style="font-size: 14px; margin-top: 10px;">
                        Pastikan kamu telah membaca syarat dan ketentuan lomba serta mengisi data dengan lengkap dan jujur. Data ini akan digunakan untuk validasi peserta lomba.
                    </p>
                    <p style="font-size: 13px; margin-top: 15px; font-style: italic; color: #555;">
                        "Terima kasih atas semangat dan partisipasi Anda. Jadilah bagian dari perubahan positif melalui ajang prestasi ini!"
                    </p>
                </div>
            `,
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Saya Siap Mendaftar',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.open('../user/daftar_lomba.php', '_blank');
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
                <li class="breadcrumb-item active text-muted" aria-current="page">Pendaftaran Lomba</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Konten Pendaftaran -->
<section class="container py-5">
    <div class="row">
        <!-- Konten Utama -->
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body">
                    <h4 class="fw-bold text-primary mb-3">
                        <i class="bi bi-trophy-fill me-2"></i> Formulir Pendaftaran Lomba PIK-R REQUEST
                    </h4>

                    <p class="text-muted" style="text-align: justify;">
                        <strong>Assalamu’alaikum warahmatullahi wabarakatuh</strong> 👋<br>
                        Salam GenRe untuk semua siswa hebat! 🌟
                    </p>

                    <p class="text-muted" style="text-align: justify;">
                        Kamu punya semangat juang? Punya kreativitas dan bakat terpendam? Yuk, salurkan energi positifmu dalam <strong>kompetisi seru</strong> yang diselenggarakan oleh <strong>PIK-R SMAN 1 Tasik Putri Puyu</strong>! 🏆✨
                        Lomba ini bukan sekadar ajang menang atau kalah, tapi juga ruang eksplorasi diri, pengembangan soft skill, dan memperkuat solidaritas antar siswa.
                    </p>

                    <p class="text-muted" style="text-align: justify;">
                        🧠 <strong>Bidang lomba</strong> berikut terbuka untuk seluruh siswa dan dirancang untuk menggali potensi terbaikmu dalam berbagai aspek seperti akademik, kreativitas, dan kepemimpinan:
                    </p>

                    <ul class="text-muted" style="text-align: justify; list-style: none; padding-left: 0;">
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i><strong>Duta Genre Sekolah</strong></li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i><strong>Rangking 1</strong></li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i><strong>Penyuluhan Remaja</strong></li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i><strong>Video Kreatif Genre</strong></li>
                    </ul>

                    <p class="text-muted" style="text-align: justify;">
                        Jadi, pastikan kamu mengisi <strong>formulir pendaftaran</strong> dengan benar dan lengkap. Kami menantikan semangatmu untuk menjadi generasi penerus yang berkualitas dan inspiratif.
                    </p>

                    <h6 class="fw-semibold">📌 Syarat dan Ketentuan Pendaftaran:</h6>
                    <ul class="text-muted mb-4" style="list-style: none; padding-left: 0;">
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i>Peserta adalah siswa aktif SMAN 1 Tasik Putri Puyu</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i>Mengisi formulir sesuai bidang lomba</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i>Komitmen mengikuti lomba dari awal hingga selesai</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i>Mematuhi semua peraturan panitia</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i>Pendaftaran sebelum batas waktu yang ditentukan</li>
                    </ul>

                    <div class="text-center">
                        <button onclick="konfirmasiPendaftaranLomba()" class="btn btn-primary px-4 py-2 rounded-3 shadow-sm">
                            <i class="bi bi-pencil-square me-2"></i>Isi Formulir Lomba
                        </button>
                    </div>
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

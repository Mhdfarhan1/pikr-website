<?php include '../layouts/heeder.php'; ?>
<?php include '../confiq/koneksi.php'; ?>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" rel="stylesheet" />

<style>
    body {
        font-family: 'Roboto', sans-serif;
    }

    .prestasi-thumb {
        height: 160px;
        object-fit: cover;
    }

    .prestasi-caption {
        font-size: 0.9rem;
    }

    .prestasi-deskripsi {
        font-size: 0.8rem;
        color: #555;
    }
</style>

<!-- Breadcrumb -->
<section class="py-3" style="margin-top: 100px;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="<?= $base_url ?>index.php" class="text-decoration-none text-primary fw-semibold">
                        <i class="bi bi-house-door-fill me-1"></i> Informasi
                    </a>
                </li>
                <li class="breadcrumb-item active text-muted" aria-current="page">
                    <i class="bi bi-award-fill me-1"></i> Prestasi
                </li>
            </ol>
        </nav>
    </div>
</section>

<!-- Halaman Prestasi -->
<section class="container py-5">
    <div class="row">
        <!-- Kolom Konten Prestasi -->
        <div class="col-md-8 mb-5">
            <div class="bg-white p-4 rounded-4 shadow-sm">
                <h4 class="fw-bold mb-4 text-dark border-bottom pb-3">
                    <i class="bi bi-award-fill me-2 text-primary"></i> Prestasi Organisasi PIK-R
                </h4>

                <div class="row">
    <?php
    $prestasi = mysqli_query($conn, "SELECT * FROM prestasi ORDER BY tanggal_input DESC");
    if (mysqli_num_rows($prestasi) > 0):
        while ($p = mysqli_fetch_assoc($prestasi)):
            // URL dan text untuk share
            $urlShare = urlencode($base_url . 'detail/detail_prestasi.php?id=' . $p['id']);
            $textShare = urlencode($p['judul']);
    ?>
        <div class="col-md-6 mb-4" data-aos="fade-up">
            <div class="card shadow-sm h-100 border-0 rounded-4">
                <a href="../<?= $p['gambar'] ?>" class="glightbox">
                    <img src="../<?= $p['gambar'] ?>" 
                         alt="<?= htmlspecialchars($p['judul']) ?>" 
                         class="card-img-top prestasi-thumb rounded-top-4">
                </a>
                <div class="card-body">
    <h6 class="fw-semibold text-primary mb-2">
        <i class="bi bi-award-fill me-1"></i><?= htmlspecialchars($p['judul']) ?>
    </h6>
    <p class="prestasi-deskripsi mb-3">
        <?= nl2br(htmlspecialchars(mb_strimwidth($p['deskripsi'], 0, 100, '...'))) ?>
    </p>

    <div class="d-flex flex-wrap gap-2">
        <!-- Lihat Detail -->
        <a href="../detail/detail_prestasi.php?id=<?= $p['id'] ?>" 
           class="btn btn-sm btn-outline-primary rounded-pill fw-semibold">
            <i class="bi bi-eye-fill me-1"></i> Lihat Detail
        </a>

        <!-- Share WhatsApp -->
        <a href="https://api.whatsapp.com/send?text=<?= $textShare ?>%20<?= $urlShare ?>" 
           target="_blank" 
           class="btn btn-sm btn-success rounded-pill" 
           title="Share ke WhatsApp">
            <i class="bi bi-whatsapp"></i>
        </a>

        <!-- Share Telegram -->
        <a href="https://t.me/share/url?url=<?= $urlShare ?>&text=<?= $textShare ?>" 
           target="_blank" 
           class="btn btn-sm btn-info rounded-pill text-white" 
           title="Share ke Telegram">
            <i class="bi bi-telegram"></i>
        </a>

        <!-- Share Instagram -->
        <a href="https://www.instagram.com/" 
           target="_blank" 
           class="btn btn-sm btn-danger rounded-pill" 
           title="Buka Instagram">
            <i class="bi bi-instagram"></i>
        </a>

        <!-- Copy Link -->
        <button type="button" 
                class="btn btn-sm btn-secondary rounded-pill" 
                onclick="copyLink('<?= $base_url . 'detail/detail_prestasi.php?id=' . $p['id'] ?>')" 
                title="Copy Link">
            <i class="bi bi-link-45deg"></i>
        </button>
    </div>
</div>
            </div>
        </div>
    <?php 
        endwhile;
    else: ?>
        <div class="col-12 text-center text-muted py-5 bg-light rounded-3">
            <i class="bi bi-exclamation-circle fs-4 me-2"></i> Belum ada data prestasi.
        </div>
    <?php endif; ?>
</div>

            </div>
        </div>


        <!-- Sidebar -->
        <div class="col-md-4">

            <div class="mb-4">
                <h6 class="fw-bold text-dark">
                    <i class="bi bi-clock-history me-1"></i> Berita Terbaru
                </h6>
                <div class="bg-white p-3 rounded-3 shadow-sm" style="max-height: 400px; overflow-y: auto;">
                    <?php
                    $sidebar = mysqli_query($conn, "SELECT * FROM berita ORDER BY tanggal_terbit DESC LIMIT 5");
                    if (mysqli_num_rows($sidebar) > 0):
                        while ($b = mysqli_fetch_assoc($sidebar)):
                    ?>
                            <div class="d-flex mb-3">
                                <?php if (!empty($b['gambar']) && file_exists('../upload/berita/' . $b['gambar'])): ?>
                                    <img src="../upload/berita/<?= htmlspecialchars($b['gambar']) ?>" class="rounded me-3" alt="Gambar"
                                        style="width: 60px; height: 60px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-secondary-subtle rounded me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                <?php endif; ?>

                                <div class="flex-grow-1">
                                    <a href="detail_berita.php?id=<?= $b['id'] ?>" class="fw-semibold text-primary small text-decoration-none d-block mb-1" style="line-height: 1.2rem;">
                                        <i class="bi bi-dot"></i> <?= htmlspecialchars(mb_strimwidth($b['judul'], 0, 60, '...')) ?>
                                    </a>
                                    <div class="text-muted small" style="font-size: 0.7rem;">
                                        <i class="bi bi-calendar2-week me-1"></i> <?= date('d M Y, H:i', strtotime($b['tanggal_terbit'])) ?><br>
                                        <i class="bi bi-person-fill me-1"></i><?= htmlspecialchars($b['penulis']) ?>
                                    </div>
                                    <a href="../detail/detail_berita.php?id=<?= $b['id'] ?>" class="small text-decoration-none text-primary">
                                        <i class="bi bi-arrow-right-circle"></i> Lihat Detail
                                    </a>
                                </div>
                            </div>
                            <hr class="my-2">
                        <?php endwhile;
                    else: ?>
                        <div class="text-center text-muted">Belum ada berita.</div>
                    <?php endif; ?>
                </div>
            </div>


            <div>
                <h6 class="fw-bold text-dark">
                    <i class="bi bi-handshake-fill me-1"></i> Kerja Sama Aktif
                </h6>
                <div class="bg-light p-3 rounded-3 shadow-sm text-center text-muted">
                    Belum ada kerja sama.
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Lightbox Script -->
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
<script>
    const lightbox = GLightbox({
        selector: '.glightbox'
    });
</script>

<script>
function copyLink(url) {
    navigator.clipboard.writeText(url).then(() => {
        alert('Link berhasil dicopy!');
    }).catch(err => {
        alert('Gagal menyalin link');
    });
}
</script>


<?php include '../layouts/footer.php'; ?>
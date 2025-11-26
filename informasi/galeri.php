<?php include '../layouts/heeder.php'; ?>
<?php include '../confiq/koneksi.php'; ?>
<?php date_default_timezone_set('Asia/Jakarta'); ?>

<?php
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
$host = $_SERVER['HTTP_HOST'];
$uri = strtok($_SERVER['REQUEST_URI'], '?'); // Ambil URL tanpa query string
$page_url_base = $protocol . "://" . $host . $uri;
?>

<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" rel="stylesheet">

<style>
    body {
        font-family: 'Roboto', sans-serif;
    }

    .badge-date {
        background-color: #dc3545;
        color: white;
        font-size: 0.75rem;
        padding: 0.4em 0.75em;
        border-radius: 50px;
        display: inline-block;
        margin-bottom: 0.5rem;
    }

    .card-img-top {
        height: 180px;
        object-fit: cover;
    }

    /* [BARU] Style untuk tombol share */
    .share-links {
        white-space: nowrap; /* Mencegah tombol share turun baris */
    }
    .share-links a {
        font-size: 1.1rem; /* Ukuran ikon */
        transition: transform 0.2s ease, color 0.2s ease;
        text-decoration: none;
    }
    .share-links a:hover {
        transform: scale(1.25); /* Efek zoom saat hover */
    }
    .share-links a.text-success:hover { color: #157347 !important; }
    .share-links a.text-primary:hover { color: #0a58ca !important; }
    .share-links a.text-info:hover { color: #08a1c4 !important; }
    .share-links a.text-secondary:hover { color: #565e64 !important; }
</style>

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
                    <i class="bi bi-images me-1"></i> Galeri
                </li>
            </ol>
        </nav>
    </div>
</section>

<section class="container py-5">
    <div class="row">
        <div class="col-md-8 mb-5">
            <div class="bg-white p-4 rounded-4 shadow-sm">
                <h4 class="fw-bold mb-4 text-dark border-bottom pb-3">
                    <i class="bi bi-image-fill me-2 text-primary"></i> Dokumentasi Galeri
                </h4>

                <div class="row g-4">
                    <?php
                    $query = mysqli_query($conn, "SELECT * FROM galeri ORDER BY tanggal_upload DESC");
                    if (mysqli_num_rows($query) > 0) :
                        while ($row = mysqli_fetch_assoc($query)) :
                            $gambar_relative = '../upload/galeri/' . $row['gambar'];
                            $gambar_filename = htmlspecialchars($row['gambar']);
                            $judul = htmlspecialchars($row['judul']);
                            $tanggal = date('D, d M Y', strtotime($row['tanggal_upload']));
                            
                            // [BARU] Variabel untuk Share
                            $share_id = 'galeri-' . $row['id'];
                            $share_url = $page_url_base . '#' . $share_id; // URL ke halaman ini + anchor
                            $share_title_encoded = rawurlencode($judul);
                            $share_url_encoded = rawurlencode($share_url);
                            $share_text_wa = rawurlencode($judul . "\n\nLihat di galeri:\n" . $share_url);
                            $share_text_tele = rawurlencode($judul);
                            $share_url_js = htmlspecialchars($share_url, ENT_QUOTES, 'UTF-8');
                    ?>
                            <div class="col-lg-6 col-md-6 col-sm-12" id="<?= $share_id ?>">
                                <div class="card shadow-sm border-0 h-100">
                                    <a href="<?= $gambar_relative ?>" class="glightbox" data-gallery="galeri" data-title="<?= $judul ?>">
                                        <img src="<?= $gambar_relative ?>" class="card-img-top" alt="<?= $judul ?>">
                                    </a>
                                    <div class="card-body d-flex flex-column">
                                        <div>
                                            <span class="badge-date"><?= strtoupper($tanggal) ?></span>
                                            <h6 class="fw-bold text-dark mb-1"><?= $judul ?></h6>
                                        </div>
                                        
                                        <div class="mt-3 pt-3 border-top d-flex flex-wrap align-items-center justify-content-between gap-2">
                                            <a href="<?= $gambar_relative ?>" download="<?= $gambar_filename ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3" title="Download Gambar">
                                                <i class="bi bi-download me-1"></i> Download
                                            </a>
                                            
                                            <div class="share-links d-flex align-items-center gap-2">
                                                <a href="https://wa.me/?text=<?= $share_text_wa ?>" target="_blank" class="text-success" title="Bagikan ke WhatsApp"><i class="bi bi-whatsapp"></i></a>
                                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $share_url_encoded ?>" target="_blank" class="text-primary" title="Bagikan ke Facebook"><i class="bi bi-facebook"></i></a>
                                                <a href="https://t.me/share/url?url=<?= $share_url_encoded ?>&text=<?= $share_title_encoded ?>" target="_blank" class="text-info" title="Bagikan ke Telegram"><i class="bi bi-telegram"></i></a>
                                                <a href="javascript:void(0);" onclick="copyLink('<?= $share_url_js ?>', this)" class="text-secondary" title="Salin Tautan"><i class="bi bi-clipboard"></i></a>
                                            </div>
                                        </div>
                                        </div>
                                </div>
                            </div>
                    <?php
                        endwhile;
                    else :
                        echo '<div class="text-center text-muted py-5 bg-light rounded-3">
                                <i class="bi bi-info-circle me-1"></i> Belum ada galeri yang diunggah.
                              </div>';
                    endif;
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="mb-5">
                <h5 class="fw-bold text-dark mb-3">
                    <i class="bi bi-info-circle-fill me-2 text-primary"></i> Info Kegiatan Terbaru
                </h5>
                <?php
                $latest = mysqli_query($conn, "SELECT * FROM kegiatan ORDER BY tanggal_kegiatan DESC LIMIT 1");
                $latest_data = mysqli_fetch_assoc($latest);
                ?>

                <?php if ($latest_data) : ?>
                    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                        <div class="card-body p-3">
                            <h6 class="fw-semibold text-primary mb-1">
                                <i class="bi bi-flag-fill text-primary me-1"></i> <?= htmlspecialchars($latest_data['judul']) ?>
                            </h6>
                            <p class="text-muted small mb-2">
                                <i class="bi bi-calendar-event me-1"></i> <?= date('d M Y', strtotime($latest_data['tanggal_kegiatan'])) ?>
                            </p>
                            <a href="../detail/detail_kegiatan.php?id=<?= $latest_data['id'] ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <i class="bi bi-eye-fill me-1"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="bg-light p-3 rounded-3 shadow-sm text-center text-muted">
                        <i class="bi bi-clock-history me-1"></i> Belum ada informasi tambahan
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-5">
                <h5 class="fw-bold text-dark mb-3">
                    <i class="bi bi-star-fill me-2 text-warning"></i> Kerja Sama Terbaru
                </h5>
                <?php
                $query = mysqli_query($conn, "SELECT * FROM kerja_sama WHERE status = 'aktif' ORDER BY urutan ASC, tanggal_input DESC LIMIT 1");
                if (mysqli_num_rows($query) > 0) :
                    while ($row = mysqli_fetch_assoc($query)) :
                ?>
                        <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-3">
                            <div class="row g-0">
                                <?php
                                $imagePath = '../upload/kerjasama/' . $row['gambar'];
                                if (!empty($row['gambar']) && file_exists($imagePath)) :
                                ?>
                                    <div class="col-4">
                                        <img src="<?= $imagePath ?>" alt="Gambar Kerja Sama"
                                             class="img-fluid h-100 w-100" style="object-fit: cover;">
                                    </div>
                                <?php endif; ?>
                                <div class="<?= (!empty($row['gambar']) && file_exists($imagePath)) ? 'col-8' : 'col-12' ?>">
                                    <div class="card-body p-3">
                                        <h6 class="fw-semibold text-dark mb-1" style="font-size: 0.95rem;">
                                            <i class="bi bi-building me-1"></i> <?= htmlspecialchars($row['nama_instansi']) ?>
                                        </h6>
                                        <p class="text-muted small mb-2">
                                            <i class="bi bi-clock me-1"></i> <?= date('d M Y', strtotime($row['tanggal_input'])) ?>
                                        </p>
                                        <a href="../detail/detail_kerjasama.php?page=detail_kerjasama&id=<?= $row['id'] ?>" class="btn btn-sm btn-primary rounded-pill">
                                            <i class="bi bi-eye-fill me-1"></i> Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    endwhile;
                else :
                    ?>
                    <div class="bg-light p-3 rounded-3 shadow-sm text-center text-muted">
                        <i class="bi bi-exclamation-circle me-1"></i> Belum ada kerja sama aktif.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>

<script>
function copyLink(text, el) {
    if (!navigator.clipboard) {
        // Fallback untuk browser lama
        try {
            var textArea = document.createElement("textarea");
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            alert('Tautan disalin!');
        } catch (err) {
            alert('Gagal menyalin tautan.');
        }
        return;
    }
    
    // Metode modern
    navigator.clipboard.writeText(text).then(function() {
        var icon = el.querySelector('i');
        if (icon) {
            var originalIcon = icon.className; // Simpan ikon asli
            
            // Ubah ikon menjadi ceklis
            icon.className = 'bi bi-clipboard-check-fill text-success';
            el.setAttribute('title', 'Tautan disalin!');
            
            // Kembalikan ke ikon semula setelah 2 detik
            setTimeout(function() {
                icon.className = originalIcon;
                el.setAttribute('title', 'Salin Tautan');
            }, 2000);
        }
    }, function(err) {
        alert('Gagal menyalin tautan: ', err);
    });
}

// Inisialisasi GLightbox
const lightbox = GLightbox({
    selector: '.glightbox',
    touchNavigation: true,
    loop: true,
    zoomable: true,
});
</script>

<?php include '../layouts/footer.php'; ?>
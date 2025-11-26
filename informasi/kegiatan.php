<?php
include '../layouts/heeder.php';
include '../confiq/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

// [BARU] Ambil URL halaman ini untuk link share
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
$host = $_SERVER['HTTP_HOST'];
$uri = strtok($_SERVER['REQUEST_URI'], '?'); // Ambil URL tanpa query string
$page_url_base = $protocol . "://" . $host . $uri;
?>

<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Roboto', sans-serif;
    }

    /* [BARU] CSS untuk memotong deskripsi */
    .deskripsi-truncate {
        display: -webkit-box;
        -webkit-line-clamp: 3; /* 3 baris */
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.5em;
        max-height: calc(1.5em * 3);
    }

    /* [BARU] Tombol detail kustom */
    .btn-custom-detail {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        font-weight: 600;
        color: #fff;
        background: linear-gradient(135deg, #0d6efd, #6610f2);
        border: none;
        border-radius: 50px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .btn-custom-detail i {
        transition: transform 0.3s ease;
    }
    .btn-custom-detail:hover {
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        transform: translateY(-2px);
    }
    .btn-custom-detail:hover i {
        transform: translateX(5px);
    }

    /* [BARU] Efek hover pada kartu */
    .card-hover {
        transition: all 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    /* [BARU] Style untuk tombol share */
    .share-links a {
        font-size: 1.1rem;
        transition: transform 0.2s ease, color 0.2s ease;
        text-decoration: none;
    }
    .share-links a:hover {
        transform: scale(1.25);
    }
    
    /* [BARU] Judul Gradasi Keren */
    .card-title-gradient {
        background: linear-gradient(135deg, #0d6efd, #6610f2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-fill-color: transparent;
        font-weight: 700;
        line-height: 1.3;
    }
    .card-title-gradient i {
        /* Ikon akan otomatis ikut gradien */
        font-size: 1.1em;
        vertical-align: middle;
    }

    /* [BARU] Style untuk list di sidebar */
    .sidebar-list-item {
        display: block;
        padding: 0.5rem 0;
        border-bottom: 1px solid #eee;
        text-decoration: none;
        transition: background-color 0.2s ease;
    }
    .sidebar-list-item:last-child {
        border-bottom: none;
    }
    .sidebar-list-item:hover {
        background-color: #f8f9fa;
    }
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
                    <i class="bi bi-bullseye me-1"></i> Kegiatan
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
                    <i class="bi bi-calendar-check-fill me-2 text-primary"></i> Kegiatan Terbaru
                </h4>

                <?php
                $query = mysqli_query($conn, "SELECT * FROM kegiatan ORDER BY tanggal_kegiatan DESC");
                if (mysqli_num_rows($query) > 0) :
                    echo '<div class="row">';
                    while ($row = mysqli_fetch_assoc($query)) :
                        
                        // [BARU] Siapkan variabel untuk link share
                        $share_id = 'kegiatan-' . $row['id'];
                        $share_url = $page_url_base . '#' . $share_id;
                        $share_title = htmlspecialchars($row['judul']);
                        
                        $share_text_wa = rawurlencode($share_title . "\n\nSelengkapnya di:\n" . $share_url);
                        $share_text_tele = rawurlencode($share_title);
                        $share_url_encoded = rawurlencode($share_url);
                        $share_url_js = htmlspecialchars($share_url, ENT_QUOTES, 'UTF-8');
                ?>
                        <div class="col-md-6 mb-4" data-aos="fade-up" id="<?= $share_id ?>">
                            <div class="card h-100 shadow-sm rounded-4 border-0 card-hover">
                                <?php if (!empty($row['gambar']) && file_exists('../upload/kegiatan/' . $row['gambar'])) : ?>
                                    <img src="../upload/kegiatan/<?= htmlspecialchars($row['gambar']) ?>" class="card-img-top rounded-top-4" alt="Gambar Kegiatan" style="max-height: 180px; object-fit: cover;">
                                <?php endif; ?>
                                
                                <div class="card-body d-flex flex-column">
                                    <h5 class="mb-2 card-title-gradient">
                                        <i class="bi bi-megaphone-fill me-1"></i> <?= htmlspecialchars($row['judul']) ?>
                                    </h5>

                                    <p class="text-muted small mb-1">
                                        <i class="bi bi-geo-alt-fill me-1"></i> <?= htmlspecialchars($row['tempat']) ?>
                                    </p>

                                    <p class="text-muted small mb-2">
                                        <i class="bi bi-calendar-event me-1"></i> <?= date('l, j F Y • H:i', strtotime($row['tanggal_kegiatan'])) ?>
                                    </p>

                                    <p class="text-dark small deskripsi-truncate flex-grow-1" style="white-space: pre-line;">
                                        <?= htmlspecialchars($row['deskripsi']) ?>
                                    </p>

                                    <div class="mt-auto pt-3 border-top">
                                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-2">
                                            <a href="../detail/detail_kegiatan.php?id=<?= $row['id'] ?>" class="btn-custom-detail">
                                                <span>Lihat Detail</span>
                                                <i class="bi bi-arrow-right-circle-fill ms-2"></i>
                                            </a>
                                            <div class="share-links d-flex align-items-center gap-2">
                                                <a href="https://wa.me/?text=<?= $share_text_wa ?>" target="_blank" class="text-success" title="Bagikan ke WhatsApp"><i class="bi bi-whatsapp"></i></a>
                                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $share_url_encoded ?>" target="_blank" class="text-primary" title="Bagikan ke Facebook"><i class="bi bi-facebook"></i></a>
                                                <a href="https://t.me/share/url?url=<?= $share_url_encoded ?>&text=<?= $share_text_tele ?>" target="_blank" class="text-info" title="Bagikan ke Telegram"><i class="bi bi-telegram"></i></a>
                                                <a href="javascript:void(0);" onclick="copyLink('<?= $share_url_js ?>', this)" class="text-secondary" title="Salin Tautan"><i class="bi bi-clipboard"></i></a>
                                            </div>
                                        </div>
                                        
                                        <?php if (!empty($row['tautan'])) : ?>
                                            <a href="<?= htmlspecialchars($row['tautan']) ?>" target="_blank" class="btn btn-sm btn-link text-decoration-none text-primary p-0">
                                                <i class="bi bi-link-45deg me-1"></i> Kunjungi Tautan
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    endwhile;
                    echo '</div>'; // Menutup .row
                else :
                    echo '<div class="text-center text-muted py-5 bg-light rounded-3"><i class="bi bi-info-circle me-1"></i> Belum ada Kegiatan</div>';
                endif;
                ?>
            </div>
        </div>

        <div class="col-md-4">
            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-3">
                    <i class="bi bi-award-fill me-1 text-warning"></i> Prestasi Terbaru
                </h6>
                <div class="bg-white p-3 rounded-3 shadow-sm">
                    <?php
                    $sidebar_prestasi = mysqli_query($conn, "SELECT * FROM prestasi ORDER BY tanggal_input DESC LIMIT 5");
                    if (mysqli_num_rows($sidebar_prestasi) > 0):
                        while ($p = mysqli_fetch_assoc($sidebar_prestasi)):
                    ?>
                            <a href="<?= $base_url ?>detail/detail_prestasi.php?id=<?= $p['id'] ?>" class="sidebar-list-item">
                                <h6 class="fw-semibold text-primary small mb-1 text-truncate" title="<?= htmlspecialchars($p['judul']) ?>">
                                    <?= htmlspecialchars($p['judul']) ?>
                                </h6>
                                <div class="text-muted small" style="font-size: 0.75rem;">
                                    <i class="bi bi-calendar3 me-1"></i> <?= date('d M Y', strtotime($p['tanggal_input'])) ?>
                                </div>
                            </a>
                    <?php
                        endwhile;
                    else: ?>
                        <div class="text-center text-muted small py-3">
                            <i class="bi bi-info-circle me-1"></i> Belum ada prestasi.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <h6 class="fw-bold text-dark mb-3">
                    <i class="bi bi-megaphone-fill me-1 text-info"></i> Pengumuman Terbaru
                </h6>
                <div class="bg-white p-3 rounded-3 shadow-sm">
                    <?php
                    $sidebar_pengumuman = mysqli_query($conn, "SELECT * FROM pengumuman ORDER BY tanggal DESC LIMIT 5");
                    if (mysqli_num_rows($sidebar_pengumuman) > 0):
                        while ($peng = mysqli_fetch_assoc($sidebar_pengumuman)):
                    ?>
                            <a href="<?= $base_url ?>informasi/pengumuman.php#pengumuman-<?= $peng['id'] ?>" class="sidebar-list-item">
                                <h6 class="fw-semibold text-primary small mb-1 text-truncate" title="<?= htmlspecialchars($peng['judul']) ?>">
                                    <?= htmlspecialchars($peng['judul']) ?>
                                </h6>
                                <div class="text-muted small" style="font-size: 0.75rem;">
                                    <i class="bi bi-calendar3 me-1"></i> <?= date('d M Y', strtotime($peng['tanggal'])) ?>
                                </div>
                            </a>
                    <?php
                        endwhile;
                    else: ?>
                        <div class="text-center text-muted small py-3">
                            <i class="bi bi-info-circle me-1"></i> Belum ada pengumuman.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function copyLink(text, el) {
    if (!navigator.clipboard) {
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
    
    navigator.clipboard.writeText(text).then(function() {
        var icon = el.querySelector('i');
        if (icon) {
            var originalIcon = icon.className;
            icon.className = 'bi bi-clipboard-check-fill text-success';
            el.setAttribute('title', 'Tautan disalin!');
            
            setTimeout(function() {
                icon.className = originalIcon;
                el.setAttribute('title', 'Salin Tautan');
            }, 2000);
        }
    }, function(err) {
        alert('Gagal menyalin tautan: ', err);
    });
}
</script>

<?php include '../layouts/footer.php'; ?>
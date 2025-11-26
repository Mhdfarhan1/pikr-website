<?php include '../layouts/heeder.php'; ?>
<?php include '../confiq/koneksi.php'; ?>

<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Roboto', sans-serif; /* Ini akan menimpa font Poppins dari heeder jika dimuat setelahnya */
    }

    /* Style untuk tombol share */
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
    
    /* [BARU] Style untuk list di sidebar yang "keren" */
    .sidebar-list-item {
        display: block;
        padding: 0.6rem 0.5rem; /* Padding agar lebih rapi */
        border-bottom: 1px solid #eee;
        text-decoration: none;
        transition: background-color 0.2s ease;
    }
    .sidebar-list-item:last-child {
        border-bottom: none; /* Hapus border di item terakhir */
    }
    .sidebar-list-item:hover {
        background-color: #f8f9fa; /* Efek hover */
    }
    .sidebar-list-item h6 {
        margin-bottom: 0.25rem;
        /* Menerapkan truncate (potong teks) */
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
</style>

<?php
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
$host = $_SERVER['HTTP_HOST'];
$uri = strtok($_SERVER['REQUEST_URI'], '?'); // Ambil URL tanpa query string
$page_url_base = $protocol . "://" . $host . $uri;
?>
<section class="py-3" style="margin-top: 100px;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="<?= $base_url ?>index.php" class="text-decoration-none text-primary fw-semibold">
                        <i class="bi bi-house-door-fill me-1"></i> Beranda
                    </a>
                </li>
                <li class="breadcrumb-item active text-muted" aria-current="page">Pengumuman</li>
            </ol>
        </nav>
    </div>
</section>

<section class="container py-5">
    <div class="row">
        <div class="col-md-8 mb-5">
            <div class="bg-white p-4 rounded-4 shadow-sm">
                <h4 class="fw-bold mb-4 text-dark border-bottom pb-3">📣 Pengumuman Terbaru</h4>
                <div class="row">
                    <?php
                    $query = mysqli_query($conn, "SELECT * FROM pengumuman ORDER BY tanggal DESC");
                    $count = 0;
                    if (mysqli_num_rows($query) > 0):
                        while ($row = mysqli_fetch_assoc($query)):
                            $count++;
                            $isFirstTwo = $count <= 2;
                            
                            $share_id = 'pengumuman-' . $row['id'];
                            $share_url = $page_url_base . '#' . $share_id;
                            $share_title = htmlspecialchars($row['judul']);
                            
                            $share_text_wa = rawurlencode($share_title . "\n\nSelengkapnya di:\n" . $share_url);
                            $share_text_tele = rawurlencode($share_title);
                            $share_url_encoded = rawurlencode($share_url);
                            $share_url_js = htmlspecialchars($share_url, ENT_QUOTES, 'UTF-8');
                    ?>
                    <div class="<?= $isFirstTwo ? 'col-md-6' : 'col-12' ?> mb-4" data-aos="fade-up" id="<?= $share_id ?>">
                        <div class="border-bottom pb-3 h-100">
                            <?php 
                            $gambar_path = 'upload/pengumuman/' . $row['gambar'];
                            if (!empty($row['gambar']) && file_exists('../' . $gambar_path)):
                            ?>
                                <div class="mb-3">
                                    <img src="<?= $base_url . htmlspecialchars($gambar_path) ?>"
                                         class="img-fluid rounded-3 shadow-sm"
                                         style="max-height: 250px; object-fit: cover; width: 100%;">
                                </div>
                            <?php endif; ?>
                            <h6 class="fw-bold text-dark"><?= htmlspecialchars($row['judul']) ?></h6>
                            <p class="text-muted small mb-2">
                                <i class="bi bi-calendar-event me-1"></i>
                                <?= date('l, j F Y', strtotime($row['tanggal'])) . ' • ' . date('H:i', strtotime($row['tanggal'])) ?>
                            </p>
                            <p class="text-dark small" style="white-space: pre-line; line-height: 1.6;">
                                <?= htmlspecialchars($row['isi']) ?>
                            </p>

                            <div class="mt-3 d-flex align-items-center gap-3 share-links">
                                <span class="text-muted small fw-semibold me-1">Bagikan:</span>
                                <a href="https://wa.me/?text=<?= $share_text_wa ?>" target="_blank" class="text-success" title="Bagikan ke WhatsApp"><i class="bi bi-whatsapp"></i></a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $share_url_encoded ?>" target="_blank" class="text-primary" title="Bagikan ke Facebook"><i class="bi bi-facebook"></i></a>
                                <a href="https://t.me/share/url?url=<?= $share_url_encoded ?>&text=<?= $share_text_tele ?>" target="_blank" class="text-info" title="Bagikan ke Telegram"><i class="bi bi-telegram"></i></a>
                                <a href="javascript:void(0);" onclick="copyLink('<?= $share_url_js ?>', this)" class="text-secondary" title="Salin Tautan"><i class="bi bi-clipboard"></i></a>
                            </div>
                            <?php if (!empty($row['tautan'])): ?>
                                <a href="<?= htmlspecialchars($row['tautan']) ?>" target="_blank"
                                   class="btn btn-sm btn-outline-primary rounded-pill fw-semibold mt-3 px-3"> <i class="bi bi-link-45deg me-1"></i> Kunjungi Tautan
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endwhile; else: ?>
                        <div class="col-12 text-center text-muted py-5 bg-light rounded-3">Belum ada Pengumuman</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-3">
                    <i class="bi bi-trophy-fill text-warning me-1"></i> Prestasi Organisasi
                </h6>
                <div class="bg-white p-3 rounded-3 shadow-sm">
                    <?php
                    // [MODIFIKASI] Ganti LIMIT 6 -> 5
                    $sidebar_prestasi = mysqli_query($conn, "SELECT * FROM prestasi ORDER BY tanggal_input DESC LIMIT 5");
                    if (mysqli_num_rows($sidebar_prestasi) > 0):
                        // [MODIFIKASI] Hapus 'echo <div class="row g-2">'
                        while ($p = mysqli_fetch_assoc($sidebar_prestasi)):
                    ?>
                            <a href="<?= $base_url ?>detail/detail_prestasi.php?id=<?= $p['id'] ?>" class="sidebar-list-item" title="<?= htmlspecialchars($p['judul']) ?>">
                                <h6 class="fw-semibold text-primary small mb-1">
                                    <?= htmlspecialchars($p['judul']) ?>
                                </h6>
                                <div class="text-muted small" style="font-size: 0.75rem;">
                                    <i class="bi bi-calendar3 me-1"></i> <?= date('d M Y', strtotime($p['tanggal_input'])) ?>
                                </div>
                            </a>
                            <?php 
                        endwhile;
                        // [MODIFIKASI] Hapus 'echo </div>'
                    else: ?>
                        <div class="text-center text-muted small py-3">Belum ada prestasi.</div>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <h6 class="fw-bold text-dark d-flex align-items-center mb-3">
                    <i class="bi bi-newspaper text-primary me-2 fs-5"></i> Berita Organisasi Terbaru
                </h6>
                <div class="bg-white p-3 rounded-3 shadow-sm">
                    <?php
                    // [MODIFIKASI] Ganti LIMIT 1 -> 5
                    $berita_sidebar = mysqli_query($conn, "SELECT * FROM berita ORDER BY tanggal_terbit DESC LIMIT 5");
                    if (mysqli_num_rows($berita_sidebar) > 0):
                        while ($b = mysqli_fetch_assoc($berita_sidebar)):
                    ?>
                            <a href="<?= $base_url ?>detail/detail_berita.php?id=<?= $b['id'] ?>" class="sidebar-list-item" title="<?= htmlspecialchars($b['judul']) ?>">
                                <h6 class="fw-semibold text-primary small mb-1">
                                    <?= htmlspecialchars($b['judul']) ?>
                                </h6>
                                <div class="text-muted small" style="font-size: 0.75rem;">
                                    <i class="bi bi-calendar2-week me-1"></i>
                                    <?= date('d M Y', strtotime($b['tanggal_terbit'])) ?>
                                </div>
                                </a>
                            <?php endwhile; else: ?>
                        <div class="text-center text-muted small py-3">Belum ada berita.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        </div>
</section>

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
</script>
<?php include '../layouts/footer.php'; ?>
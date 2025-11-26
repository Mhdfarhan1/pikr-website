<?php
include '../confiq/koneksi.php';

$id = $_GET['id'] ?? 0;
$query = mysqli_query($conn, "SELECT * FROM kegiatan WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='index.php';</script>";
    exit;
}

include '../layouts/heeder.php';

// Ambil URL halaman ini untuk link share
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
$host = $_SERVER['HTTP_HOST'];
$uri = $_SERVER['REQUEST_URI'];
$current_page_url = $protocol . "://" . $host . $uri;
?>

<style>
    /* Tombol detail kustom */
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

    /* Judul Gradasi Keren */
    .card-title-gradient {
        background: linear-gradient(135deg, #0d6efd, #6610f2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-fill-color: transparent;
        font-weight: 700;
        line-height: 1.3;
    }

    /* Tombol Share */
    .share-links {
        display: inline-flex;
        align-items: center;
        gap: 1rem; /* Jarak antar ikon */
    }
    .share-links a {
        font-size: 1.2rem;
        transition: transform 0.2s ease, color 0.2s ease;
        text-decoration: none;
    }
    .share-links a:hover {
        transform: scale(1.25);
    }
    
    /* Warna Ikon Instagram */
    .share-links a.text-instagram {
        color: #E1306C; 
    }
    .share-links a.text-instagram:hover {
        color: #C13584 !important;
    }

</style>


<section class="py-3" style="margin-top: 100px;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="<?= $base_url ?>index.php" class="text-decoration-none text-primary fw-semibold">
                        <i class="bi bi-house-door-fill me-1"></i> Beranda
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="<?= $base_url ?>informasi/kegiatan.php" class="text-decoration-none text-primary fw-semibold">
                        Kegiatan
                    </a>
                </li>
                <li class="breadcrumb-item active text-muted" aria-current="page" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                    <?= htmlspecialchars($data['judul']) ?>
                </li>
            </ol>
        </nav>
    </div>
</section>

<section class="container py-5">
    <div class="row">
        <div class="col-md-4 text-center mb-4 mb-md-0">
            <?php 
            $gambar_path = '../upload/kegiatan/' . $data['gambar'];
            if (!empty($data['gambar']) && file_exists($gambar_path)): 
            ?>
                <img src="<?= htmlspecialchars($gambar_path) ?>" class="img-fluid rounded shadow" alt="Gambar Kegiatan" style="max-width: 100%;">
                
                <a href="<?= htmlspecialchars($gambar_path) ?>" download="<?= htmlspecialchars($data['gambar']) ?>" class="btn btn-sm btn-outline-success rounded-pill mt-3 px-3">
                    <i class="bi bi-download me-1"></i> Download Gambar
                </a>

            <?php else: ?>
                <div class="bg-secondary text-white py-5 rounded shadow d-flex align-items-center justify-content-center" style="min-height: 200px;">
                    <i class="bi bi-image-fill me-2 fs-4"></i> Tidak ada gambar
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-8">
            <div class="bg-white p-4 rounded-4 shadow-sm h-100">
                
                <h4 class="card-title-gradient mb-3"><?= htmlspecialchars($data['judul']) ?></h4>
                
                <div class="text-muted small mb-3 border-bottom pb-3">
                    <div>
                        <i class="bi bi-calendar-event me-1"></i> <?= date('l, d F Y • H:i', strtotime($data['tanggal_kegiatan'])) ?>
                    </div>
                    <div>
                        <i class="bi bi-geo-alt-fill me-1"></i> <?= htmlspecialchars($data['tempat']) ?>
                    </div>
                </div>
                
                <p class="text-dark" style="white-space: pre-line; line-height: 1.7;">
                    <?= htmlspecialchars($data['deskripsi']) ?>
                </p>

                <hr class="my-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-4">
                    
                    <?php if (!empty($data['tautan'])): ?>
                        <a href="<?= htmlspecialchars($data['tautan']) ?>" class="btn-custom-detail" target="_blank">
                            <i class="bi bi-link-45deg me-2"></i> 
                            <span>Kunjungi Tautan</span>
                            <i class="bi bi-arrow-right-circle-fill ms-2"></i>
                        </a>
                    <?php endif; ?>

                    <div class="share-links">
                         <span class="text-muted small fw-semibold me-3 d-none d-sm-inline">Bagikan:</span>
                         <?php
                            // Siapkan variabel untuk link share
                            $share_url_encoded = rawurlencode($current_page_url);
                            $share_title_encoded = rawurlencode($data['judul']);
                            $share_text_wa = rawurlencode($data['judul'] . "\n\nSelengkapnya di:\n" . $current_page_url);
                            $share_url_js = htmlspecialchars($current_page_url, ENT_QUOTES, 'UTF-8');
                         ?>
                        <a href="https://wa.me/?text=<?= $share_text_wa ?>" target="_blank" class="text-success" title="Bagikan ke WhatsApp"><i class="bi bi-whatsapp"></i></a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $share_url_encoded ?>" target="_blank" class="text-primary" title="Bagikan ke Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="https://t.me/share/url?url=<?= $share_url_encoded ?>&text=<?= $share_title_encoded ?>" target="_blank" class="text-info" title="Bagikan ke Telegram"><i class="bi bi-telegram"></i></a>
                        
                        <a href="javascript:void(0);" onclick="copyLink('<?= $share_url_js ?>', this)" class="text-secondary" title="Salin Tautan"><i class="bi bi-clipboard"></i></a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<script>
function copyLink(text, el) {
    if (!navigator.clipboard) {
        // Fallback
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
            // Cek apakah ikonnya bi-instagram atau bi-clipboard
            var isInstagram = icon.classList.contains('bi-instagram');
            var originalIcon = icon.className; // Simpan ikon asli
            
            // Ubah ikon menjadi ceklis
            icon.className = 'bi bi-clipboard-check-fill text-success';
            el.setAttribute('title', 'Tautan disalin!');
            
            // Kembalikan ke ikon semula setelah 2 detik
            setTimeout(function() {
                icon.className = originalIcon;
                // Kembalikan title asli berdasarkan ikon
                if (isInstagram) {
                    el.setAttribute('title', 'Salin Tautan (untuk Instagram)');
                } else {
                    el.setAttribute('title', 'Salin Tautan');
                }
            }, 2000);
        }
    }, function(err) {
        alert('Gagal menyalin tautan: ', err);
    });
}
</script>

<?php include '../layouts/footer.php'; ?>
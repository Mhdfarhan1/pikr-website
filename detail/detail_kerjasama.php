<?php
include '../confiq/koneksi.php';

$id = $_GET['id'] ?? 0;
$query = mysqli_query($conn, "SELECT * FROM kerja_sama WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='kerjasama.php';</script>";
    exit;
}

include '../layouts/heeder.php';

// [BARU] Ambil URL halaman ini untuk link share
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
$host = $_SERVER['HTTP_HOST'];
$uri = $_SERVER['REQUEST_URI'];
$current_page_url = $protocol . "://" . $host . $uri;
?>

<style>
    .share-links {
        display: inline-flex; /* Membuat ikon sejajar */
        align-items: center; 
        gap: 1rem; /* Jarak antar ikon */
    }
    .share-links a {
        font-size: 1.2rem; /* Ukuran ikon */
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
                        <i class="bi bi-house-door-fill me-1"></i> Beranda
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="<?= $base_url ?>informasi/kerja_sama.php" class="text-decoration-none text-primary fw-semibold">
                        Kerja Sama
                    </a>
                </li>
                <li class="breadcrumb-item active text-muted" aria-current="page">
                    <?= htmlspecialchars($data['nama_instansi']) ?>
                </li>
            </ol>
        </nav>
    </div>
</section>

<section class="container py-5">
    <div class="row">
        <div class="col-md-4 text-center mb-4 mb-md-0">
            <?php 
            $gambar_path = '../upload/kerjasama/' . $data['gambar'];
            if (!empty($data['gambar']) && file_exists($gambar_path)): 
            ?>
                <img src="<?= htmlspecialchars($gambar_path) ?>" class="img-fluid rounded shadow" alt="Gambar Kerja Sama" style="max-width: 100%; object-fit: cover;">
                
                <a href="<?= htmlspecialchars($gambar_path) ?>" download="<?= htmlspecialchars($data['gambar']) ?>" class="btn btn-sm btn-outline-primary rounded-pill mt-3 px-3">
                    <i class="bi bi-download me-1"></i> Download Gambar
                </a>

            <?php else: ?>
                <div class="bg-secondary text-white py-5 rounded shadow">Tidak ada gambar</div>
            <?php endif; ?>
        </div>

        <div class="col-md-8">
            <h4 class="fw-bold"><?= htmlspecialchars($data['nama_instansi']) ?></h4>
            <div class="text-muted small mb-2">
                <i class="bi bi-calendar3 me-1"></i> <?= date('d F Y, H:i', strtotime($data['tanggal_input'])) ?>
            </div>
            <p class="text-dark" style="white-space: pre-line; line-height: 1.6;">
                <?= htmlspecialchars($data['deskripsi']) ?>
            </p>

            <div class="mt-4 pt-3 border-top d-flex flex-wrap align-items-center gap-4">
                
                <?php if (!empty($data['tautan'])): ?>
                    <a href="<?= htmlspecialchars($data['tautan']) ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3" target="_blank">
                        <i class="bi bi-link-45deg me-1"></i> Lihat Tautan
                    </a>
                <?php endif; ?>

                <div class="share-links">
                     <span class="text-muted small fw-semibold me-2 d-none d-sm-inline">Bagikan:</span>
                     <?php
                        // Siapkan variabel untuk link share
                        $share_url_encoded = rawurlencode($current_page_url);
                        $share_title_encoded = rawurlencode($data['nama_instansi']);
                        $share_text_wa = rawurlencode($data['nama_instansi'] . "\n\nSelengkapnya di:\n" . $current_page_url);
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
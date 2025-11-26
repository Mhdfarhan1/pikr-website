<?php
include '../confiq/koneksi.php';

$id = $_GET['id'] ?? 0;
$query = mysqli_query($conn, "SELECT * FROM prestasi WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);

// Validasi data
if (!$data) {
    // Sebaiknya tidak ada output HTML sebelum redirect
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='../informasi/prestasi.php';</script>";
    exit;
}

include '../layouts/heeder.php';

// [MODIFIKASI] Variabel share dipindahkan ke atas agar bisa diakses di kolom gambar
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
$host = $_SERVER['HTTP_HOST'];
$uri = $_SERVER['REQUEST_URI'];
$current_page_url = $protocol . "://" . $host . $uri;

// Siapkan variabel untuk link share
$share_url_encoded = rawurlencode($current_page_url);
$share_title_encoded = rawurlencode($data['judul']);
$share_text_wa = rawurlencode($data['judul'] . "\n\nSelengkapnya di:\n" . $current_page_url);
$share_url_js = htmlspecialchars($current_page_url, ENT_QUOTES, 'UTF-8');
?>

<style>
    /* Tombol detail kustom (jika diperlukan) */
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
                    <a href="<?= $base_url ?>informasi/prestasi.php" class="text-decoration-none text-primary fw-semibold">
                        Prestasi
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
    <div class="row g-4">
        <div class="col-md-4 text-center">
            <?php 
            $gambar_path_relative = '../' . $data['gambar'];
            
            if (!empty($data['gambar']) && file_exists($gambar_path_relative)): 
            ?>
                <img src="<?= htmlspecialchars($gambar_path_relative) ?>" 
                     class="img-fluid rounded shadow-sm" 
                     alt="<?= htmlspecialchars($data['judul']) ?>" 
                     style="max-width: 100%;">
                
                <div class="mt-3 d-flex flex-wrap justify-content-center align-items-center gap-4">
                    <a href="<?= htmlspecialchars($gambar_path_relative) ?>" download="<?= htmlspecialchars(basename($data['gambar'])) ?>" class="btn btn-sm btn-outline-success rounded-pill px-3">
                        <i class="bi bi-download me-1"></i> Download
                    </a>
                    
                    <div class="share-links">
                        <a href="https://wa.me/?text=<?= $share_text_wa ?>" target="_blank" class="text-success" title="Bagikan ke WhatsApp"><i class="bi bi-whatsapp"></i></a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $share_url_encoded ?>" target="_blank" class="text-primary" title="Bagikan ke Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="https://t.me/share/url?url=<?= $share_url_encoded ?>&text=<?= $share_title_encoded ?>" target="_blank" class="text-info" title="Bagikan ke Telegram"><i class="bi bi-telegram"></i></a>
                        <a href="javascript:void(0);" onclick="copyLink('<?= $share_url_js ?>', this)" class="text-secondary" title="Salin Tautan"><i class="bi bi-clipboard"></i></a>
                    </div>
                </div>
                
            <?php else: ?>
                <img src="../assets/img/default-image.jpg" 
                     class="img-fluid rounded shadow-sm" 
                     alt="Tidak ada gambar" 
                     style="max-width: 100%;">
            <?php endif; ?>
        </div>

        <div class="col-md-8">
            <div class="bg-white p-4 rounded-4 shadow-sm h-100">
                
                <h4 class="card-title-gradient mb-3"><?= htmlspecialchars($data['judul']) ?></h4>
                
                <div class="text-muted mb-3 pb-3 border-bottom" style="font-size: 0.9rem;">
                    <i class="bi bi-calendar3 me-1"></i>
                    <?= date('d F Y, H:i', strtotime($data['tanggal_input'])) ?>
                </div>
                
                <p class="text-dark" style="white-space: pre-line; line-height: 1.7; font-size: 0.95rem;">
                    <?= htmlspecialchars($data['deskripsi']) ?>
                </p>

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
            var originalIcon = icon.className; // Simpan ikon asli
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
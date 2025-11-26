<?php
include '../confiq/koneksi.php';

$id = $_GET['id'] ?? 0;
// [MODIFIKASI] Ambil semua data dari 'frofile' (nama tabel Anda)
$query = mysqli_query($conn, "SELECT * FROM frofile WHERE id = '$id'"); 
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='index.php';</script>";
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
    /* Foto Profil Kustom */
    .profile-pic {
        width: 250px;
        height: 250px;
        object-fit: cover;
        border: 5px solid #fff;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
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

    /* Tombol Share & Sosial Media */
    .social-links {
        display: inline-flex;
        align-items: center;
        gap: 1rem;
    }
    .social-links a {
        font-size: 1.5rem; /* Lebih besar untuk profil */
        transition: transform 0.2s ease, color 0.2s ease;
        text-decoration: none;
    }
    .social-links a:hover {
        transform: scale(1.2);
    }
    .social-links a.text-instagram { color: #E1306C; }
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
                    <a href="<?= $base_url ?>index.php" class="text-decoration-none text-primary fw-semibold">
                        Profil Tim
                    </a>
                </li>
                <li class="breadcrumb-item active text-muted" aria-current="page" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                    <?= htmlspecialchars($data['nama']) ?>
                </li>
            </ol>
        </nav>
    </div>
</section>

<section class="container py-5">
    <div class="row">
        <div class="col-md-4 text-center mb-4 mb-md-0">
            <?php
            $foto_path = '../assets/img/frofile/' . $data['foto'];
            if (!empty($data['foto']) && file_exists($foto_path)):
            ?>
                <img src="<?= htmlspecialchars($foto_path) ?>" class="img-fluid rounded-circle profile-pic" alt="Foto">
                
            <?php else: ?>
                <img src="../assets/img/default-profile.png" class="img-fluid rounded-circle profile-pic" alt="Foto Default">
            <?php endif; ?>

            <div class="mt-4 social-links justify-content-center">
                <?php if (!empty($data['whatsapp'])): ?>
                    <a href="https://wa.me/<?= htmlspecialchars($data['whatsapp']) ?>" target="_blank" class="text-success" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                <?php endif; ?>
                <?php if (!empty($data['instagram'])): ?>
                    <a href="https://instagram.com/<?= htmlspecialchars($data['instagram']) ?>" target="_blank" class="text-instagram" title="Instagram"><i class="bi bi-instagram"></i></a>
                <?php endif; ?>
                <?php if (!empty($data['facebook'])): ?>
                    <a href="<?= htmlspecialchars($data['facebook']) ?>" target="_blank" class="text-primary" title="Facebook"><i class="bi bi-facebook"></i></a>
                <?php endif; ?>
            </div>

        </div>

        <div class="col-md-8">
            <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm h-100">
                
                <h4 class="card-title-gradient mb-1"><?= htmlspecialchars($data['nama']) ?></h4>
                <h5 class="text-muted mb-3 pb-3 border-bottom"><?= htmlspecialchars($data['jabatan']) ?></h5>
                
                <p class="text-dark" style="white-space: pre-line; line-height: 1.7;">
                    <?= htmlspecialchars($data['deskripsi']) ?>
                </p>
                
                <hr class="my-4">
                <div class="share-links">
                     <span class="text-muted small fw-semibold me-3 d-none d-sm-inline fs-6">Bagikan Profil:</span>
                     <?php
                        // Siapkan variabel untuk link share
                        $share_url_encoded = rawurlencode($current_page_url);
                        $share_title_encoded = rawurlencode("Profil " . $data['nama'] . " - " . $data['jabatan']);
                        $share_text_wa = rawurlencode("Lihat profil " . $data['nama'] . " (" . $data['jabatan'] . ")\n\n" . $current_page_url);
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
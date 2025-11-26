<?php
// === Maintenance mode with token+cookie (device-specific) ===
// Letakkan paling atas index.php

$maintenance = false; // true = publik lihat maintenance

// Buat token kuat sekali (sekali saja). Contoh token; ganti dengan tokenmu sendiri.
$adminToken = 'twNQf1Kp3IQSrNMV5E7D'; // GANTI dgn token acak panjang
$cookieName = 'site_admin_token';
$cookieTtl = 3600 * 2; // 2 jam, sesuaikan jika perlu

// Optional: cek juga user-agent agar token tidak gampang dipakai perangkat lain bila cookie dicuri
$checkUserAgent = true;
$userAgentHashName = 'site_admin_ua';

// Jika token dikirim via URL, set cookie (HTTP only, secure jika pakai https)
if (isset($_GET['admin']) && hash_equals($adminToken, $_GET['admin'])) {
    // set cookie token (httpOnly, SameSite)
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    setcookie($cookieName, $adminToken, [
        'expires' => time() + $cookieTtl,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'],
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    // set user agent hash cookie (not httponly so we can read if needed) OR store via another httponly cookie
    if ($checkUserAgent) {
        $uaHash = hash('sha256', $_SERVER['HTTP_USER_AGENT'] ?? '');
        setcookie($userAgentHashName, $uaHash, [
            'expires' => time() + $cookieTtl,
            'path' => '/',
            'domain' => $_SERVER['HTTP_HOST'],
            'secure' => $secure,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    }

    // Redirect supaya URL bersih dari ?admin=
    $url = strtok($_SERVER["REQUEST_URI"], '?');
    header("Location: $url");
    exit;
}

// Fungsi validasi cookie/token
function is_admin_allowed($adminToken, $cookieName, $checkUserAgent = true, $userAgentHashName = 'site_admin_ua') {
    if (!isset($_COOKIE[$cookieName])) return false;
    // gunakan hash_equals untuk mencegah timing attack
    if (!hash_equals($adminToken, $_COOKIE[$cookieName])) return false;

    if ($checkUserAgent) {
        $uaHash = hash('sha256', $_SERVER['HTTP_USER_AGENT'] ?? '');
        if (!isset($_COOKIE[$userAgentHashName]) || !hash_equals($_COOKIE[$userAgentHashName], $uaHash)) {
            return false;
        }
    }

    return true;
}

// Optional: clear admin cookie via ?admin_clear=1 (untuk logout)
if (isset($_GET['admin_clear']) && $_GET['admin_clear'] == '1') {
    setcookie($cookieName, '', time() - 3600, '/');
    setcookie($userAgentHashName, '', time() - 3600, '/');
    header('Location: /');
    exit;
}

// Jika maintenance aktif dan bukan admin yang diizinkan -> tampilkan maintenance
if ($maintenance && !is_admin_allowed($adminToken, $cookieName, $checkUserAgent, $userAgentHashName)) {
    include 'maintenance.php';
    exit;
}
// jika sampai sini, browser ini punya cookie valid -> lanjut ke website normal

// --- Kode utama ---
include 'layouts/heeder.php';
include 'confiq/koneksi.php';

$page = $_GET['page'] ?? 'home'; // default ke home
$file = "pages/$page.php";

// Ambil data Frofile
$sqlFrofile = "SELECT * FROM frofile ORDER BY id ASC";
$result = mysqli_query($conn, $sqlFrofile);

// Ambil 1 data utama dari kategori 'tentang'
$queryTentang = "SELECT * FROM tentang_pikr WHERE kategori = 'tentang' ORDER BY tanggal_input DESC LIMIT 1";
$resultTentang = mysqli_query($conn, $queryTentang);
$tentang = mysqli_fetch_assoc($resultTentang);

// Ambil data logo (yang kamu lupa tadi)
$query = mysqli_query($conn, "SELECT * FROM logo WHERE status = 'aktif' ORDER BY urutan ASC");

// Daftar kategori penting
$kategori_list = ['prestasi', 'profil', 'fasilitas', 'galeri'];
$ikon_map = [
  'prestasi' => 'bi-trophy',
  'profil' => 'bi-person-circle',
  'fasilitas' => 'bi-building',
  'galeri' => 'bi-image'
];

// Ambil data terbaru dari masing-masing kategori
$data_kategori = [];

foreach ($kategori_list as $kategori) {
  $stmt = mysqli_query($conn, "SELECT * FROM tentang_pikr WHERE kategori = '$kategori' ORDER BY tanggal_input DESC LIMIT 1");
  if ($row = mysqli_fetch_assoc($stmt)) {
    $data_kategori[] = [
      'kategori'  => ucfirst($kategori),
      'ikon'      => $ikon_map[$kategori] ?? 'bi-info-circle',
      'judul'     => !empty($row['judul']) ? $row['judul'] : ucfirst($kategori),
      'deskripsi' => $row['deskripsi']
    ];
  }
}

$strukturQuery = mysqli_query($conn, "SELECT * FROM struktur_organisasi WHERE status = 'aktif' ORDER BY urutan ASC");
$queryKerjasama = mysqli_query($conn, "SELECT * FROM kerja_sama WHERE status = 'aktif' ORDER BY urutan ASC LIMIT 3");
$prestasi_query = mysqli_query($conn, "SELECT * FROM prestasi ORDER BY tanggal_input DESC LIMIT 4");
?>


<style>
  .scroll-top {
    position: fixed;
    right: 20px;
    bottom: 20px;
    width: 40px;
    height: 40px;
    background: #0b0c4c;
    color: white;
    border-radius: 50%;
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 999;
    transition: opacity 0.3s ease;
  }

  .scroll-top.active {
    display: flex;
  }

  .icon-box {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 12px;
    padding: 25px;
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
  }

  .icon-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
  }

  .icon-box i {
    font-size: 2.5rem;
    color: #ffffffff;
    margin-bottom: 10px;
    display: inline-block;
  }

  .testimonial-img {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 50%;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  .testimonial-item {
    transition: transform 0.3s ease;
  }

  .testimonial-item:hover {
    transform: translateY(-5px);
  }

  .clamp-text {
    display: -webkit-box;
    -webkit-line-clamp: 5;
    line-clamp: 5;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  
   .description-truncate {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    min-height: 4.5em; /* Supaya tinggi konsisten */
  }
  
  .gallery-item {
  position: relative;
  width: 100%;
  padding-top: 100%; /* Memaksa kotak: tinggi = lebar */
  overflow: hidden;
  border-radius: 10px;
}

.gallery-item img {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover; /* Gambar akan terisi penuh */
  object-position: center;
}

.member .pic {
  position: relative;
  width: 100%;
  padding-top: 100%; /* 1:1 rasio kotak, atau pakai 75% untuk 4:3 */
  overflow: hidden;
  border-radius: 10px;
}

.member .pic img {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
}


</style>

<body class="index-page">


  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background position-relative">
  <img src="assets/img/baground1.JPG" alt="Background Hero" class="hero-bg w-100 position-absolute top-0 start-0" style="height: 100%; object-fit: cover; z-index: -1;">

  <div class="container">
    <div class="row justify-content-center align-items-center gy-4">

      <!-- Hero Image -->
      <div class="col-lg-6 col-md-8 col-12 hero-img text-center" data-aos="zoom-out" data-aos-delay="100">
        <img src="assets/img/baground2.JPG" class="img-fluid rounded-4 shadow" alt="Hero Image PIK R Request">
      </div>

      <!-- Hero Text -->
      <div class="col-lg-6 col-md-10 col-12 d-flex flex-column justify-content-center text-center text-lg-start" data-aos="fade-in">
        <h1 class="mb-3">
          Selamat Datang Di Website <span style="border-bottom: 3px solid #34d399;">PIK-R REQUEST</span>
        </h1>
        <h2 class="mb-3">SMA NEGERI 1 TASIK PUTRI PUYU</h2>
        <p class="mb-4">Organisasi Pusat Informasi dan Konseling Remaja yang terletak di kecamatan Tasik Putri Puyu</p>

        <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center justify-content-lg-start">
          <a href="#about" class="btn-get-started mb-2 mb-sm-0">Tentang Organisasi</a>
          <a href="https://www.instagram.com/reel/CwOnXrwgj8g/?igsh=Y3A0ejA0cjhpZDN1"
             class="btn-watch-video d-flex align-items-center justify-content-center" target="_blank" rel="noopener noreferrer">
             <i class="bi bi-play-circle me-2"></i><span>Video Profil</span>
          </a>
        </div>
      </div>

    </div>
  </div>

  <!-- Hero Waves -->
  <svg class="hero-waves" xmlns="http://www.w3.org/2000/svg" viewBox="0 24 150 28" preserveAspectRatio="none">
    <defs>
      <path id="wave-path" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z"></path>
    </defs>
    <g class="wave1">
      <use xlink:href="#wave-path" x="50" y="3"></use>
    </g>
    <g class="wave2">
      <use xlink:href="#wave-path" x="50" y="0"></use>
    </g>
    <g class="wave3">
      <use xlink:href="#wave-path" x="50" y="9"></use>
    </g>
  </svg>
</section>


    <!-- Frofile Section -->
    <section id="Frofile" class="Frofil section">
      <div class="container section-title" data-aos="fade-up">
        <h2>Frofile</h2>
        <div>
          <span>KEPALA SEKOLAH & PEMBINA</span>
          <span class="description-title">PIK-R Request</span>
        </div>
      </div>

      <div class="container">
        <div class="row gx-4 gy-4" data-aos="fade-up">
          <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <div class="col-md-6">
              <div class="card h-100 shadow-sm border-0 rounded-4">
                <div class="row g-0 align-items-center">
                  <div class="col-4">
                    <img src="assets/img/frofile/<?= htmlspecialchars($row['foto']) ?>"
                      class="img-fluid rounded-start-4"
                      alt="<?= htmlspecialchars($row['nama']) ?>">
                  </div>
                  <div class="col-8">
                    <div class="card-body p-3">
                      <!-- Nama sebagai link ke detail -->
                      <h6 class="card-title fw-bold mb-1" style="font-size: 1rem;">
                        <a href="detail/detail_frofile.php?id=<?= $row['id'] ?>" class="text-decoration-none text-dark">
                          <?= htmlspecialchars($row['nama']) ?>
                        </a>
                      </h6>
                      <p class="card-text mb-2" style="font-size: 0.9rem;">
                        <?= htmlspecialchars($row['jabatan']) ?>
                      </p>

                      <!-- Deskripsi maksimal 5 baris -->
                      <p class="text-muted mb-0 clamp-text">
                        <?= htmlspecialchars($row['deskripsi']) ?>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    </section>


    <!-- About Section -->
    <section id="about" class="about section">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row align-items-xl-center gy-5">

          <!-- Bagian kiri: Tentang -->
          <div class="col-xl-5 content">
            <h3 class="text-uppercase text-primary fw-bold"><?= strtoupper($tentang['kategori'] ?? 'TENTANG') ?> PIK-R</h3>
            <h2 class="fw-bold"><?= htmlspecialchars($tentang['judul'] ?? 'Judul Belum Tersedia') ?></h2>
            <p><?= nl2br(htmlspecialchars($tentang['deskripsi'] ?? 'Belum ada deskripsi tentang.')) ?></p>
            <a href="#hero" class="read-more">
              <span>Kembali</span><i class="bi bi-arrow-right"></i>
            </a>
          </div>

          <!-- Bagian kanan: Ikon Box -->
          <div class="col-xl-7">
            <div class="row gy-4 icon-boxes">

              <?php if (!empty($data_kategori)): ?>
                <?php foreach ($data_kategori as $index => $data): ?>
                  <div class="col-md-6" data-aos="fade-up" data-aos-delay="<?= 200 + ($index * 100) ?>">
                    <div class="icon-box h-100">
                      <i class="bi <?= htmlspecialchars($data['ikon']) ?>"></i>
                      <h4 class="fw-semibold mt-2"><?= htmlspecialchars($data['judul']) ?></h4>
                      <p class="text-muted small"><?= nl2br(htmlspecialchars($data['deskripsi'])) ?></p>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <div class="col-12">
                  <p class="text-muted">Belum ada data kategori tambahan.</p>
                </div>
              <?php endif; ?>

            </div>
          </div>

        </div>
      </div>
    </section>


    <!-- logo Section -->
    <section id="stats" class="stats section light-background py-5">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row justify-content-center align-items-center text-center">

          <?php if (mysqli_num_rows($query) > 0): ?>
            <?php while ($logo = mysqli_fetch_assoc($query)): ?>
              <div class="col-lg-2 col-md-3 col-4 mb-4">
                <img src="upload/logo/<?= htmlspecialchars($logo['gambar']) ?>"
                  class="img-fluid grayscale-hover"
                  alt="Logo <?= $logo['id'] ?>"
                  style="max-height: 100px;">
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <div class="col-12 text-center text-muted">Belum ada logo yang ditampilkan.</div>
          <?php endif; ?>

        </div>
      </div>
    </section>


    </section><!-- /Details Section -->

    <!-- Gallery Section -->
    <section id="gallery" class="gallery section">
      <div class="container section-title" data-aos="fade-up">
        <h2>Galeri</h2>
        <div>
          <span>Foto Kegiatan</span>
          <span class="description-title"> PIK-R</span>
        </div>
      </div>

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row g-0">

          <?php
          // Pastikan $conn sudah didefinisikan dan terkoneksi ke database
          // Contoh: include 'path/to/koneksi.php';

          $query = mysqli_query($conn, "SELECT * FROM galeri ORDER BY tanggal_upload DESC");
          $count = 0; // Inisialisasi counter

          if (mysqli_num_rows($query) > 0) :
            while ($row = mysqli_fetch_assoc($query)) :
              $imgPath = "upload/galeri/" . htmlspecialchars($row['gambar']);
              // Ubah kondisi menjadi ($count >= 4) untuk menampilkan hanya 4 gambar pertama
              $isHidden = ($count >= 4) ? 'gallery-more d-none' : '';
          ?>
              <div class="col-lg-3 col-md-4 <?= $isHidden ?>">
                <div class="gallery-item">
                  <a href="<?= $imgPath ?>" class="glightbox" data-gallery="images-gallery">
                    <img src="<?= $imgPath ?>" alt="<?= htmlspecialchars($row['judul']) ?>" class="img-fluid">
                  </a>
                </div>
              </div>
          <?php
              $count++; // Tambahkan counter setelah setiap gambar
            endwhile;
          else :
            echo "<div class='text-muted text-center py-5'>Belum ada data galeri</div>";
          endif;
          ?>

        </div>

        <div class="text-center mt-4">
          <a href="informasi/galeri.php" class="btn btn-primary">
            <i class="bi bi-images me-1"></i> Lihat Selengkapnya
          </a>
        </div>

      </div>
    </section>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <section id="struktur" class="testimonials section dark-background"
      style="position: relative; background: url('assets/img/4.jpg') no-repeat center center / cover;">

      <!-- Overlay biru transparan -->
      <div style="
    position: absolute;
    top: 0; left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 50, 0.4);
    z-index: 1;"></div>

      <!-- Judul Section -->
      <div class="container section-title" data-aos="fade-up" style="position: relative; z-index: 2;">
        <h2>Struktur</h2>
        <div><span>Struktur Organisasi</span> <span class="description-title">PIK-R</span></div>
      </div>

      <!-- Swiper Container -->
      <div class="container position-relative" data-aos="fade-up" data-aos-delay="100" style="z-index: 2;">
        <div class="swiper mySwiper">
          <div class="swiper-wrapper">
            <?php while ($struktur = mysqli_fetch_assoc($strukturQuery)): ?>
              <div class="swiper-slide">
                <div class="testimonial-item text-center p-4 bg-white rounded-4 shadow-sm h-100">
                  <img src="upload/struktur/<?= htmlspecialchars($struktur['foto']) ?>"
                    class="testimonial-img mb-3 rounded-circle shadow"
                    alt="<?= htmlspecialchars($struktur['nama']) ?>"
                    style="width: 100px; height: 100px; object-fit: cover; border: 4px solid #fff;">
                  <h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars($struktur['nama']) ?></h5>
                  <h6 class="text-muted mb-2"><?= htmlspecialchars($struktur['jabatan']) ?></h6>
                  <p class="text-muted small fst-italic">
                    <i class="bi bi-quote quote-icon-left"></i>
                    <?= nl2br(htmlspecialchars($struktur['deskripsi'])) ?>
                    <i class="bi bi-quote quote-icon-right"></i>
                  </p>
                </div>
              </div>
            <?php endwhile; ?>
          </div>

          <!-- Pagination -->
          <div class="swiper-pagination mt-3"></div>
        </div>
      </div>
    </section>





    <section id="team" class="team section">
  <div class="container section-title" data-aos="fade-up">
    <h2>Kerja Sama</h2>
    <div><span>Kerja Sama</span> <span class="description-title">PIK- R Request</span></div>
  </div>

  <div class="container">
    <div class="row gy-5">
      <?php while ($data = mysqli_fetch_assoc($queryKerjasama)) : ?>
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
          <div class="member">
            <div class="pic">
              <img src="upload/kerjasama/<?= htmlspecialchars($data['gambar']) ?>" class="img-fluid" alt="">
            </div>
            <div class="member-info">
              <h4><?= htmlspecialchars($data['nama_instansi']) ?></h4>
              <p class="description-truncate mb-2"><?= htmlspecialchars($data['deskripsi']) ?></p>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>

    <!-- Tombol Lihat Semua -->
    <div class="text-center mt-4">
      <a href="informasi/kerja_sama.php" class="btn btn-primary shadow-sm px-4 fw-semibold">
        <i class="bi bi-arrow-right-circle me-1"></i> Lihat Semua Kerja Sama
      </a>
    </div>
  </div>
</section>


    <section id="prestasi" class="prestasi section">
      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Prestasi</h2>
        <div><span>Foto Prestasi</span> <span class="description-title"> PIK-R</span></div>
      </div>

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row g-0">
          <?php while ($p = mysqli_fetch_assoc($prestasi_query)) : ?>
            <div class="col-lg-3 col-md-4 mb-4">
              <div class="prestasi-item p-2">
                <a href="<?= $p['gambar'] ?>" class="glightbox" data-gallery="images-prestasi">
                  <img src="<?= $p['gambar'] ?>" alt="<?= htmlspecialchars($p['judul']) ?>" class="img-fluid rounded shadow-sm">
                </a>
                <p class="text-center prestasi-caption fw-bold mt-2"><?= strtoupper($p['judul']) ?></p>
              </div>
            </div>
          <?php endwhile; ?>
        </div>

        <!-- Tombol Selengkapnya -->
        <div class="text-center mt-4">
          <a href="informasi/prestasi.php" class="btn btn-primary rounded-pill px-4 shadow-sm">
            Lihat Selengkapnya <i class="bi bi-arrow-right-circle ms-1"></i>
          </a>
        </div>
      </div>
    </section>

    <!-- ======= Footer ======= -->
    <?php include 'layouts/footer.php'; ?>

    <a href="#" class="scroll-top d-flex align-items-center justify-content-center">
      <i class="bi bi-arrow-up-short"></i>
    </a>


    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    <script>
      const lightbox = GLightbox({
        selector: '.glightbox'
      });
    </script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script>
      const swiper = new Swiper(".mySwiper", {
        loop: true,
        speed: 600,
        autoplay: {
          delay: 5000,
          disableOnInteraction: false
        },
        spaceBetween: 30,
        slidesPerView: 1,
        pagination: {
          el: ".swiper-pagination",
          clickable: true
        },
        breakpoints: {
          576: {
            slidesPerView: 2
          },
          768: {
            slidesPerView: 3
          },
          992: {
            slidesPerView: 4
          }
        }
      });
    </script>



</body>

</html>
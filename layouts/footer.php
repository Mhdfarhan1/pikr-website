<footer id="footer" class="footer dark-background text-white pt-5" style="overflow-x:hidden;">
  <style>
    /* ====== FOOTER UTAMA ====== */
    #footer {
      background-color: #040677;
      color: #fff;
      overflow-x: hidden;
      font-family: 'Poppins', sans-serif;
    }

    #footer .footer-top {
      border-bottom: 1px solid rgba(255, 255, 255, 0.15);
      padding-bottom: 20px;
    }

    #footer a {
      color: rgba(255, 255, 255, 0.85);
      text-decoration: none;
      transition: 0.3s;
    }

    #footer a:hover {
      color: #1acc8d;
      text-decoration: none;
    }

    #footer ul {
      padding: 0;
      margin: 0;
      list-style: none;
    }

    #footer ul li {
      display: flex;
      align-items: flex-start;
      margin-bottom: 10px;
      gap: 8px;
      line-height: 1.5;
    }

    #footer ul li i {
      font-size: 16px;
      color: #1acc8d;
      margin-top: 3px;
    }

    /* ====== SOSIAL MEDIA ====== */
    #footer .social-links a {
      color: #fff;
      font-size: 22px;
      transition: 0.3s;
    }

    #footer .social-links a:hover {
      color: #1acc8d;
      transform: scale(1.15);
    }

    /* ====== BADGE VERSI (IKON PETIR) ====== */
    #footer .footer-version-badge {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      background: linear-gradient(90deg, #ffcc00, #ff9900);
      color: #000;
      border-radius: 50px;
      padding: 6px 16px;
      font-size: 14px;
      font-weight: 600;
      box-shadow: 0 2px 10px rgba(255, 200, 0, 0.3);
      transition: all 0.3s ease;
    }

    #footer .footer-version-badge i {
      color: #000;
      font-size: 16px;
      animation: glow 1.2s infinite ease-in-out;
    }

    @keyframes glow {
      0%, 100% { transform: scale(1); opacity: 1; filter: brightness(1.2); }
      50% { transform: scale(1.2); opacity: 0.8; filter: brightness(1.6); }
    }

    #footer .footer-version-badge:hover {
      transform: scale(1.05);
      background: linear-gradient(90deg, #ffb300, #ffd000);
    }

    /* ====== COPYRIGHT ====== */
    #footer .copyright {
      text-align: center !important;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      margin-top: 20px;
      color: rgba(255, 255, 255, 0.85);
      font-size: 14px;
    }

    #footer .credits a {
      color: #1acc8d;
    }

    #footer .credits a:hover {
      color: #00ffcc;
    }

    #footer .footer-legal-links a {
      color: rgba(255, 255, 255, 0.85);
      text-decoration: none;
      margin: 0 4px;
    }

    #footer .footer-legal-links a:hover {
      color: #1acc8d;
    }

    /* ====== RESPONSIVE ====== */
    @media (max-width: 768px) {
      #footer .footer-about,
      #footer .footer-links,
      #footer ul li,
      #footer p,
      #footer h5,
      #footer span {
        text-align: left !important;
        justify-content: flex-start !important;
      }

      #footer .social-links {
        justify-content: flex-start !important;
        flex-wrap: wrap;
      }

      #footer .copyright {
        text-align: center !important;
      }

      #footer .footer-version-badge {
        margin-top: 10px;
        font-size: 13px;
      }
    }
  </style>

  <div class="container footer-top pb-4">
    <div class="row gy-4">

      <!-- Tentang PIK-R -->
      <div class="col-lg-5 col-md-12 footer-about">
        <a href="<?= $base_url ?>index.php" class="logo d-flex align-items-center text-white text-decoration-none mb-3">
          <span class="sitename fs-4 fw-bold">PIK-R REQUEST</span>
        </a>
        <span class="schoolname fw-bold d-block mb-3" style="font-size: 1rem; color: rgba(255,255,255,0.7);">
          SMAN 1 TASIK PUTRI PUYU
        </span>
        <p class="mt-2" style="color: rgba(255,255,255,0.7); word-break: break-word;">
          Organisasi Pusat Informasi dan Konseling Remaja yang berdedikasi untuk menciptakan generasi muda yang berencana, beretika, dan berwawasan.
        </p>
        <div class="social-links d-flex flex-wrap gap-3 mt-4 footer-social-links">
          <a href="https://wa.me/628123456789" title="WhatsApp" target="_blank"><i class="bi bi-whatsapp"></i></a>
          <a href="https://www.instagram.com/pik_request_sman_01ttp/" title="Instagram" target="_blank"><i class="bi bi-instagram"></i></a>
          <a href="https://www.facebook.com/share/1Bjc1kMnM2/" title="Facebook" target="_blank"><i class="bi bi-facebook"></i></a>
          <a href="https://www.tiktok.com/@username_tiktok" title="TikTok" target="_blank"><i class="bi bi-tiktok"></i></a>
        </div>
      </div>

      <!-- Tautan Cepat -->
      <div class="col-lg-3 col-md-6 col-sm-12 footer-links">
        <h5 class="fw-bold text-white mb-3">Tautan Cepat</h5>
        <ul class="list-unstyled">
          <li><i class="bi bi-chevron-right"></i> <a href="<?= $base_url ?>index.php">Beranda</a></li>
          <li><i class="bi bi-chevron-right"></i> <a href="<?= $base_url ?>informasi/berita.php">Berita</a></li>
          <li><i class="bi bi-chevron-right"></i> <a href="<?= $base_url ?>informasi/kegiatan.php">Kegiatan</a></li>
          <li><i class="bi bi-chevron-right"></i> <a href="<?= $base_url ?>informasi/galeri.php">Galeri</a></li>
          <li><i class="bi bi-chevron-right"></i> <a href="<?= $base_url ?>informasi/prestasi.php">Prestasi</a></li>
          <li><i class="bi bi-chevron-right"></i> <a href="<?= $base_url ?>#struktur">Struktur Tim</a></li>
        </ul>
      </div>

      <!-- Hubungi Kami -->
      <div class="col-lg-4 col-md-6 col-sm-12 footer-links">
        <h5 class="fw-bold text-white mb-3">Hubungi Kami</h5>
        <ul class="list-unstyled">
          <li><i class="bi bi-geo-alt-fill"></i> <span>Jl. Husni Tamri, Desa Kudap,<br>Kec. Tasik Putri Puyu,<br>Kab. Kepulauan Meranti, Riau 28754</span></li>
          <li><i class="bi bi-envelope-fill"></i> <a href="mailto:pikrequestsman1tpp25@gmail.com">pikrequestsman1tpp25@gmail.com</a></li>
          <li><i class="bi bi-telephone-fill"></i> <a href="tel:+6282229245081">+62 812 3456 7890</a></li>
        </ul>
      </div>

    </div>
  </div>

  <!-- Copyright -->
  <div class="container copyright py-4">
    <p class="mb-1">© <span>Desain</span> <strong class="px-1 sitename">Pik-r Request</strong> <span>SMA NEGERI 1 TASIK PUTRI PUYU</span></p>
    <div class="credits mb-1">
      Designed by <a href="https://github.com/Mhdfarhan1" class="text-info text-decoration-none" target="_blank">Muhammad Farhan</a>
    </div>
    <div class="footer-legal-links mb-1">
      <a href="#">Kebijakan Privasi</a> | <a href="#">Syarat & Ketentuan</a>
    </div>
    <div class="mt-2">
      <span class="footer-version-badge"><i class="bi bi-lightning-fill"></i> Versi Pembaruan 1.1</span>
    </div>
  </div>
</footer>

<a href="#" id="back-to-top" class="back-to-top d-flex align-items-center justify-content-center">
  <i class="bi bi-arrow-up-short"></i>
</a>

<script src="<?= $base_url ?>assets/vendor/aos/aos.js"></script>
<script>
AOS.init({ once: true, duration: 800 });
document.addEventListener('DOMContentLoaded', () => {
  const backToTopButton = document.querySelector('.back-to-top');
  if (backToTopButton) {
    const toggleBackToTop = () => {
      backToTopButton.classList.toggle('active', window.scrollY > 100);
    };
    window.addEventListener('load', toggleBackToTop);
    document.addEventListener('scroll', toggleBackToTop);
    backToTopButton.addEventListener('click', e => {
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }
});
</script>

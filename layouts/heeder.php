<?php
// Panggil config untuk base_url
include_once __DIR__ . '/../confiq/koneksi.php';

?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>PIK - R REQUEST</title>

  <link href="<?= $base_url ?>assets/img/logo 1.png" rel="icon" />
  <link href="<?= $base_url ?>assets/img/logo 1.png" rel="apple-touch-icon" />

  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />

  <link href="<?= $base_url ?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link href="<?= $base_url ?>assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" />
  <link href="<?= $base_url ?>assets/vendor/aos/aos.css" rel="stylesheet" />

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      // PENTING: preflight false agar Tailwind tidak merusak style Bootstrap Anda
      corePlugins: {
        preflight: false,
      }
    }
  </script>

  <link href="<?= $base_url ?>assets/css/main.css" rel="stylesheet" />
  <link href="<?= $base_url ?>assets/css/style.css" rel="stylesheet" />

  <style>
    /* Global Colors - The following color variables are used throughout the website. Updating them here will change the color scheme of the entire website */
    :root {
      --background-color: #ffffff;
      /* Background color for the entire website, including individual sections */
      --default-color: #444444;
      /* Default color used for the majority of the text content across the entire website */
      --heading-color: #040677;
      /* Warna biru gelap untuk heading, juga kita gunakan untuk latar belakang header dan menu mobile */
      --accent-color: #1acc8d;
      /* Warna aksen, untuk tombol, hover link nav desktop, dan ikon spinner */
      --surface-color: #ffffff;
      /* Background untuk elemen box, seperti card */
      --contrast-color: #ffffff;
      /* Warna teks kontras, misal di hover icon atau di atas background aksen/heading */

      /* Nav Menu Colors - The following color variables are used specifically for the navigation menu. They are separate from the global colors to allow for more customization options */
      --nav-color: #ffffff;
      /* Warna default link navmenu utama (desktop) */
      --nav-hover-color: var(--accent-color);
      /* Warna hover link navmenu utama (desktop) akan hijau terang */
      --nav-mobile-background-color: var(--heading-color);
      /* Warna biru gelap untuk menu mobile */
      --nav-dropdown-background-color: var(--heading-color);
      /* Warna biru gelap untuk dropdown desktop dan mobile */
      --nav-dropdown-color: #ffffff;
      /* Warna teks dropdown mobile/desktop */
      --nav-dropdown-hover-color: var(--accent-color);
      /* Warna hover teks dropdown mobile/desktop akan hijau terang */

      /* Font variables */
      --nav-font: 'Poppins', sans-serif;
    }

    /* --- General Body and Loader Styles --- */
    body {
      font-family: var(--nav-font);
      overflow-x: hidden;
    }

    #page-loader {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: var(--background-color);
      z-index: 99999;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      transition: opacity 0.3s ease;
    }

    #page-loader.hidden {
      opacity: 0;
      visibility: hidden;
    }

    .spinner-border {
      width: 3rem;
      height: 3rem;
      margin-bottom: 10px;
      color: var(--accent-color) !important;
    }

    .loader-text {
      font-weight: bold;
      color: var(--heading-color);
      font-size: 1.2rem;
      letter-spacing: 1px;
    }

    /* --- Header Styles --- */
    header.header {
      background-color: #040677 !important;
      /* Biru gelap untuk header desktop */
      transition: all 0.3s ease-in-out;
      z-index: 9990;
      height: 70px;
      display: flex;
      align-items: center;
    }

    .header .logo img {
      max-height: 40px;
    }

    .header .logo h1 {
      font-size: 24px;
      margin: 0;
      padding: 0;
      line-height: 1;
      font-weight: 700;
      color: var(--nav-color);
      /* Warna teks logo putih */
    }

    /* --- Navmenu (Desktop) --- */
    .navmenu {
      transition: 0.3s;
    }

    .navmenu ul {
      margin: 0;
      padding: 0;
      display: flex;
      list-style: none;
      align-items: center;
    }

    .navmenu li {
      position: relative;
      margin: 0 10px;
    }

    .navmenu a {
      color: var(--nav-color);
      /* Warna link nav putih */
      display: flex;
      align-items: center;
      padding: 10px 0;
      font-family: var(--nav-font);
      font-size: 16px;
      font-weight: 500;
      white-space: nowrap;
      transition: 0.3s;
      text-decoration: none;
    }

    .navmenu a i.toggle-dropdown {
      font-size: 12px;
      line-height: 0;
      margin-left: 5px;
    }

    .navmenu a:hover,
    .navmenu .active {
      color: var(--nav-color) !important;
      /* Warna hover hijau terang */
    }

    /* --- Dropdown Desktop --- */
    .navmenu .dropdown {
      position: relative;
    }

    .navmenu .dropdown ul {
      display: none;
      position: absolute;
      top: 100%;
      left: 0;
      background: var(--nav-dropdown-background-color);
      /* Background dropdown biru gelap */
      padding: 10px 0;
      margin: 0;
      border-radius: 6px;
      min-width: 200px;
      z-index: 99;
      box-shadow: 0px 0px 30px rgba(0, 0, 0, 0.1);
    }

    .navmenu .dropdown:hover>ul {
      display: block;
    }

    .navmenu .dropdown ul li {
      min-width: 180px;
      margin: 0;
    }

    .navmenu .dropdown ul a {
      color: var(--nav-dropdown-color);
      /* Warna teks dropdown putih */
      padding: 10px 20px;
      font-size: 15px;
      text-transform: none;
      justify-content: flex-start;
    }

    .navmenu .dropdown ul a i {
      margin-left: 10px;
      line-height: 0;
    }

    .navmenu .dropdown ul a:hover {
      background-color: var(--nav-dropdown-hover-color);
      /* Background hover dropdown hijau terang */
      color: var(--contrast-color);
      /* Teks hover dropdown putih */
    }

    .navmenu .dropdown .dropdown ul {
      top: 0;
      left: 100%;
    }

    .navmenu .dropdown .dropdown:hover>ul {
      display: block;
    }

    /* --- Mobile Specific Styles (@media max-width: 1199px) --- */
    @media (max-width: 1199px) {
      .mobile-nav-toggle {
        color: var(--nav-color);
        /* Warna ikon toggle putih */
        font-size: 28px;
        line-height: 0;
        margin-right: 10px;
        cursor: pointer;
        transition: color 0.3s;
        display: block;
        z-index: 9999;
        position: fixed;
        top: 21px;
        right: 20px;
      }

      .navmenu {
        padding: 0;
        z-index: 9997;
        position: fixed;
        inset: 0;
        background: rgba(33, 37, 41, 0.8);
        /* Overlay untuk mobile menu */
        transition: 0.3s;
        transform: translateX(100%);
        visibility: hidden;
      }

      .navmenu ul {
        /* Ini adalah kotak menu mobile utama */
        display: none;
        list-style: none;
        position: absolute;
        inset: 60px 20px 20px 20px;
        padding: 10px 0;
        margin: 0;
        border-radius: 6px;
        background-color: var(--nav-mobile-background-color);
        /* Background menu mobile biru gelap */
        border: 1px solid color-mix(in srgb, var(--default-color), transparent 90%);
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        overflow-y: auto;
        transition: 0.3s;
        z-index: 9998;
      }

      .navmenu a,
      .navmenu a:focus {
        color: var(--nav-dropdown-color);
        /* Warna teks link di menu mobile putih */
        padding: 10px 20px;
        font-family: var(--nav-font);
        font-size: 17px;
        font-weight: 500;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        white-space: nowrap;
        transition: 0.3s;
      }

      .navmenu a i.toggle-dropdown,
      .navmenu a:focus i.toggle-dropdown {
        font-size: 12px;
        line-height: 0;
        margin-left: 5px;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: 0.3s;
        background-color: var(--accent-color);
        /* Ikon toggle di menu mobile hijau terang */
      }

      .navmenu a i:hover,
      .navmenu a:focus i:hover {
        background-color: var(--accent-color);
        color: var(--contrast-color);
      }

      .navmenu a:hover,
      .navmenu .active,
      .navmenu .active:focus {
        color: var(--nav-dropdown-hover-color);
        /* Warna hover teks di menu mobile hijau terang */
      }

      .navmenu .active i.toggle-dropdown,
      .navmenu .active:focus i.toggle-dropdown {
        background-color: var(--accent-color);
        color: var(--contrast-color);
        transform: rotate(180deg);
      }

      /* Dropdown Mobile */
      .navmenu .dropdown ul {
        position: static;
        display: none;
        z-index: 99;
        padding: 10px 0;
        margin: 10px 20px 0 20px;
        transition: all 0.3s ease-in-out;
        box-shadow: none;
        border: none;
        border-radius: 0;
        opacity: 0;
        max-height: 0;
        overflow: hidden;
      }

      .navmenu .dropdown ul.dropdown-active {
        display: block;
        opacity: 1;
        max-height: 500px;
      }

      /* Styling untuk link di dalam dropdown mobile */
      .navmenu .dropdown ul li a {
        padding: 8px 20px;
        display: block;
        color: var(--nav-dropdown-color);
        /* Warna teks link di dropdown mobile putih */
        justify-content: flex-start;
      }

      .navmenu .dropdown ul li a:hover {
        color: var(--nav-dropdown-hover-color);
        /* Warna hover teks di dropdown mobile hijau terang */
        background-color: rgba(255, 255, 255, 0.05);
      }

      .navmenu .dropdown ul ul {
        background-color: rgba(33, 37, 41, 0.1);
        padding-left: 20px;
        margin: 0;
      }

      .mobile-nav-active {
        overflow: hidden;
      }

      .mobile-nav-active .navmenu {
        transform: translateX(0);
        visibility: visible;
      }

      .mobile-nav-active .navmenu>ul {
        display: block;
      }
    }

    /* --- Breadcrumb Section --- */
    .breadcrumb-section {
      margin-top: 70px;
      background-color: var(--background-color);
      border-bottom: 1px solid #dee2e6;
      /* Menggunakan warna abu-abu default Bootstrap untuk border */
      padding: 15px 0;
    }

    .breadcrumb-section ol {
      padding: 0;
      margin: 0;
      list-style: none;
      display: flex;
      align-items: center;
    }

    .breadcrumb-section ol li {
      font-size: 0.9rem;
      color: var(--default-color);
    }

    .breadcrumb-section ol li+li::before {
      display: inline-block;
      padding-right: 0.5rem;
      color: #6c757d;
      /* Warna abu-abu default Bootstrap untuk pemisah */
      content: "/";
    }

    .breadcrumb-section ol li a {
      color: var(--heading-color);
      /* Warna link breadcrumb biru gelap */
      text-decoration: none;
    }

    .breadcrumb-section ol li a:hover {
      text-decoration: underline;
    }

    .breadcrumb-section ol li.active {
      color: var(--default-color);
    }

    .text-purple {
      color: var(--heading-color);
    }

    /* SMOOTH SCROLL */
    html {
      scroll-behavior: smooth;
    }

    /* === DROPDOWN DESKTOP === */
    .navmenu .dropdown ul {
      opacity: 0;
      visibility: hidden;
      transform: translateY(10px);
      pointer-events: none;
      transition: opacity 0.25s ease, transform 0.25s ease, visibility 0.25s;
      position: absolute;
      top: 100%;
      left: 0;
      background: var(--nav-dropdown-background-color);
      padding: 10px 0;
      margin: 0;
      border-radius: 6px;
      min-width: 200px;
      z-index: 99;
      box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
    }

    .navmenu .dropdown:hover>ul {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
      pointer-events: auto;
    }

    /* === DROPDOWN MOBILE === */
    @media (max-width: 1199px) {
      .navmenu .dropdown ul {
        position: static;
        opacity: 0;
        transform: translateY(-10px);
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.3s ease, transform 0.3s ease, visibility 0.3s;
        margin: 10px 20px 0 20px;
        background-color: var(--nav-mobile-background-color);
        border-radius: 6px;
        padding: 0;
      }

      .navmenu .dropdown ul.dropdown-active {
        opacity: 1;
        transform: translateY(0);
        visibility: visible;
        pointer-events: auto;
        padding: 10px 0;
      }
    }

    /* === Loader Styling === */
    #page-loader {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: var(--background-color);
      z-index: 99999;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      transition: opacity 0.4s ease;
    }

    #page-loader.hidden {
      opacity: 0;
      visibility: hidden;
    }

    .spinner-border {
      width: 2.5rem;
      height: 2.5rem;
      color: var(--accent-color) !important;
      animation: spin 1s linear infinite;
    }

    .loader-text {
      font-weight: bold;
      color: var(--heading-color);
      font-size: 1.2rem;
      letter-spacing: 1px;
    }

    .loader-logo {
      width: 80px;
      height: auto;
      animation: zoomIn 0.6s ease forwards;
      opacity: 0;
    }

    /* Animations */
    @keyframes zoomIn {
      0% {
        transform: scale(0.20);
        opacity: 0;
      }

      100% {
        transform: scale(1);
        opacity: 1;
      }
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }
  </style>
</head>

<body>

  <div id="page-loader">
    <img src="<?= $base_url ?>assets/img/logo%201.png" alt="Logo" class="loader-logo mb-3" />
    <div class="spinner-border text-primary" role="status"></div>
    <div class="loader-text mt-2">PIK R REQUEST</div>
  </div>


  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

      <a href="<?= $base_url ?>index.php" class="logo d-flex align-items-center text-white text-decoration-none">
        <img src="<?= $base_url ?>assets/img/logo 1.png" alt="logo" class="me-2">
        <h1 class="sitename mb-0">PIK REQUEST</h1>
      </a>

      <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="<?= $base_url ?>#hero" class="active">Beranda</a></li>
          <li class="dropdown">
            <a href="#"><span>Profil</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="<?= $base_url ?>#Frofile">Kepala Sekolah & Pembina</a></li>
              <li><a href="<?= $base_url ?>#about">Tentang Kami</a></li>
            </ul>
          </li>
          <li class="dropdown">
            <a href="#"><span>Informasi</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="<?= $base_url ?>informasi/pengumuman.php">Pengumuman</a></li>
              <li><a href="<?= $base_url ?>informasi/kegiatan.php">Kegiatan</a></li>
              <li><a href="<?= $base_url ?>informasi/pendaftaran.php">Pendaftaran</a></li>
              <li><a href="https://lomba-id.pikrequestsman1tpp.my.id/">Pendaftaran Lomba</a></li>
              <li><a href="<?= $base_url ?>informasi/galeri.php">Galeri</a></li>
              <li><a href="<?= $base_url ?>informasi/prestasi.php">Prestasi</a></li>
              <li><a href="<?= $base_url ?>informasi/kerja_sama.php">Kerja Sama</a></li>
              <li><a href="<?= $base_url ?>informasi/berita.php">Berita</a></li>
            </ul>
          </li>
          <li><a href="<?= $base_url ?>#struktur">Struktur</a></li>
          <li><a href="<?= $base_url ?>login/login.php" class="text-white fw-semibold">Login</a></li>
        </ul>
      </nav>

    </div>
  </header>



  <script>
    window.addEventListener('load', () => {
      setTimeout(() => {
        const loader = document.getElementById('page-loader');
        if (loader) loader.classList.add('hidden');
      }, 500); // Waktu lebih cepat untuk loader
    });
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const toggle = document.querySelector('.mobile-nav-toggle');
      const body = document.body;
      const navmenu = document.getElementById('navmenu');
      const mobileBreakpoint = 1199; // Breakpoint mobile sesuai CSS Anda

      // Fungsi untuk menutup semua dropdown
      function closeAllDropdowns() {
        document.querySelectorAll('#navmenu .dropdown.active').forEach(function(openDropdown) {
          openDropdown.classList.remove('active');
          const openSubmenu = openDropdown.querySelector('ul');
          if (openSubmenu) {
            openSubmenu.classList.remove('dropdown-active');
          }
        });
      }

      // Fungsi untuk mengatur ulang tampilan menu saat resize dari mobile ke desktop dan sebaliknya
      function handleResize() {
        if (window.innerWidth > mobileBreakpoint) {
          body.classList.remove('mobile-nav-active');
          if (toggle) {
            toggle.classList.remove('bi-x');
            toggle.classList.add('bi-list');
          }
          closeAllDropdowns(); // Pastikan dropdown tertutup saat beralih ke desktop
        }
      }

      // Event listener untuk tombol toggle mobile
      if (toggle) {
        toggle.addEventListener('click', function() {
          body.classList.toggle('mobile-nav-active');

          // Mengganti ikon toggle
          if (body.classList.contains('mobile-nav-active')) {
            toggle.classList.remove('bi-list');
            toggle.classList.add('bi-x'); // Mengubah ke ikon 'x'
          } else {
            toggle.classList.remove('bi-x');
            toggle.classList.add('bi-list'); // Mengubah kembali ke ikon 'list'
            closeAllDropdowns(); // Tutup semua dropdown saat menu utama ditutup
          }
        });
      }

      // Dropdown mobile support
      const dropdownLinks = document.querySelectorAll('#navmenu .dropdown > a');

      dropdownLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
          if (window.innerWidth <= mobileBreakpoint) {
            const parent = this.parentElement;
            const submenu = parent.querySelector('ul');

            // Jika tidak ada submenu, biarkan link berfungsi normal (jangan preventDefault)
            if (!submenu) {
              return;
            }

            // Jika ada submenu, selalu preventDefault agar tidak langsung navigasi,
            // dan biarkan JavaScript yang mengontrol buka/tutup.
            e.preventDefault();

            // Tutup semua dropdown lain kecuali yang saat ini diklik
            document.querySelectorAll('#navmenu .dropdown.active').forEach(function(openDropdown) {
              if (openDropdown !== parent) {
                openDropdown.classList.remove('active');
                const openSubmenu = openDropdown.querySelector('ul');
                if (openSubmenu) {
                  openSubmenu.classList.remove('dropdown-active');
                }
              }
            });

            parent.classList.toggle('active');
            if (submenu) {
              submenu.classList.toggle('dropdown-active');
            }
          }
        });
      });

      // Menutup menu mobile dan dropdown saat link non-toggle diklik
      document.querySelectorAll('#navmenu a').forEach(function(link) {
        link.addEventListener('click', function(e) {
          if (window.innerWidth <= mobileBreakpoint) {
            // Jangan menutup menu jika yang diklik adalah ikon toggle dropdown
            // Karena itu akan membuka/menutup submenu, bukan menavigasi ke halaman
            const isDropdownToggle = this.parentElement.classList.contains('dropdown') && e.target.closest('.toggle-dropdown');

            if (!isDropdownToggle) {
              body.classList.remove('mobile-nav-active');
              if (toggle) {
                toggle.classList.remove('bi-x');
                toggle.classList.add('bi-list');
              }
              closeAllDropdowns(); // Tutup semua dropdown saat menu utama ditutup
            }
          }
        });
      });


      // Tambahkan event listener untuk resize window
      window.addEventListener('resize', handleResize);
      // Panggil sekali saat dimuat untuk penyesuaian awal
      handleResize();
    });
  </script>

 
</body>

</html>
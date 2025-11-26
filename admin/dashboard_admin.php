<?php
session_start();
include_once __DIR__ . '/../confiq/koneksi.php'; 
include '../template/heeder.php'; // header

// Hitung data dashboard
$jumlah_kegiatan    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM kegiatan"))['total'];
$jumlah_pembina     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pembina"))['total'];
$jumlah_pengumuman  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pengumuman"))['total'];
$jumlah_siswa       = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM siswa"))['total'];

// Ambil filter bulan & tahun dari GET (default: sekarang)
$bulan_siswa = $_GET['bulan_siswa'] ?? date('m');
$tahun_siswa = $_GET['tahun_siswa'] ?? date('Y');
$bulan_pembina = $_GET['bulan_pembina'] ?? date('m');
$tahun_pembina = $_GET['tahun_pembina'] ?? date('Y');
$bulan_kegiatan = $_GET['bulan_kegiatan'] ?? date('m');
$tahun_kegiatan = $_GET['tahun_kegiatan'] ?? date('Y');
$bulan_pengumuman = $_GET['bulan_pengumuman'] ?? date('m');
$tahun_pengumuman = $_GET['tahun_pengumuman'] ?? date('Y');

// Hitung jumlah berdasarkan filter
$jumlah_siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM siswa WHERE MONTH(created_at) = '$bulan_siswa' AND YEAR(created_at) = '$tahun_siswa'"))['total'];
$jumlah_pembina = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pembina WHERE MONTH(created_at) = '$bulan_pembina' AND YEAR(created_at) = '$tahun_pembina'"))['total'];
$jumlah_kegiatan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM kegiatan WHERE MONTH(tanggal_kegiatan) = '$bulan_kegiatan' AND YEAR(tanggal_kegiatan) = '$tahun_kegiatan'"))['total'];
$jumlah_pengumuman = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pengumuman WHERE MONTH(tanggal) = '$bulan_pengumuman' AND YEAR(tanggal) = '$tahun_pengumuman'"))['total'];

?>

<!-- Tambahkan Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<body>
  <div class="container-scroller">

    <!-- Promo Banner -->
    <div class="row p-0 m-0 proBanner" id="proBanner">
      <div class="col-md-12 p-0 m-0">
        <div class="card-body card-body-padding px-3 d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center justify-content-between">
            <p class="mb-0 font-weight-medium me-3 buy-now-text">Free 24/7 customer support, updates, and more with this template!</p>
            <a href="#" target="_blank" class="btn me-2 buy-now-btn border-0">Buy Now</a>
          </div>
          <div class="d-flex align-items-center justify-content-between">
            <a href="#"><i class="ti-home me-3 text-white"></i></a>
            <button id="bannerClose" class="btn border-0 p-0"><i class="ti-close text-white"></i></button>
          </div>
        </div>
      </div>
    </div>

    <!-- Navbar dan Sidebar -->
    <?php include '../template/navbar.php'; ?>
    <div class="container-fluid page-body-wrapper">
      <?php include '../template/sidebar.php'; ?>

      <div class="main-panel">
        <div class="content-wrapper">
          <?php
          $page = $_GET['page'] ?? 'dashboard_admin';

          if ($page == 'dashboard_admin') {
          ?>

            <!-- === DASHBOARD === -->
            <div class="position-relative">
              <div class="bg-primary text-white px-5 pt-5 pb-5 rounded-bottom-4" style="margin-top: -40px;">
                <h3 class="fw-semibold mb-2">Dashboard</h3>
                <p class="mb-5">Selamat datang di Sistem Informasi PIK-R.</p>
              </div>

              <div class="container position-relative" style="margin-top: -40px; z-index: 10;">
                <div class="row">
                  <!-- Statistik Cards -->
                  <?php
                  $stats = [
                    ['label' => 'Total Siswa', 'jumlah' => $jumlah_siswa, 'icon' => 'school', 'bg' => 'primary'],
                    ['label' => 'Pembina', 'jumlah' => $jumlah_pembina, 'icon' => 'account-tie', 'bg' => 'success'],
                    ['label' => 'Kegiatan', 'jumlah' => $jumlah_kegiatan, 'icon' => 'calendar-star', 'bg' => 'warning'],
                    ['label' => 'Pengumuman', 'jumlah' => $jumlah_pengumuman, 'icon' => 'bullhorn-outline', 'bg' => 'danger'],
                  ];
                  foreach ($stats as $stat) :
                  ?>
                    <div class="col-md-3 stretch-card grid-margin">
                      <div class="card bg-white shadow border-0">
                        <div class="card-body d-flex justify-content-between align-items-center">
                          <div>
                            <p class="mb-1 text-muted"><?= $stat['label']; ?></p>
                            <h3 class="fw-bold mb-0"><?= $stat['jumlah']; ?></h3>
                          </div>
                          <div class="bg-gradient-<?= $stat['bg']; ?> text-white rounded-circle p-3">
                            <i class="mdi mdi-<?= $stat['icon']; ?> fs-4"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>

                <!-- Diagram Statistik PIK-R -->
                <div class="card mt-4 shadow-sm border-0">
                  <div class="card-body">
                    <h5 class="fw-semibold text-primary mb-4">Diagram Statistik PIK-R</h5>

                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3 mb-4">
                      <!-- Filter Kegiatan -->
                      <form method="GET" class="col">
                        <input type="hidden" name="page" value="dashboard_admin">
                        <label class="form-label">Filter Siswa:</label>
                        <div class="d-flex gap-2">
                          <select name="bulan_siswa" class="form-select form-select-sm">
                            <?php for ($i = 1; $i <= 12; $i++) {
                              $bulan = str_pad($i, 2, "0", STR_PAD_LEFT);
                              $selected = ($bulan_siswa == $bulan) ? 'selected' : '';
                              echo "<option value='$bulan' $selected>$bulan</option>";
                            } ?>
                          </select>
                          <select name="tahun_siswa" class="form-select form-select-sm">
                            <?php for ($y = 2023; $y <= date('Y'); $y++) {
                              $selected = ($tahun_siswa == $y) ? 'selected' : '';
                              echo "<option value='$y' $selected>$y</option>";
                            } ?>
                          </select>
                          <button type="submit" class="btn btn-sm btn-primary">Tampilkan</button>
                        </div>
                      </form>

                      <!-- Filter Pengumuman -->
                      <form method="GET" class="col">
                        <input type="hidden" name="page" value="dashboard_admin">
                        <label class="form-label">Filter Pengumuman:</label>
                        <div class="d-flex gap-2">
                          <select name="bulan_pengumuman" class="form-select form-select-sm">
                            <?php for ($i = 1; $i <= 12; $i++) {
                              $bulan = str_pad($i, 2, "0", STR_PAD_LEFT);
                              $selected = ($bulan_pengumuman == $bulan) ? 'selected' : '';
                              echo "<option value='$bulan' $selected>$bulan</option>";
                            } ?>
                          </select>
                          <select name="tahun_pengumuman" class="form-select form-select-sm">
                            <?php for ($y = 2023; $y <= date('Y'); $y++) {
                              $selected = ($tahun_pengumuman == $y) ? 'selected' : '';
                              echo "<option value='$y' $selected>$y</option>";
                            } ?>
                          </select>
                          <button type="submit" class="btn btn-sm btn-primary">Tampilkan</button>
                        </div>
                      </form>

                      <!-- Filter Siswa -->
                      <form method="GET" class="col">
                        <input type="hidden" name="page" value="dashboard_admin">
                        <label class="form-label">Filter Kegiatan:</label>
                        <div class="d-flex gap-2">
                          <select name="bulan_kegiatan" class="form-select form-select-sm">
                            <?php for ($i = 1; $i <= 12; $i++) {
                              $bulan = str_pad($i, 2, "0", STR_PAD_LEFT);
                              $selected = ($bulan_kegiatan == $bulan) ? 'selected' : '';
                              echo "<option value='$bulan' $selected>$bulan</option>";
                            } ?>
                          </select>
                          <select name="tahun_kegiatan" class="form-select form-select-sm">
                            <?php for ($y = 2023; $y <= date('Y'); $y++) {
                              $selected = ($tahun_kegiatan == $y) ? 'selected' : '';
                              echo "<option value='$y' $selected>$y</option>";
                            } ?>
                          </select>
                          <button type="submit" class="btn btn-sm btn-primary">Tampilkan</button>
                        </div>
                      </form>

                      <!-- Filter Pembina -->
                      <form method="GET" class="col">
                        <input type="hidden" name="page" value="dashboard_admin">
                        <label class="form-label">Filter Pembina:</label>
                        <div class="d-flex gap-2">
                          <select name="bulan_pembina" class="form-select form-select-sm">
                            <?php for ($i = 1; $i <= 12; $i++) {
                              $bulan = str_pad($i, 2, "0", STR_PAD_LEFT);
                              $selected = ($bulan_pembina == $bulan) ? 'selected' : '';
                              echo "<option value='$bulan' $selected>$bulan</option>";
                            } ?>
                          </select>
                          <select name="tahun_pembina" class="form-select form-select-sm">
                            <?php for ($y = 2023; $y <= date('Y'); $y++) {
                              $selected = ($tahun_pembina == $y) ? 'selected' : '';
                              echo "<option value='$y' $selected>$y</option>";
                            } ?>
                          </select>
                          <button type="submit" class="btn btn-sm btn-primary">Tampilkan</button>
                        </div>
                      </form>
                    </div>
                    <div class="row">
                      <div class="col-md-3">
                        <div class="card shadow-sm border-0 mb-4">
                          <div class="card-body">
                            <h6 class="text-center">Siswa</h6>
                            <canvas id="chartSiswa" height="200"></canvas>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-3">
                        <div class="card shadow-sm border-0 mb-4">
                          <div class="card-body">
                            <h6 class="text-center">Pembina</h6>
                            <canvas id="chartPembina" height="200"></canvas>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-3">
                        <div class="card shadow-sm border-0 mb-4">
                          <div class="card-body">
                            <h6 class="text-center">Kegiatan</h6>
                            <canvas id="chartKegiatan" height="200"></canvas>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-3">
                        <div class="card shadow-sm border-0 mb-4">
                          <div class="card-body">
                            <h6 class="text-center">Pengumuman</h6>
                            <canvas id="chartPengumuman" height="200"></canvas>
                          </div>
                        </div>
                      </div>
                    </div>



                    <script>
                      function buatBarChart(id, label, jumlah, warna) {
                        const ctx = document.getElementById(id).getContext('2d');
                        new Chart(ctx, {
                          type: 'bar',
                          data: {
                            labels: [label],
                            datasets: [{
                              label: label,
                              data: [jumlah],
                              backgroundColor: warna
                            }]
                          },
                          options: {
                            responsive: true,
                            plugins: {
                              legend: {
                                display: false
                              },
                              tooltip: {
                                callbacks: {
                                  label: function(context) {
                                    return `${label}: ${context.parsed.y}`;
                                  }
                                }
                              }
                            },
                            scales: {
                              y: {
                                beginAtZero: true,
                                ticks: {
                                  stepSize: 1
                                }
                              }
                            }
                          }
                        });
                      }

                      buatBarChart('chartSiswa', 'Siswa', <?= $jumlah_siswa ?>, '#4e73df');
                      buatBarChart('chartPembina', 'Pembina', <?= $jumlah_pembina ?>, '#1cc88a');
                      buatBarChart('chartKegiatan', 'Kegiatan', <?= $jumlah_kegiatan ?>, '#f6c23e');
                      buatBarChart('chartPengumuman', 'Pengumuman', <?= $jumlah_pengumuman ?>, '#e74a3b');
                    </script>


                  </div>
                </div>

              <?php
            } elseif ($page == 'data_siswa') {
              include 'siswa/data_siswa.php';
            } elseif ($page == 'tambah_siswa') {
              include 'siswa/tambah_siswa.php';
            } elseif ($page == 'edit_siswa') {
              include 'siswa/edit_siswa.php';
            } elseif ($page == 'hapus_siswa') {
              include 'siswa/hapus_siswa.php';

              // === Tambahan untuk fitur Pembina / Guru ===
            } elseif ($page == 'data_pembina') {
              include 'pembina/data_pembina.php';
            } elseif ($page == 'tambah_pembina') {
              include 'pembina/tambah_pembina.php';
            } elseif ($page == 'edit_pembina') {
              include 'pembina/edit_pembina.php';
            } elseif ($page == 'hapus_pembina') {
              include 'pembina/hapus_pembina.php';

              // === Tambahan untuk fitur Frofile ===
            } elseif ($page == 'data_frofile') {
              include 'frofile/data_frofile.php';
            } elseif ($page == 'tambah_frofile') {
              include 'frofile/tambah_frofile.php';
            } elseif ($page == 'edit_frofile') {
              include 'frofile/edit_frofile.php';
            } elseif ($page == 'hapus_frofile') {
              include 'frofile/hapus_frofile.php';

              // === Tambahan untuk fitur Pengumuman ===
            } elseif ($page == 'data_pengumuman') {
              include 'pengumuman/data_pengumuman.php';
            } elseif ($page == 'tambah_pengumuman') {
              include 'pengumuman/tambah_pengumuman.php';
            } elseif ($page == 'edit_pengumuman') {
              include 'pengumuman/edit_pengumuman.php';
            } elseif ($page == 'hapus_pengumuman') {
              include 'pengumuman/hapus_pengumuman.php';


              // === Tambahan untuk fitur Kegiatan ===
            } elseif ($page == 'data_kegiatan') {
              include 'kegiatan/data_kegiatan.php';
            } elseif ($page == 'tambah_kegiatan') {
              include 'kegiatan/tambah_kegiatan.php';
            } elseif ($page == 'edit_kegiatan') {
              include 'kegiatan/edit_kegiatan.php';
            } elseif ($page == 'hapus_kegiatan') {
              include 'kegiatan/hapus_kegiatan.php';

              // === Fitur Galeri ===
            } elseif ($page == 'data_galeri') {
              include 'galeri/data_galeri.php';
            } elseif ($page == 'tambah_galeri') {
              include 'galeri/tambah_galeri.php';
            } elseif ($page == 'edit_galeri') {
              include 'galeri/edit_galeri.php';
            } elseif ($page == 'hapus_galeri') {
              include 'galeri/hapus_galeri.php';

              // === Fitur Tentang PIK-R ===
            } elseif ($page == 'data_tentang') {
              include 'tentang/data_tentang.php';
            } elseif ($page == 'tambah_tentang') {
              include 'tentang/tambah_tentang.php';
            } elseif ($page == 'edit_tentang') {
              include 'tentang/edit_tentang.php';
            } elseif ($page == 'hapus_tentang') {
              include 'tentang/hapus_tentang.php';

              // === Fitur Logo PIK-R ===
            } elseif ($page == 'data_logo') {
              include 'logo/data_logo.php';
            } elseif ($page == 'tambah_logo') {
              include 'logo/tambah_logo.php';
            } elseif ($page == 'edit_logo') {
              include 'logo/edit_logo.php';
            } elseif ($page == 'hapus_logo') {
              include 'logo/hapus_logo.php';

              // === Fitur Struktur Organisasi ===
            } elseif ($page == 'data_struktur') {
              include 'struktur/data_struktur.php';
            } elseif ($page == 'tambah_struktur') {
              include 'struktur/tambah_struktur.php';
            } elseif ($page == 'edit_struktur') {
              include 'struktur/edit_struktur.php';
            } elseif ($page == 'hapus_struktur') {
              include 'struktur/hapus.php';

              // === Fitur Kerja Sama ===
            } elseif ($page == 'data_kerjasama') {
              include 'kerjasama/data_kerjasama.php';
            } elseif ($page == 'tambah_kerjasama') {
              include 'kerjasama/tambah_kerjasama.php';
            } elseif ($page == 'edit_kerjasama') {
              include 'kerjasama/edit_kerjasama.php';
            } elseif ($page == 'hapus_kerjasama') {
              include 'kerjasama/hapus_kerjasama.php';

              // === Fitur Prestasi ===
            } elseif ($page == 'data_prestasi') {
              include 'prestasi/data_prestasi.php';
            } elseif ($page == 'tambah_prestasi') {
              include 'prestasi/tambah_prestasi.php';
            } elseif ($page == 'edit_prestasi') {
              include 'prestasi/edit_prestasi.php';
            } elseif ($page == 'hapus_prestasi') {
              include 'prestasi/hapus_prestasi.php';

              // === Fitur Berita ===
            } elseif ($page == 'data_berita') {
              include 'berita/data_berita.php';
            } elseif ($page == 'tambah_berita') {
              include 'berita/tambah_berita.php';
            } elseif ($page == 'edit_berita') {
              include 'berita/edit_berita.php';
            } elseif ($page == 'hapus_berita') {
              include 'berita/hapus_berita.php';

              // === Fitur Pendaftaran Lomba ===
            } elseif ($page == 'data_pendaftaran') {
              include 'pendaftaran/data_pendaftaran.php';
            } elseif ($page == 'tambah_pendaftaran') {
              include 'pendaftaran/tambah_pendaftaran.php';
            } elseif ($page == 'edit_pendaftaran') {
              include 'pendaftaran/edit_pendaftaran.php';
            } elseif ($page == 'hapus_pendaftaran') {
              include 'pendaftaran/hapus_pendaftaran.php';

              // === Fitur Tambah Lomba ===
            } elseif ($page == 'data_lomba') {
              include 'lomba/data_lomba.php';
            } elseif ($page == 'tambah_lomba') {
              include 'lomba/tambah_lomba.php';
            } elseif ($page == 'edit_lomba') {
              include 'lomba/edit_lomba.php';
            } elseif ($page == 'hapus_lomba') {
              include 'lomba/hapus_lomba.php';
            } else {


              echo "<div class='alert alert-danger text-center'>Halaman <b>$page</b> tidak ditemukan.</div>";
            }
              ?>



              </div> <!-- End content-wrapper -->

              <!-- Footer -->
              <?php include '../template/footer.php'; ?>
            </div>
            <!-- End Main Panel -->

        </div>
        <!-- End Page Body Wrapper -->

      </div>
      <!-- End Container Scroller -->
</body>

</html>
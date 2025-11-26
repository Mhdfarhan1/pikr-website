<?php if (!isset($_SESSION)) session_start(); ?>
<?php $page = $_GET['page'] ?? ''; ?>

<nav class="sidebar sidebar-offcanvas bg-white shadow-sm" id="sidebar">
    <!-- User Info -->
    <div class="px-3 py-4 d-flex flex-column align-items-center text-center bg-white bg-opacity-75 rounded shadow-sm mx-2 mb-4">
        <img src="../assets/img/pp web.png" alt="Profile"
            class="rounded-circle mb-2" width="60" height="60" style="object-fit: cover;">
        <hr class="w-100 my-2 border-top border-2 border-secondary" />
        <div class="mt-1 text-dark">
            <h6 class="mb-0 fw-semibold" style="font-size: 14px;">
                <?= $_SESSION['username'] ?? 'Guest'; ?>
            </h6>
            <small style="font-size: 12px;" class="text-muted">
                <?= $_SESSION['role'] ?? 'Role tidak diketahui'; ?>
            </small>
        </div>
    </div>

    <ul class="nav flex-column px-2">
        <hr class="mx-3 border-top border-secondary-subtle" />

        <!-- Data Master -->
        <li class="nav-item mb-2">
            <span class="text-muted text-uppercase fw-bold small ps-2 d-block mb-1">Data Master</span>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link d-flex align-items-center px-3 py-2 rounded <?= $page == 'dashboard_admin' ? 'fw-bold text-dark' : 'text-muted' ?>"
                href="dashboard_admin.php?page=dashboard_admin">
                <i class="mdi mdi-view-dashboard-outline me-2"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <hr class="mx-3 border-top border-secondary-subtle" />

        <!-- Master Admin -->
        <li class="nav-item mb-2 mt-3">
            <span class="text-muted text-uppercase fw-bold small ps-2 d-block mb-1">Master Admin</span>
        </li>

        <li class="nav-item mb-2">
            <a class="nav-link d-flex justify-content-between align-items-center px-3 py-2 rounded <?= in_array($page, ['data_siswa', 'data_pembina']) ? 'fw-bold text-dark' : 'text-muted' ?>"
                data-bs-toggle="collapse" href="#dataMaster" role="button"
                aria-expanded="<?= in_array($page, ['data_siswa', 'data_pembina']) ? 'true' : 'false' ?>"
                aria-controls="dataMaster">
                <span><i class="mdi mdi-database-outline me-2"></i> Manajemen Data</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse <?= in_array($page, ['data_siswa', 'data_pembina']) ? 'show' : '' ?>" id="dataMaster">
                <ul class="nav flex-column ms-3 mt-2">
                    <li class="nav-item mb-1">
                        <a class="nav-link py-1 px-3 rounded <?= $page == 'data_siswa' ? 'fw-bold text-dark' : 'text-muted' ?>"
                            href="dashboard_admin.php?page=data_siswa">
                            <i class="mdi mdi-account-multiple-outline me-2"></i> Data Siswa
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link py-1 px-3 rounded <?= $page == 'data_pembina' ? 'fw-bold text-dark' : 'text-muted' ?>"
                            href="dashboard_admin.php?page=data_pembina">
                            <i class="mdi mdi-account-star-outline me-2"></i> Data Pembina
                        </a>
                    </li>

                    <li class="nav-item mb-1">
                        <a class="nav-link py-1 px-3 rounded <?= $page == 'pendaftaran' ? 'fw-bold text-dark' : 'text-muted' ?>"
                            href="dashboard_admin.php?page=data_pendaftaran">
                            <i class="mdi mdi-account-plus-outline me-2"></i>Pendaftar Lomba
                        </a>
                    </li>

                    <li class="nav-item mb-1">
                        <a class="nav-link py-1 px-3 rounded <?= $page == 'lomba' ? 'fw-bold text-dark' : 'text-muted' ?>"
                            href="dashboard_admin.php?page=data_lomba">
                            <i class="mdi mdi-playlist-plus me-2"></i>Tambah Lomba
                        </a>
                    </li>


                </ul>
            </div>
        </li>

        <hr class="mx-3 border-top border-secondary-subtle" />

        <!-- Master Landing -->
        <li class="nav-item mb-2 mt-3">
            <span class="text-muted text-uppercase fw-bold small ps-2 d-block mb-1">Master Landing</span>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link d-flex align-items-center px-3 py-2 rounded <?= $page == 'data_frofile' ? 'fw-bold text-dark' : 'text-muted' ?>"
                href="dashboard_admin.php?page=data_frofile">
                <i class="mdi mdi-account-box-outline me-2"></i>
                <span>Frofile</span>
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link d-flex align-items-center px-3 py-2 rounded <?= $page == 'data_tentang' ? 'fw-bold text-dark' : 'text-muted' ?>"
                href="dashboard_admin.php?page=data_tentang">
                <i class="mdi mdi-information-outline me-2"></i>
                <span>Tentang</span>
            </a>
        </li>

        <hr class="mx-3 border-top border-secondary-subtle mt-4" />

        <!-- Master Informasi -->
        <li class="nav-item mb-2 mt-3">
            <span class="text-muted text-uppercase fw-bold small ps-2 d-block mb-1">Master Informasi</span>
        </li>

        <li class="nav-item mb-2">
            <a class="nav-link d-flex align-items-center px-3 py-2 rounded <?= $page == 'data_pengumuman' ? 'fw-bold text-dark' : 'text-muted' ?>"
                href="dashboard_admin.php?page=data_pengumuman">
                <i class="mdi mdi-bullhorn-outline me-2"></i>
                <span>Pengumuman</span>
            </a>
        </li>

        <li class="nav-item mb-2">
            <a class="nav-link d-flex align-items-center px-3 py-2 rounded <?= $page == 'data_kegiatan' ? 'fw-bold text-dark' : 'text-muted' ?>"
                href="dashboard_admin.php?page=data_kegiatan">
                <i class="bi bi-calendar2-event-fill me-2"></i>
                <span>Kegiatan</span>
            </a>
        </li>

        <li class="nav-item mb-2">
            <a class="nav-link d-flex align-items-center px-3 py-2 rounded <?= $page == 'data_galeri' ? 'fw-bold text-dark' : 'text-muted' ?>"
                href="dashboard_admin.php?page=data_galeri">
                <i class="bi bi-images me-2"></i>
                <span>Galeri</span>
            </a>
        </li>

        <li class="nav-item mb-2">
            <a class="nav-link d-flex align-items-center px-3 py-2 rounded <?= $page == 'data_logo' ? 'fw-bold text-dark' : 'text-muted' ?>"
                href="dashboard_admin.php?page=data_logo">
                <i class="bi bi-patch-check me-2"></i>
                <span>Logo</span>
            </a>
        </li>

        <li class="nav-item mb-2">
            <a class="nav-link d-flex align-items-center px-3 py-2 rounded <?= $page == 'data_struktur' ? 'fw-bold text-dark' : 'text-muted' ?>"
                href="dashboard_admin.php?page=data_struktur">
                <i class="bi bi-diagram-3-fill me-2"></i>
                <span>Struktur Organisasi</span>
            </a>
        </li>

        <li class="nav-item mb-2">
            <a class="nav-link d-flex align-items-center px-3 py-2 rounded <?= $page == 'data_kerjasama' ? 'fw-bold text-dark' : 'text-muted' ?>"
                href="dashboard_admin.php?page=data_kerjasama">
                <i class="bi bi-people-fill me-2"></i>
                <span>Kerja Sama</span>
            </a>
        </li>

        <li class="nav-item mb-2">
            <a class="nav-link d-flex align-items-center px-3 py-2 rounded <?= $page == 'data_prestasi' ? 'fw-bold text-dark' : 'text-muted' ?>"
                href="dashboard_admin.php?page=data_prestasi">
                <i class="bi bi-award-fill me-2"></i>
                <span>Prestasi</span>
            </a>
        </li>

        <li class="nav-item mb-2">
            <a class="nav-link d-flex align-items-center px-3 py-2 rounded <?= $page == 'data_berita' ? 'fw-bold text-dark' : 'text-muted' ?>"
                href="dashboard_admin.php?page=data_berita">
                <i class="bi bi-newspaper me-2"></i>
                <span>Berita</span>
            </a>
        </li>

        <li class="nav-item mb-2">
            <a class="nav-link d-flex align-items-center px-3 py-2 rounded <?= $page == 'youtube_channel' ? 'fw-bold text-dark' : 'text-muted' ?>"
                href="dashboard_admin.php?page=youtube_channel">
                <i class="bi bi-play-btn me-2"></i>
                <span> Upload YouTube</span>
            </a>
        </li>



        <!-- Logout -->
        <li class="nav-item mt-2">
            <a class="nav-link text-danger px-3 py-2 d-flex align-items-center"
                href="../index.php" onclick="return confirm('Keluar dari sistem?')">
                <i class="mdi mdi-logout me-2"></i> Logout
            </a>
        </li>
    </ul>
</nav>
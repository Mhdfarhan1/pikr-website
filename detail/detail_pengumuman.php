<?php
include '../confiq/koneksi.php';

$id = $_GET['id'] ?? 0;
$query = mysqli_query($conn, "SELECT * FROM pengumuman WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='../informasi/pengumuman.php';</script>";
    exit;
}

include '../layouts/heeder.php';
?>

<!-- Breadcrumb -->
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
                    <a href="<?= $base_url ?>informasi/pengumuman.php" class="text-decoration-none text-primary fw-semibold">
                        Pengumuman
                    </a>
                </li>
                <li class="breadcrumb-item active text-muted" aria-current="page">
                    <?= htmlspecialchars($data['judul']) ?>
                </li>
            </ol>
        </nav>
    </div>
</section>

<!-- Detail Pengumuman -->
<section class="container py-5">
    <div class="row">
        <div class="col-md-4 text-center mb-4">
            <?php if (!empty($data['gambar']) && file_exists('../upload/pengumuman/' . $data['gambar'])): ?>
                <img src="../upload/pengumuman/<?= htmlspecialchars($data['gambar']) ?>" class="img-fluid rounded shadow" alt="Gambar Pengumuman">
            <?php else: ?>
                <div class="bg-secondary text-white py-5 rounded shadow">Tidak ada gambar</div>
            <?php endif; ?>
        </div>
        <div class="col-md-8">
            <h4 class="fw-bold"><?= htmlspecialchars($data['judul']) ?></h4>
            <div class="text-muted small mb-2">
                <i class="bi bi-calendar3 me-1"></i> <?= date('d F Y, H:i', strtotime($data['tanggal'])) ?>
            </div>
            <p class="text-dark" style="white-space: pre-line; line-height: 1.6;">
                <?= htmlspecialchars($data['isi']) ?>
            </p>
        </div>
    </div>
</section>

<?php include '../layouts/footer.php'; ?>

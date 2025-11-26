<?php
session_start();
include '../confiq/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

$lomba_query = mysqli_query($conn, "SELECT * FROM lomba");
?>

<?php include '../layouts/heeder.php'; ?>

<!-- Google Font & SweetAlert -->
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    body {
        font-family: 'Roboto', sans-serif;
        background: #f2f5fa;
    }
</style>

<section class="py-5" style="margin-top: 100px;">
    <div class="container">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-primary text-center mb-4">
                        <i class="bi bi-pencil-square me-2"></i> Formulir Pendaftaran Lomba
                    </h4>

                    <?php if (isset($_SESSION['success'])) : ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: '🎉 Pendaftaran Berhasil!',
                                                    html: `
                                <div style="text-align: center; line-height: 1.4; font-size: 0.9rem; color: #444;">
                                    <p>Halo, <strong><?= htmlspecialchars($_SESSION['nama_peserta'] ?? 'Peserta') ?></strong>! Terima kasih sudah mendaftar lomba <strong><?= htmlspecialchars($_SESSION['nama_lomba'] ?? '') ?></strong>.</p>
                                    <p>Data pendaftaran Anda telah kami terima dengan baik pada tanggal <strong><?= date('d F Y, H:i', time()) ?></strong>.</p>
                                    <p>Selanjutnya, panitia akan melakukan verifikasi dan menghubungi Anda melalui nomor kontak yang telah diberikan.</p>
                                    <p>Pastikan nomor <strong>HP / WA</strong> Anda aktif dan mudah dihubungi.</p>
                                    <p>Jika ada pertanyaan, jangan ragu untuk menghubungi <a href="mailto:panitia@example.com">panitia lomba</a> atau kunjungi kantor sekolah.</p>
                                    <hr>
                                    <p style="font-size: 0.8rem; color: #666;">
                                        🎯 <em>Semangat berkompetisi dan semoga sukses!</em>
                                    </p>
                                </div>
                                `,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Tutup'
                            });
                        </script>
                        <?php
                        unset($_SESSION['success']);
                        unset($_SESSION['nama_peserta']);
                        unset($_SESSION['nama_lomba']);
                        ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])) : ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: '❌ Terjadi Kesalahan!',
                                html: `
                                <div style="text-align: center; line-height: 1.4; font-size: 0.9rem; color: #444;">
                                    <p><strong>Maaf,</strong> terjadi masalah saat memproses data pendaftaran Anda.</p>
                                    <p><strong>Pesan error:</strong> <?= htmlspecialchars($_SESSION['error']) ?></p>
                                    <p>Pastikan semua data telah diisi dengan benar dan sesuai format.</p>
                                    <p>Jika Anda merasa ini kesalahan sistem, silakan hubungi <a href="mailto:admin@example.com">admin</a> untuk bantuan lebih lanjut.</p>
                                    <hr>
                                    <p style="font-size: 0.8rem; color: #666;">
                                        ⚠️ <em>Jangan putus asa, coba periksa data dan kirim ulang.</em>
                                    </p>
                                </div>
                                `,
                                confirmButtonColor: '#d33',
                                confirmButtonText: 'Tutup'
                            });
                        </script>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>



                    <form id="daftarForm" action="proses_daftar_lomba.php" method="POST">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Peserta</label>
                            <input type="text" class="form-control" id="nama" name="nama_peserta" required>
                        </div>
                        <div class="mb-3">
                            <label for="kelas" class="form-label">Kelas</label>
                            <input type="text" class="form-control" id="kelas" name="kelas" required>
                        </div>
                        <div class="mb-3">
                            <label for="lomba" class="form-label">Pilih Lomba</label>
                            <select class="form-select" id="lomba" name="id_lomba" required>
                                <option value="" disabled selected>-- Pilih Lomba --</option>
                                <?php while ($row = mysqli_fetch_assoc($lomba_query)) : ?>
                                    <option value="<?= $row['id_lomba'] ?>"><?= $row['nama_lomba'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="anggota_kelompok" class="form-label">Anggota Kelompok <small class="text-muted">(opsional)</small></label>
                            <textarea class="form-control" name="anggota_kelompok" rows="3" placeholder="Kosongkan jika individu"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="kontak" class="form-label">No. HP / WA</label>
                            <input type="text" class="form-control" id="kontak" name="kontak" required>
                        </div>
                        <div class="d-grid">
                            <button type="button" id="submitBtn" class="btn btn-primary">
                                <i class="bi bi-send-check me-2"></i> Daftar Sekarang
                            </button>
                        </div>
                    </form>

                    <script>
                        document.getElementById('submitBtn').addEventListener('click', function() {
                            // Ambil nilai inputan wajib
                            const nama = document.getElementById('nama').value.trim();
                            const kelas = document.getElementById('kelas').value.trim();
                            const lomba = document.getElementById('lomba').value;
                            const kontak = document.getElementById('kontak').value.trim();

                            // Cek apakah ada field wajib yang kosong
                            if (!nama || !kelas || !lomba || !kontak) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Data belum lengkap',
                                    text: 'Harap isi semua kolom wajib kecuali anggota kelompok.',
                                    confirmButtonColor: '#3085d6'
                                });
                                return; // Jangan lanjut submit form
                            }

                            // Jika sudah lengkap, tampilkan konfirmasi
                            Swal.fire({
                                title: 'Yakin ingin mendaftar?',
                                text: "Pastikan semua data sudah benar sebelum dikirim!",
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ya, Daftar!',
                                cancelButtonText: 'Batal'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    document.getElementById('daftarForm').submit();
                                }
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../layouts/footer.php'; ?>
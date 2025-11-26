<?php
session_start();

// Aktifkan error reporting untuk debugging (HAPUS INI SETELAH DEBUGGING SELESAI!)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include '../confiq/koneksi.php'; // Atau '../confiq/koneksi.php' tergantung lokasi

// Pastikan data POST dikirim
if (!isset($_POST['username']) || !isset($_POST['password'])) {
    $_SESSION['error'] = "Mohon masukkan username dan password.";
    header("Location: login.php");
    exit(); // Penting: Hentikan eksekusi setelah redirect
}

$username = $_POST['username'];
$password = $_POST['password'];

// Gunakan Prepared Statements untuk keamanan (Mencegah SQL Injection)
// Ini adalah PERBAIKAN PENTING dari sisi keamanan
$stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
if ($stmt === false) {
    // Penanganan error jika prepare statement gagal
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if ($data && password_verify($password, $data['password'])) {
    $_SESSION['id_user'] = $data['id'];
    $_SESSION['username'] = $data['username'];
    $_SESSION['role'] = $data['role'];

    // Ambil nama lengkap dari tabel sesuai role
    if ($data['role'] == 'pembina') {
        $stmt_guru = $conn->prepare("SELECT nama_guru FROM pembina WHERE id_user = ?");
        if ($stmt_guru === false) { die('Prepare failed: ' . htmlspecialchars($conn->error)); }
        $stmt_guru->bind_param("i", $data['id']);
        $stmt_guru->execute();
        $result_guru = $stmt_guru->get_result();
        $guru = $result_guru->fetch_assoc();
        $stmt_guru->close();
        $_SESSION['nama_lengkap'] = $guru['nama_guru'] ?? 'Pembina';
        header("Location: ../pembina/dashboard_pembina.php");
        exit();
    } elseif ($data['role'] == 'siswa') {
        $stmt_siswa = $conn->prepare("SELECT nama_siswa FROM siswa WHERE id_user = ?");
        if ($stmt_siswa === false) { die('Prepare failed: ' . htmlspecialchars($conn->error)); }
        $stmt_siswa->bind_param("i", $data['id']);
        $stmt_siswa->execute();
        $result_siswa = $stmt_siswa->get_result();
        $siswa = $result_siswa->fetch_assoc();
        $stmt_siswa->close();
        $_SESSION['nama_lengkap'] = $siswa['nama_siswa'] ?? 'Siswa';
        header("Location: ../siswa/dashboard_siswa.php");
        exit();
    } elseif ($data['role'] == 'admin') {
        // Asumsi nama lengkap admin sudah ada di tabel users atau bisa diset manual
        // Jika nama_lengkap ada di tabel users: $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
        $_SESSION['nama_lengkap'] = 'Administrator'; // Atau ambil dari $data['nama_lengkap'] jika ada di tabel users
        header("Location: ../admin/dashboard_admin.php");
        exit();
    } else {
        $_SESSION['error'] = "Role tidak dikenali.";
        header("Location: login.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Username atau password salah.";
    header("Location: login.php");
    exit(); // Penting: Hentikan eksekusi setelah redirect
}
?>
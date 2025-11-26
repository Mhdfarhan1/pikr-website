<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login PIK-R</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- animate.css CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <style>
    body {
      background: linear-gradient(135deg, #e0f2fe, #f0f9ff);
    }
    .glass {
      background: rgba(255, 255, 255, 0.3);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.3);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    .form-input {
      background: rgba(255, 255, 255, 0.5);
      backdrop-filter: blur(10px);
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4">

  <div class="w-full max-w-md glass p-10 rounded-3xl text-gray-800 animate__animated animate__fadeInDown">
    <div class="text-center mb-8">
      <img src="../assets/img/logo 1.png" alt="Logo PIK-R" class="mx-auto h-20 mb-3 animate__animated animate__fadeInUp">
      <h2 class="text-3xl font-bold tracking-wide">Login PIK-R</h2>
      <p class="text-sm text-gray-700">Masuk untuk mengakses sistem</p>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="bg-red-100 text-red-700 p-3 mb-4 rounded animate__animated animate__shakeX">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
      </div>
    <?php endif; ?>

    <form action="proses_login.php" method="POST" class="space-y-5 animate__animated animate__fadeInUp animate__delay-1s">
      <!-- Username -->
      <div>
        <label class="block mb-1 font-semibold">Username</label>
        <div class="flex items-center border border-gray-300 rounded-xl px-3 form-input">
          <i class="bi bi-person text-gray-500 mr-2"></i>
          <input type="text" name="username" required placeholder="Masukkan username"
                 class="w-full py-2 bg-transparent outline-none text-gray-800">
        </div>
      </div>

      <!-- Password -->
      <div>
        <label class="block mb-1 font-semibold">Password</label>
        <div class="flex items-center border border-gray-300 rounded-xl px-3 relative form-input">
          <i class="bi bi-lock text-gray-500 mr-2"></i>
          <input type="password" name="password" id="password" required placeholder="Masukkan password"
                 class="w-full py-2 bg-transparent outline-none text-gray-800">
          <button type="button" onclick="togglePassword()" class="absolute right-3 text-gray-500">
            <i id="toggleIcon" class="bi bi-eye"></i>
          </button>
        </div>
      </div>

      <!-- Tombol Masuk -->
      <button type="submit"
              class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-xl shadow transition duration-300 ease-in-out">
        Masuk
      </button>
    </form>

    <!-- Kembali ke beranda -->
    <div class="text-center text-sm text-gray-700 mt-6 animate__animated animate__fadeInUp animate__delay-2s">
      <a href="../index.php" class="inline-flex items-center gap-1 text-blue-600 hover:underline font-medium transition">
        <i class="bi bi-arrow-left-circle"></i> Kembali ke Beranda
      </a>
    </div>
  </div>

  <!-- Toggle password -->
  <script>
    function togglePassword() {
      const passwordInput = document.getElementById('password');
      const toggleIcon = document.getElementById('toggleIcon');
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('bi-eye');
        toggleIcon.classList.add('bi-eye-slash');
      } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('bi-eye-slash');
        toggleIcon.classList.add('bi-eye');
      }
    }
  </script>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistem Dalam Pembaruan Inti</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Montserrat', sans-serif;
        background: #111827; /* Latar belakang biru pekat */
        color: #e0e7ff;
        overflow: hidden;
        position: relative;
    }

    /* Kanvas untuk Latar Belakang Plexus */
    #plexus-bg {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
        opacity: 0.6;
    }

    /* Kontainer untuk memposisikan kartu di tengah */
    .card-container {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        width: 100vw;
        position: relative;
        z-index: 2; /* Di atas kanvas */
        padding: 1rem; /* Padding untuk mobile */
    }

    /* Kartu Kaca (Tanpa 3D) */
    .maintenance-card {
        width: 90%;
        max-width: 600px;
        background: rgba(0, 0, 0, 0.25);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(52, 211, 153, 0.3); /* Border hijau */
        border-radius: 1.5rem; /* 24px */
        padding: 2.5rem; /* 40px */
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        text-align: center;
    }

    /* === BARU: Animasi Fade-in untuk Kartu === */
    .animate-fade-in {
        animation: fadeIn 1.2s ease-out forwards;
        opacity: 0;
    }
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.98);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    /* === Selesai Bagian Baru === */


    /* Ikon Utama (Glow "Bernaas") */
    .icon-container {
        font-size: 5rem;
        color: #34d399; /* Hijau cerah */
        animation: breathGlow 3s infinite alternate ease-in-out;
        margin-bottom: 2rem;
        /* transform: translateZ(50px); DIHAPUS */
    }
    @keyframes breathGlow {
        from {
            text-shadow: 0 0 10px #34d399, 0 0 20px #34d399, 0 0 30px #10b981;
            opacity: 0.8;
        }
        to {
            text-shadow: 0 0 20px #34d399, 0 0 40px #34d399, 0 0 60px #10b981;
            opacity: 1;
        }
    }

    /* Animasi Teks "Flicker" (Reboot) */
    .flicker-text {
        color: #ffffff;
        text-shadow: 0 0 5px #fff, 0 0 10px #fff, 0 0 15px #34d399;
        animation: flicker 2s infinite alternate;
        will-change: opacity;
    }
    @keyframes flicker {
        0%, 18%, 22%, 25%, 53%, 57%, 100% {
            opacity: 1;
            text-shadow: 0 0 5px #fff, 0 0 10px #fff, 0 0 15px #34d399;
        }
        20%, 24%, 55% {
            opacity: 0.7;
            text-shadow: none;
        }
    }

    /* Progress Bar (Tetap keren dengan glow hijau) */
    .progress-bar-container {
        background: rgba(0, 0, 0, 0.3);
        border-radius: 9999px;
        height: 8px;
        overflow: hidden;
        box-shadow: inset 0 0 5px rgba(0,0,0,0.3);
    }
    .progress-bar-indicator {
        height: 100%;
        width: 30%;
        background: linear-gradient(90deg, #34d399, #10b981);
        border-radius: 9999px;
        animation: indeterminate-progress 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        box-shadow: 0 0 10px rgba(52, 211, 153, 0.6);
    }
    @keyframes indeterminate-progress {
        0% { transform: translateX(-100%); }
        50% { transform: translateX(200%); }
        100% { transform: translateX(400%); }
    }

    /* Tombol (Sama seperti sebelumnya, sudah bagus) */
    .btn-primary-green {
        background-color: #34d399; 
        color: #111827; 
        padding: 0.75rem 2rem;
        border-radius: 9999px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(52, 211, 153, 0.3);
    }
    .btn-primary-green:hover {
        background-color: #6ee7b7;
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 8px 20px rgba(52, 211, 153, 0.5);
    }
    .btn-secondary-link {
        color: #e0e7ff;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
    }
    .btn-secondary-link:hover {
        color: #6ee7b7;
    }
</style>
</head>
<body class="flex items-center justify-center h-screen px-4">

<canvas id="plexus-bg"></canvas>

<div class="card-container">
    <div class="maintenance-card animate-fade-in">
        
        <div class="icon-container">
            <i class="fas fa-dna"></i>
        </div>
        
        <h1 class="text-3xl md:text-3xl font-bold mb-4 flicker-text">
            Sistem Sedang Diperbaiki
        </h1>
        
        <p class="text-lg md:text-md text-blue-200 mb-8">
            Kami sedang melakukan pembaruan inti untuk arsitektur sistem.
            Mohon bersabar, kami akan kembali dengan performa baru.
        </p>
        
        <div class="w-full max-w-lg mx-auto h-2.5 progress-bar-container mt-12 mb-10">
            <div class="progress-bar-indicator"></div>
        </div>
        
        <div class="mt-12 flex flex-col sm:flex-row items-center justify-center gap-6">
            <a href="javascript:location.reload();" class="btn-primary-green">
                <i class="fas fa-redo-alt mr-2"></i> Coba Refresh
            </a>
            
            <a href="https://wa.me/6282229245081?text=Halo%20Admin,%20saya%20butuh%20bantuan%20terkait%20website." 
               class="btn-secondary-link" 
               target="_blank" 
               rel="noopener noreferrer">
                <i class="fab fa-whatsapp mr-2"></i>
                <span>Hubungi Admin</span>
            </a>
        </div>
    </div>
</div>

<script>
    // === SKRIP UNTUK KARTU 3D (TILT) ===
    // --- DIHAPUS ---

    // === SKRIP UNTUK LATAR BELAKANG PLEXUS INTERAKTIF ===
    const canvas = document.getElementById('plexus-bg');
    const ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    let particles = [];
    const numParticles = window.innerWidth > 768 ? 100 : 50; // Lebih sedikit partikel di mobile
    const lineColor = 'rgba(52, 211, 153, 0.2)'; // Warna garis hijau
    const particleColor = 'rgba(52, 211, 153, 0.7)'; // Warna titik hijau
    const maxDist = 120; // Jarak maksimum untuk menggambar garis

    const mouse = {
        x: null,
        y: null,
        radius: 150
    };

    window.addEventListener('mousemove', (e) => {
        mouse.x = e.x;
        mouse.y = e.y;
    });
    window.addEventListener('mouseout', () => {
        mouse.x = null;
        mouse.y = null;
    });

    class Particle {
        constructor() {
            this.x = Math.random() * canvas.width;
            this.y = Math.random() * canvas.height;
            this.size = Math.random() * 2 + 1;
            this.speedX = (Math.random() * 2 - 1) * 0.5;
            this.speedY = (Math.random() * 2 - 1) * 0.5;
        }
        update() {
            if (this.x > canvas.width || this.x < 0) this.speedX = -this.speedX;
            if (this.y > canvas.height || this.y < 0) this.speedY = -this.speedY;
            this.x += this.speedX;
            this.y += this.speedY;
        }
        draw() {
            ctx.fillStyle = particleColor;
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fill();
        }
    }

    function init() {
        particles = [];
        for (let i = 0; i < numParticles; i++) {
            particles.push(new Particle());
        }
    }

    function connect() {
        for (let a = 0; a < particles.length; a++) {
            for (let b = a + 1; b < particles.length; b++) {
                const dx = particles[a].x - particles[b].x;
                const dy = particles[a].y - particles[b].y;
                const dist = Math.sqrt(dx * dx + dy * dy);

                if (dist < maxDist) {
                    ctx.strokeStyle = `rgba(52, 211, 153, ${1 - dist / maxDist})`;
                    ctx.lineWidth = 0.5;
                    ctx.beginPath();
                    ctx.moveTo(particles[a].x, particles[a].y);
                    ctx.lineTo(particles[b].x, particles[b].y);
                    ctx.stroke();
                }
            }
            // Hubungkan ke mouse
            if (mouse.x) {
                const dx = particles[a].x - mouse.x;
                const dy = particles[a].y - mouse.y;
                const dist = Math.sqrt(dx * dx + dy * dy);
                if (dist < mouse.radius) {
                    ctx.strokeStyle = `rgba(52, 211, 153, ${0.5 - dist / mouse.radius})`;
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    ctx.moveTo(particles[a].x, particles[a].y);
                    ctx.lineTo(mouse.x, mouse.y);
                    ctx.stroke();
                }
            }
        }
    }

    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        for (let particle of particles) {
            particle.update();
            particle.draw();
        }
        connect();
        requestAnimationFrame(animate);
    }

    window.addEventListener('resize', () => {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        mouse.radius = 150;
        init();
    });

    init();
    animate();
</script>

</body>
</html>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>
    <script>
      document.addEventListener("DOMContentLoaded", function() {
        const btn = document.getElementById('loadMoreBtn');
        btn.addEventListener('click', function() {
          document.querySelectorAll('.gallery-more.d-none').forEach(function(el) {
            el.classList.remove('d-none');
          });
          btn.style.display = 'none';
        });
      });
    </script>
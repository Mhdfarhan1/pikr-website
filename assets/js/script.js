document.addEventListener("DOMContentLoaded", function () {
    const hiddenItems = document.querySelectorAll(".gallery-hidden");
    const loadMoreBtn = document.getElementById("loadMoreBtn");

    loadMoreBtn.addEventListener("click", function () {
      hiddenItems.forEach(item => item.style.display = "block");
      loadMoreBtn.style.display = "none"; // sembunyikan tombol setelah klik
    });
  });
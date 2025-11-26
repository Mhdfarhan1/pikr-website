(function () {
  "use strict";

  // ===== Scroll toggle .scrolled class =====
  function toggleScrolled() {
    const body = document.querySelector('body');
    const header = document.querySelector('#header');
    if (!header) return;

    const isSticky = header.classList.contains('scroll-up-sticky') ||
      header.classList.contains('sticky-top') ||
      header.classList.contains('fixed-top');

    if (isSticky) {
      body.classList.toggle('scrolled', window.scrollY > 100);
    }
  }

  document.addEventListener('scroll', toggleScrolled);
  window.addEventListener('load', toggleScrolled);

  // ===== Mobile nav toggle =====
  function mobileNavToggle() {
    document.body.classList.toggle('mobile-nav-active');
    const btn = document.querySelector('.mobile-nav-toggle');
    if (btn) {
      btn.classList.toggle('bi-list');
      btn.classList.toggle('bi-x');
    }
  }

  const mobileNavToggleBtn = document.querySelector('.mobile-nav-toggle');
  if (mobileNavToggleBtn) {
    mobileNavToggleBtn.addEventListener('click', mobileNavToggle);

    document.querySelectorAll('#navmenu a').forEach(link => {
      link.addEventListener('click', () => {
        if (document.body.classList.contains('mobile-nav-active')) {
          mobileNavToggle();
        }
      });
    });
  }

  // ===== Dropdown toggle (mobile) =====
  const dropdownToggles = document.querySelectorAll('.navmenu .toggle-dropdown');
  if (dropdownToggles.length > 0) {
    dropdownToggles.forEach(toggle => {
      toggle.addEventListener('click', function (e) {
        e.preventDefault();
        const parent = this.closest('.dropdown');
        if (parent) {
          parent.classList.toggle('active');
          const submenu = parent.querySelector('ul');
          if (submenu) submenu.classList.toggle('dropdown-active');
        }
      });
    });
  }

  // ===== Preloader (hapus jika tak dipakai) =====
  const preloader = document.querySelector('#preloader');
  if (preloader) {
    window.addEventListener('load', () => {
      preloader.remove();
    });
  }

  // ===== AOS Animasi scroll =====
  function aosInit() {
    if (typeof AOS !== 'undefined') {
      AOS.init({
        duration: 600,
        easing: 'ease-in-out',
        once: true,
        mirror: false
      });
    }
  }
  window.addEventListener('load', aosInit);

  // ===== GLightbox =====
  if (typeof GLightbox !== 'undefined') {
    GLightbox({ selector: '.glightbox' });
  }

  // ===== PureCounter =====
  if (typeof PureCounter !== 'undefined') {
    new PureCounter();
  }

  // ===== Swiper.js =====
  function initSwiper() {
    if (typeof Swiper === 'undefined') return;
    document.querySelectorAll(".init-swiper").forEach(swiperElement => {
      const configElement = swiperElement.querySelector(".swiper-config");
      if (!configElement) return;
      try {
        const config = JSON.parse(configElement.innerHTML.trim());
        new Swiper(swiperElement, config);
      } catch (e) {
        console.error("Swiper config JSON invalid:", e);
      }
    });
  }
  window.addEventListener("load", initSwiper);

  // ===== FAQ Toggle =====
  const faqItems = document.querySelectorAll('.faq-item h3, .faq-item .faq-toggle');
  if (faqItems.length > 0) {
    faqItems.forEach(el => {
      el.addEventListener('click', () => {
        el.parentNode.classList.toggle('faq-active');
      });
    });
  }

  // ===== Scroll ke anchor otomatis =====
  window.addEventListener('load', () => {
    if (window.location.hash) {
      const section = document.querySelector(window.location.hash);
      if (section) {
        setTimeout(() => {
          const scrollMarginTop = getComputedStyle(section).scrollMarginTop || 0;
          window.scrollTo({
            top: section.offsetTop - parseInt(scrollMarginTop),
            behavior: 'smooth'
          });
        }, 100);
      }
    }
  });

  // ===== Scrollspy =====
  const navmenulinks = document.querySelectorAll('.navmenu a');
  function navmenuScrollspy() {
    const position = window.scrollY + 200;
    navmenulinks.forEach(link => {
      if (!link.hash) return;
      const section = document.querySelector(link.hash);
      if (!section) return;
      const inSection = position >= section.offsetTop &&
        position <= (section.offsetTop + section.offsetHeight);
      link.classList.toggle('active', inSection);
    });
  }

  window.addEventListener('load', navmenuScrollspy);
  document.addEventListener('scroll', navmenuScrollspy);

  // ===== Scroll to top =====
  const scrollTop = document.querySelector('.scroll-top');
  function toggleScrollTop() {
    if (scrollTop) {
      scrollTop.classList.toggle('active', window.scrollY > 100);
    }
  }

  if (scrollTop) {
    scrollTop.addEventListener('click', (e) => {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  }

  window.addEventListener('load', toggleScrollTop);
  document.addEventListener('scroll', toggleScrollTop);

})();

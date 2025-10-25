document.addEventListener("DOMContentLoaded", function () {
  const loader = document.getElementById("loader");
  const mainContent = document.body;
  const banner = document.querySelector(".banner-slider");
  const animadovolver = document.getElementById("animadovolver");
  const footer = document.querySelector("footer");

  // --- LOADER ---
  if (loader) {
    mainContent.style.overflow = "hidden";

    const hideLoader = () => {
      loader.classList.add("fade-out");
      loader.addEventListener(
        "transitionend",
        function () {
          loader.style.display = "none";
          mainContent.style.overflow = "auto";
        },
        { once: true }
      );
    };

    // Esperar a que cargue todo el contenido
    window.addEventListener("load", function () {
      setTimeout(hideLoader, 1200);
    });

    // Sin conexión
    window.addEventListener("offline", function () {
      loader.style.display = "flex";
      loader.classList.remove("fade-out");
      loader.innerHTML = `
        <div class="loader-logo"></div>
        <p style="color: white; font-size: 18px; margin-top: 15px;">
          Sin conexión a Internet...
        </p>
      `;
      mainContent.style.overflow = "hidden";
    });

    // Conexión restaurada
    window.addEventListener("online", function () {
      loader.innerHTML = `
        <div class="loader-logo"></div>
        <div class="loading-bar"></div>
      `;
      loader.style.display = "flex";
      loader.classList.remove("fade-out");
      setTimeout(hideLoader, 2000);
    });
  }

  // --- CATEGORÍAS ---
  document.querySelectorAll(".categories a").forEach(function (link) {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      const target = document.querySelector(link.getAttribute("href"));
      if (target) {
        target.scrollIntoView({ behavior: "smooth", block: "start" });
      }
    });
  });

  // --- BANNER SLIDER ---
  if (banner) {
    const slides = document.querySelectorAll(".banner-slide");
    let currentIndex = 0;

    function showSlide(index) {
      slides.forEach(function (slide, i) {
        slide.classList.toggle("active", i === index);
      });
    }

    function nextSlide() {
      currentIndex = (currentIndex + 1) % slides.length;
      showSlide(currentIndex);
    }

    setInterval(nextSlide, 4000);

    // Efecto de escarcha
    const sparkleContainer = document.createElement("div");
    sparkleContainer.classList.add("sparkle-container");
    banner.appendChild(sparkleContainer);

    banner.addEventListener("mousemove", function (e) {
      const rect = banner.getBoundingClientRect();
      const sparkle = document.createElement("span");
      sparkle.classList.add("sparkle");
      sparkle.style.left = e.clientX - rect.left + "px";
      sparkle.style.top = e.clientY - rect.top + "px";
      sparkleContainer.appendChild(sparkle);
      setTimeout(() => sparkle.remove(), 1000);
    });
  }

  // --- BOTÓN VOLVER ARRIBA ---
  if (animadovolver && footer) {
    window.addEventListener("scroll", function () {
      const triggerPoint = 600;
      const footerTop = footer.getBoundingClientRect().top;

      if (window.scrollY > triggerPoint && footerTop > window.innerHeight) {
        animadovolver.classList.add("show");
      } else {
        animadovolver.classList.remove("show");
      }
    });

    animadovolver.addEventListener("click", function () {
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  }
});
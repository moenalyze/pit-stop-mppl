// assets/js/main.js

// Navbar shadow saat scroll
window.addEventListener("scroll", () => {
  const header = document.querySelector("header");
  if (window.scrollY > 10) {
    header.style.boxShadow = "0 4px 20px rgba(0,0,0,0.1)";
  } else {
    header.style.boxShadow = "none";
  }
});

// Animasi scroll untuk fitur cards
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) entry.target.classList.add("animate");
  });
});

document.querySelectorAll(".feature").forEach((el) => observer.observe(el));

const sidebar = document.querySelector(".sidebar");
const hamburger = document.querySelector(".menu-icon");
const closeBtn = document.querySelector(".close-icon");

hamburger.addEventListener("click", () => {
  sidebar.classList.add("open");
});

closeBtn.addEventListener("click", () => {
  sidebar.classList.remove("open");
});

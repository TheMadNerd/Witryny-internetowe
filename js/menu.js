document.addEventListener("DOMContentLoaded", () => {
    const hamburger = document.getElementById("hamburger");
    const navLinks = document.getElementById("navLinks");
    const closeBtn = document.getElementById("closeBtn");

    if (hamburger && navLinks && closeBtn) {
        hamburger.addEventListener("click", () => {
            navLinks.classList.add("active");
        });

        closeBtn.addEventListener("click", () => {
            navLinks.classList.remove("active");
        });
    }
});

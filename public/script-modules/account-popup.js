let accountPopup;

function toggleAccountPopup() {
    accountPopup.classList.toggle("show");
}

document.addEventListener("DOMContentLoaded", function() {
    accountPopup = document.querySelector(".account-dropdown");
});
document.addEventListener("click", function(e) {
    if (!accountPopup.classList.contains("show") || e.target.closest(".account-dropdown-wrapper")) return;
    accountPopup.classList.remove("show");
});
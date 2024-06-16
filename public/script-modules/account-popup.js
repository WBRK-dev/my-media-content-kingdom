let accountPopup;

function toggleAccountPopup() {
    accountPopup.classList.toggle("show");
}

document.addEventListener("DOMContentLoaded", function() {
    accountPopup = document.querySelector(".account-dropdown");
});
document.addEventListener("click", function(e) {
    if (e.target.closest(".account-dropdown-wrapper") || !accountPopup.classList.contains("show")) return;
    accountPopup.classList.remove("show");
});
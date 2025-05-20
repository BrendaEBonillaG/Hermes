document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("confirmationModal");
    const logoutBtn = document.getElementById("logoutBtn");
    const noBtn = document.getElementById("noBtn");


    logoutBtn.addEventListener("click", function (event) {
        event.preventDefault(); // Evita la navegación
        modal.style.display = "flex";
    });


    noBtn.addEventListener("click", function () {
        modal.style.display = "none";
    });


    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
});

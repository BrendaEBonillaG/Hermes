document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("confirmationModal");
    const logoutBtn = document.getElementById("logoutBtn");
    const noBtn = document.getElementById("noBtn");

    // Mostrar el modal cuando se haga clic en "Cerrar sesión"
    logoutBtn.addEventListener("click", function (event) {
        event.preventDefault(); // Evita la navegación
        modal.style.display = "flex";
    });

    // Ocultar el modal cuando se haga clic en "No"
    noBtn.addEventListener("click", function () {
        modal.style.display = "none";
    });

    // Cerrar el modal si se hace clic fuera de él
    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
});

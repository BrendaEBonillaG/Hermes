const openBtn = document.getElementById("openWishlistModal");
const modal = document.getElementById("wishlistModal");
const closeBtn = document.getElementById("closeWishlistModal");

openBtn.onclick = () => {
    // Llama al archivo PHP para obtener listas
    fetch("PHP/ObtenerListas.php")
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById("listaExistente");
            select.innerHTML = '<option value="">Selecciona una lista</option>';

            if (Array.isArray(data)) {
                data.forEach(lista => {
                    const option = document.createElement("option");
                    option.value = lista.id;
                    option.textContent = lista.nombre;
                    select.appendChild(option);
                });
            } else {
                console.error(data.error || "Error inesperado");
            }
        })
        .catch(error => {
            console.error("Error al cargar listas:", error);
        });

    modal.style.display = "block";
};

closeBtn.onclick = () => {
    modal.style.display = "none";
};

window.onclick = (event) => {
    if (event.target === modal) {
        modal.style.display = "none";
    }
};

const openBtn = document.getElementById("openWishlistModal");
const modal = document.getElementById("wishlistModal");
const closeBtn = document.getElementById("closeWishlistModal");
const agregarBtn = document.getElementById("agregarAListaBtn");

openBtn.onclick = (event) => {
    // Captura el id del producto desde el botón que abrió el modal
    const productoId = event.target.dataset.productoId;

    // 👉 ESTO ES LO CORRECTO:
    agregarBtn.dataset.productoId = productoId;

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

agregarBtn.addEventListener("click", () => {
    const listaId = document.getElementById("listaExistente").value;
    const productoId = agregarBtn.dataset.productoId;

    if (!listaId || !productoId) {
        alert("Por favor selecciona una lista y un producto.");
        return;
    }

    fetch("PHP/AgregarProdLista.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            id_lista: listaId,
            id_producto: productoId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Producto agregado a la lista.");
            modal.style.display = "none";
        } else {
            console.error(data.error);
            alert("Error: " + data.error);
        }
    })
    .catch(error => {
        console.error("Error al enviar:", error);
    });
});

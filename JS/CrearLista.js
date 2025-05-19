const openBtn = document.getElementById("openWishlistModal");
const modal = document.getElementById("wishlistModal");
const closeBtn = document.getElementById("closeWishlistModal");
const agregarBtn = document.getElementById("agregarAListaBtn");
const formCrearLista = document.getElementById("formCrearLista");
const idProductoHidden = document.getElementById("idProductoHidden");

// Abrir modal y asignar id_producto
openBtn.onclick = (event) => {
    const productoId = event.target.dataset.productoId;
    console.log("Asignando id al botón: ", productoId);

    // ✅ Asignamos el id al botón y al campo oculto
    agregarBtn.dataset.productoId = productoId;
    idProductoHidden.value = productoId;
    console.log("Asignando id_producto:", idProductoHidden.value);

    // Obtener listas del usuario
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

// Cerrar modal
closeBtn.onclick = () => {
    modal.style.display = "none";
};

// Cerrar al hacer clic fuera del modal
window.onclick = (event) => {
    if (event.target === modal) {
        modal.style.display = "none";
    }
};

// Agregar producto a lista existente
agregarBtn.addEventListener("click", () => {
    const listaId = document.getElementById("listaExistente").value;
    const productoId = agregarBtn.dataset.productoId;
    console.log("ID de producto (click agregar):", productoId);

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

// Crear nueva lista + agregar producto
formCrearLista.addEventListener("submit", (e) => {
    e.preventDefault();

    // ✅ Reasignar el id del producto al campo oculto antes de crear FormData
    const productoId = agregarBtn.dataset.productoId;
    idProductoHidden.value = productoId;

    console.log("Asignando id_producto:", productoId);

    const formData = new FormData(formCrearLista);
    console.log("id_producto en formData:", formData.get('id_producto'));

    fetch("PHP/CrearLista.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Lista creada y producto agregado.");
            modal.style.display = "none";

            // Refrescar las listas en el select
            fetch("PHP/ObtenerListas.php")
                .then(response => response.json())
                .then(listas => {
                    const select = document.getElementById("listaExistente");
                    select.innerHTML = '<option value="">Selecciona una lista</option>';
                    if (Array.isArray(listas)) {
                        listas.forEach(lista => {
                            const option = document.createElement("option");
                            option.value = lista.id;
                            option.textContent = lista.nombre;
                            select.appendChild(option);
                        });
                    }
                });
        } else {
            alert("Error: " + data.error);
            console.error(data.error);
        }
    })
    .catch(error => {
        alert("Error al crear la lista.");
        console.error(error);
    });
});

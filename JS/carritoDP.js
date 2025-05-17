document.addEventListener("DOMContentLoaded", () => {
    let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

    const addToCartBtn = document.querySelector(".add-to-cart");
    const contenidoCarrito = document.getElementById("contenidoCarrito");
    const totalCarrito = document.getElementById("totalCarrito");
    const modalCarritoEl = document.getElementById("modalCarrito");
    const cerrarModalBtn = document.getElementById("cerrarModal"); // Botón cerrar modal

    function actualizarModal() {
        if (!contenidoCarrito || !totalCarrito) return;

        contenidoCarrito.innerHTML = '';
        let total = 0;

        if (carrito.length === 0) {
            contenidoCarrito.innerHTML = "<p>Tu carrito está vacío.</p>";
            totalCarrito.innerText = "$0.00";
            return;
        }

        carrito.forEach((producto, i) => {
            const subtotal = producto.precio * producto.cantidad;
            total += subtotal;

            contenidoCarrito.innerHTML += `
                <div class="item-carrito">
                    <div class="info-producto">
                        <p><strong>${producto.nombre}</strong></p>
                        <p>Precio: $${producto.precio.toFixed(2)}</p>
                        <p>Cantidad: ${producto.cantidad}</p>
                        <p>Subtotal: $${subtotal.toFixed(2)}</p>
                    </div>
                    <button onclick="eliminarDelCarrito(${i})" class="botonTrash btn btn-danger btn-sm">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                    <hr>
                </div>`;
        });

        totalCarrito.innerText = `$${total.toFixed(2)}`;
    }

    function mostrarModal() {
        modalCarritoEl.style.display = "block";
    }

    function ocultarModal() {
        modalCarritoEl.style.display = "none";
    }

    if (addToCartBtn) {
        addToCartBtn.addEventListener("click", (e) => {
            e.preventDefault();

            console.log("Click add-to-cart");  // Para depurar

            const nombre = document.getElementById("nombre")?.textContent.trim();
            const descripcion = document.getElementById("descripcion")?.textContent.trim();
            const vendedor = document.getElementById("vendedor")?.textContent.trim();
            const precioTexto = document.getElementById("precio")?.textContent.trim().replace("$", "").replace(",", ".");
            const cantidadInput = document.getElementById("cantidad")?.value;

            const precio = parseFloat(precioTexto);
            const cantidad = parseInt(cantidadInput);

            if (!nombre || isNaN(precio) || isNaN(cantidad) || cantidad <= 0) {
                alert("Información del producto incompleta o incorrecta.");
                return;
            }

            const indexExistente = carrito.findIndex(p => p.nombre === nombre);

            if (indexExistente !== -1) {
                carrito[indexExistente].cantidad = cantidad; // Reemplaza, no acumula
            } else {
                carrito.push({
                    nombre,
                    descripcion,
                    vendedor,
                    precio,
                    cantidad
                });
            }

            localStorage.setItem("carrito", JSON.stringify(carrito));
            actualizarModal();
            mostrarModal();
        });
    }

    function eliminarDelCarrito(index) {
        carrito.splice(index, 1);
        localStorage.setItem("carrito", JSON.stringify(carrito));
        actualizarModal();
    }

    window.eliminarDelCarrito = eliminarDelCarrito;

    const abrirCarrito = document.getElementById("abrirCarritoNavbar");
    if (abrirCarrito) {
        abrirCarrito.addEventListener("click", (e) => {
            e.preventDefault();
            mostrarModal();
        });
    }

    if (cerrarModalBtn) {
        cerrarModalBtn.addEventListener("click", () => {
            ocultarModal();
        });
    }

    window.addEventListener("click", (event) => {
        if (event.target === modalCarritoEl) {
            ocultarModal();
        }
    });

    actualizarModal();
});

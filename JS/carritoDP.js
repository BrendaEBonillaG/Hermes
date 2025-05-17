document.addEventListener("DOMContentLoaded", () => {
    let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

    const addToCartBtn = document.querySelector(".add-to-cart");
    const contenidoCarrito = document.getElementById("contenidoCarrito");
    const totalCarrito = document.getElementById("totalCarrito");
    const modalCarritoEl = document.getElementById("modalCarrito");

    function actualizarModal() {
        contenidoCarrito.innerHTML = "";
        let total = 0;

        if (carrito.length === 0) {
            contenidoCarrito.innerHTML = "<p>Tu carrito está vacío.</p>";
        } else {
            carrito.forEach((producto, index) => {
                const item = document.createElement("div");
                item.classList.add("item-carrito");
                item.innerHTML = `
          <p><strong>${producto.nombre}</strong></p>
          <p>Cantidad: ${producto.cantidad}</p>
          <p>Precio: $${producto.precio.toFixed(2)}</p>
          <p>Subtotal: $${(producto.precio * producto.cantidad).toFixed(2)}</p>
          <button class="btn btn-danger btn-sm eliminar-btn" data-index="${index}">Eliminar</button>
          <hr>
        `;
                contenidoCarrito.appendChild(item);
                total += producto.precio * producto.cantidad;
            });
        }
        totalCarrito.textContent = `$${total.toFixed(2)}`;

        document.querySelectorAll(".eliminar-btn").forEach(btn => {
            btn.addEventListener("click", (e) => {
                const index = e.target.getAttribute("data-index");
                eliminarDelCarrito(parseInt(index));
            });
        });
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

            console.log("Click add-to-cart");  // Para depurar cuántas veces se ejecuta

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

    window.addEventListener("click", (event) => {
        if (event.target === modalCarritoEl) {
            ocultarModal();
        }
    });

    actualizarModal();
});

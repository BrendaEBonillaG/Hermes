document.addEventListener("DOMContentLoaded", () => {
    let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

    const addToCartBtn = document.querySelector(".add-to-cart");
    const contenidoCarrito = document.getElementById("contenidoCarrito");
    const totalCarrito = document.getElementById("totalCarrito");
    const modalCarritoEl = document.getElementById("modalCarrito");
    const cerrarModalBtn = document.getElementById("cerrarModal");
    const finalizarPagoBtn = document.getElementById("finalizarPago");

    function actualizarModal() {
        if (!contenidoCarrito || !totalCarrito) return;

        contenidoCarrito.innerHTML = '';
        let total = 0;

        if (carrito.length === 0) {
            contenidoCarrito.innerHTML = "<p>Tu carrito está vacío.</p>";
            totalCarrito.innerText = "$0.00";
            localStorage.setItem("totalPago", "0.00");
            return;
        }

        carrito.forEach((producto, i) => {
            const subtotal = producto.precio * producto.cantidad;
            total += subtotal;

            contenidoCarrito.innerHTML += `
                <div class="item-carrito">
                    <div class="img">
                        <button onclick="eliminarDelCarrito(${i})" class="botonTrash btn btn-danger btn-sm">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                        <p>${producto.nombre}</p>
                    </div>
                    <div class="detalle-compra">
                        <p>Precio: $${producto.precio.toFixed(2)}</p>
                        <label>Cantidad:
                            <input type="number" min="1" value="${producto.cantidad}" onchange="cambiarCantidad(${i}, this.value)">
                        </label>
                        <p>Subtotal: $${subtotal.toFixed(2)}</p>
                    </div>
                </div>
                ${i < carrito.length - 1 ? '<hr>' : ''}
            `;
        });

        totalCarrito.textContent = `$${total.toFixed(2)}`;
        localStorage.setItem("totalPago", total.toFixed(2)); // ← Guarda el total a pagar


    }

    function cambiarCantidad(index, nuevaCantidad) {
        const cantidad = parseInt(nuevaCantidad);
        if (isNaN(cantidad) || cantidad < 1) return;

        carrito[index].cantidad = cantidad;
        localStorage.setItem("carrito", JSON.stringify(carrito));
        actualizarModal();
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

            const nombre = document.getElementById("nombre")?.textContent.trim();
            const descripcion = document.getElementById("descripcion")?.textContent.trim();
            const vendedor = document.getElementById("vendedor")?.textContent.trim();
            const precioTexto = document.getElementById("precio")?.textContent.trim().replace("$", "").replace(",", ".");
            const cantidadInput = document.getElementById("cantidad")?.value;
            const id_producto = document.getElementById("id_producto")?.textContent.trim();
            const precio = parseFloat(precioTexto);
            const cantidad = parseInt(cantidadInput);
console.log("ID Producto:", id_producto); 

            if (!nombre || isNaN(precio) || isNaN(cantidad) || cantidad <= 0) {
                alert("Información del producto incompleta o incorrecta.");
                return;
            }

            const indexExistente = carrito.findIndex(p => p.nombre === nombre);

            if (indexExistente !== -1) {
                carrito[indexExistente].cantidad += cantidad;
            } else {
                carrito.push({
                    nombre,
                    descripcion,
                    vendedor,
                   id_producto: parseInt(id_producto),
                    precio,
                    cantidad
                });
            }

            localStorage.setItem("carrito", JSON.stringify(carrito));
            console.log("Carrito actualizado:", carrito); 
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
    window.cambiarCantidad = cambiarCantidad;

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


    if (finalizarPagoBtn) {
        finalizarPagoBtn.addEventListener("click", () => {
     
            window.open("pago.html", "_blank");
        });
    }

    actualizarModal();
});

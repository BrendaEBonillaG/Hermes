document.addEventListener('DOMContentLoaded', function () {
    // Variables DOM
    const informacionCompra = document.getElementById('informacionCompra');
    const contenedorCompra = document.getElementById('contenedorCompra');
    const productosCompra = document.getElementById('productosCompra');
    const contenedor = document.getElementById('contenedor');
    const carrito = document.getElementById('modalCarrito');
    const numero = document.getElementById("numero");
    const header = document.querySelector("#header");
    const filtros = document.querySelector("#filtros");
    const total = document.getElementById('total');
    const body = document.querySelector("body");
    const x = document.getElementById('x');
    const abrirCarritoNavbar = document.getElementById("abrirCarritoNavbar");

    let lista = [];
    let productos = []; // Variable global para productos
    let valortotal = 0;

    window.addEventListener("scroll", function () {
        if (contenedor.getBoundingClientRect().top < 10) {
            header.classList.add("scroll");
            filtros.classList.add("scroll");
        } else {
            header.classList.remove("scroll");
            filtros.classList.remove("scroll");
        }
    });

    window.addEventListener('load', () => {
        visualizarProductos();
        if(contenedorCompra) contenedorCompra.classList.add("none");
    });

    async function visualizarProductos() {
        try {
            const respuesta = await fetch('http://localhost/Hermes/productos_enventa.php');
            productos = await respuesta.json();

            if(!contenedor) return;

            contenedor.innerHTML = "";
            for (let i = 0; i < productos.length; i++) {
                const producto = productos[i];
                const soldOut = producto.existencia <= 0;

                contenedor.innerHTML += `
                    <div class="cardProducto" onclick="irADetalle(${i})">
                        <img src="${producto.imagenes[0]}">
                        <div class="informacion">
                            <p class="precio">$${producto.precio}</p>
                            ${soldOut ? '<p class="soldOut">Sold Out</p>' : `<button onclick="event.stopPropagation(); comprar(${i})">Comprar</button>`}
                        </div>
                    </div>`;
            }
            // Guardamos productos en window para acceso global (opcional)
            window.productos = productos;

        } catch (error) {
            console.error("Error al cargar productos:", error);
        }
    }

    function comprar(indice) {
        if(!productos[indice]) return; // Evitar error si índice inválido

        let productoExistente = lista.find(p => p.nombre === productos[indice].nombre);

        if (productoExistente) {
            productoExistente.cantidad += 1;
        } else {
            lista.push({ 
                nombre: productos[indice].nombre, 
                precio: productos[indice].precio,  // Cambié de valor a precio, para consistencia
                cantidad: 1 
            });
        }

        for (let i = 0; i < productos.length; i++) {
            if (productos[i].nombre === productos[indice].nombre) {
                productos[i].existencia -= 1;
                if (productos[i].existencia === 0) {
                    visualizarProductos();
                }
                break; // Para salir del ciclo
            }
        }

        guardarAlmacenamientoLocal("productos", productos);

        if(numero) {
            numero.innerHTML = lista.length;
            numero.classList.add("diseñoNumero");
        }

        mostrarElemtrosLista();
        renderizarCarrito();

        return lista;
    }

    if (carrito) {
        carrito.addEventListener("click", function () {
            body.style.overflow = "hidden";
            if(contenedorCompra) {
                contenedorCompra.classList.remove('none');
                contenedorCompra.classList.add('contenedorCompra');
            }
            if(informacionCompra) informacionCompra.classList.add('informacionCompra');
            mostrarElemtrosLista();
            renderizarCarrito();
        });
    }

    function mostrarElemtrosLista() {
        if(!productosCompra || !total) return;

        productosCompra.innerHTML = "";
        valortotal = 0;

        for (let i = 0; i < lista.length; i++) {
            const producto = lista[i];
            const subtotal = producto.precio * producto.cantidad;
            valortotal += subtotal;

            productosCompra.innerHTML += `
                <div class="item-carrito">
                    <div class="img">
                        <button onclick="eliminar(${i})" class="botonTrash btn btn-danger btn-sm">
                            <i class="bi bi-trash-fill"></i>
                        </button>

                        <p>${producto.nombre}</p>
                    </div>
                    <div class="detalle-compra">
                        <p>Precio: $${producto.precio}</p>
                        <label>Cantidad:
                            <input type="number" min="1" value="${producto.cantidad}" onchange="cambiarCantidad(${i}, this.value)">
                        </label>
                        <p>Subtotal: $${subtotal}</p>
                    </div>
                </div>`;
        }

        total.innerHTML = `<p>Valor Total</p> <p><span>$${valortotal}</span></p>`;
    }

    function cambiarCantidad(indice, nuevaCantidad) {
        nuevaCantidad = parseInt(nuevaCantidad);
        if (nuevaCantidad < 1) return; // evitar cantidades inválidas

        lista[indice].cantidad = nuevaCantidad;
        mostrarElemtrosLista();
        renderizarCarrito();
    }

    function eliminar(indice) {
        if (!lista[indice]) return; // evitar índice inválido

        const productoEliminado = lista[indice];

        for (let i = 0; i < productos.length; i++) {
            if (productos[i].nombre === productoEliminado.nombre) {
                productos[i].existencia += productoEliminado.cantidad;
                break;
            }
        }

        lista.splice(indice, 1);

        guardarAlmacenamientoLocal("productos", productos);

        if(numero) {
            numero.innerHTML = lista.length;
            if (lista.length === 0) {
                numero.classList.remove("diseñoNumero");
            }
        }

        visualizarProductos();
        mostrarElemtrosLista();
        renderizarCarrito();
    }

    if (x) {
        x.addEventListener("click", function () {
            body.style.overflow = "auto";
            if(contenedorCompra) {
                contenedorCompra.classList.add('none');
                contenedorCompra.classList.remove('contenedorCompra');
            }
            if(informacionCompra) informacionCompra.classList.remove('informacionCompra');
        });
    }

    function irADetalle(indice) {
        if (!window.productos || !window.productos[indice]) return;
        const producto = window.productos[indice];
        localStorage.setItem("productoSeleccionado", JSON.stringify(producto));
        window.location.href = "Producto.php";
    }

    if (abrirCarritoNavbar) {
        abrirCarritoNavbar.addEventListener("click", function (e) {
            e.preventDefault();
            carrito.click();
        });
    }

    // Exponer funciones globales para uso en HTML
    window.comprar = comprar;
    window.cambiarCantidad = cambiarCantidad;
    window.eliminar = eliminar;
    window.irADetalle = irADetalle;

    function renderizarCarrito() {
        const contenidoCarrito = document.getElementById('contenidoCarrito');
        const totalCarrito = document.getElementById('totalCarrito');

        if (!contenidoCarrito || !totalCarrito) return;

        contenidoCarrito.innerHTML = '';
        let total = 0;

        lista.forEach((producto, i) => {
            const subtotal = producto.precio * producto.cantidad;
            total += subtotal;

            contenidoCarrito.innerHTML += `
            <div class="item-carrito">
                <div class="info-producto">
                    <p><strong>${producto.nombre}</strong></p>
                    <p>Precio: $${producto.precio}</p>
                    <p>Cantidad: ${producto.cantidad}</p>
                    <p>Subtotal: $${subtotal}</p>
                </div>
                <button onclick="eliminar(${i})" class="botonTrash btn btn-danger btn-sm">
                    <i class="bi bi-trash-fill"></i>
                </button>
            </div>`;
        });

        totalCarrito.innerText = `$${total.toFixed(2)}`;
    }

    function obtenerAlmacenamientoLocal(clave) {
        const datos = localStorage.getItem(clave);
        return datos ? JSON.parse(datos) : null;
    }

    function guardarAlmacenamientoLocal(clave, datos) {
        localStorage.setItem(clave, JSON.stringify(datos));
    }
});

document.addEventListener('DOMContentLoaded', function () {
    function updatePrecioValue() {
        const precio = document.getElementById("precio").value;
        document.getElementById("precioValor").textContent = "$" + precio;
    }

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
        contenedorCompra.classList.add("none");
    });

    async function visualizarProductos() {
        try {
            const respuesta = await fetch('http://localhost/Hermes/productos_enventa.php');
            const productos = await respuesta.json();

            contenedor.innerHTML = "";
            for (let i = 0; i < productos.length; i++) {
                const producto = productos[i];
                const soldOut = producto.existencia <= 0;

                contenedor.innerHTML += `
                    <div class="cardProducto" onclick="irADetalle(${i})">
                        <img src="${producto.imagenes[0]}">
                        <div class="informacion">
                            <p class="precio">$${producto.precio}</p>
                            ${soldOut ? '<p class="soldOut">Sold Out</p>' : '<button onclick="event.stopPropagation(); comprar(' + i + ')">Comprar</button>'}
                        </div>
                    </div>`;
            }

            window.productos = productos;

        } catch (error) {
            console.error("Error al cargar productos:", error);
        }
    }

    function comprar(indice) {
        let productoExistente = lista.find(p => p.nombre === productos[indice].nombre);

        if (productoExistente) {
            productoExistente.cantidad += 1;
        } else {
            lista.push({ nombre: productos[indice].nombre, precio: productos[indice].valor, cantidad: 1 });
        }

        let van = true;
        let i = 0;
        while (van === true) {
            if (productos[i].nombre === productos[indice].nombre) {
                productos[i].existencia -= 1;
                if (productos[i].existencia === 0) {
                    visualizarProductos();
                }
                van = false;
            }
            guardarAlmacenamientoLocal("productos", productos);
            i += 1;
        }

        numero.innerHTML = lista.length;
        numero.classList.add("diseñoNumero");
        return lista;
    }

    if (carrito) {
        carrito.addEventListener("click", function () {
            body.style.overflow = "hidden";
            contenedorCompra.classList.remove('none');
            contenedorCompra.classList.add('contenedorCompra');
            informacionCompra.classList.add('informacionCompra');
            mostrarElemtrosLista();
        });
    }

    function mostrarElemtrosLista() {
        productosCompra.innerHTML = "";
        valortotal = 0;

        for (let i = 0; i < lista.length; i++) {
            const producto = lista[i];
            const subtotal = parseInt(producto.precio) * producto.cantidad;
            valortotal += subtotal;

            productosCompra.innerHTML += `
                <div class="item-carrito">
                    <div class="img">
                        <button onclick="eliminar(${i})" class="botonTrash">
                            <img src="/img/trash.png">
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
        lista[indice].cantidad = parseInt(nuevaCantidad);
        mostrarElemtrosLista();
    }

    function eliminar(indice) {
        let van = true;
        let i = 0;
        while (van === true) {
            if (productos[i].nombre === lista[indice].nombre) {
                productos[i].existencia += 1;
                lista.splice(indice, 1);
                van = false;
            }
            i += 1;
        }

        guardarAlmacenamientoLocal("productos", productos);

        numero.innerHTML = lista.length;
        if (lista.length === 0) {
            numero.classList.remove("diseñoNumero");
        }
        visualizarProductos();
        mostrarElemtrosLista();
    }

    if (x) {
        x.addEventListener("click", function () {
            body.style.overflow = "auto";
            contenedorCompra.classList.add('none');
            contenedorCompra.classList.remove('contenedorCompra');
            informacionCompra.classList.remove('informacionCompra');
        });
    }

    function irADetalle(indice) {
        const producto = window.productos[indice];
        localStorage.setItem("productoSeleccionado", JSON.stringify(producto));
        window.location.href = "producto.html";
    }

    if (abrirCarritoNavbar) {
        abrirCarritoNavbar.addEventListener("click", function (e) {
            e.preventDefault();
            carrito.click();
        });
    }

    // Exponer funciones que usas en HTML
    window.comprar = comprar;
    window.cambiarCantidad = cambiarCantidad;
    window.eliminar = eliminar;
    window.irADetalle = irADetalle;
});

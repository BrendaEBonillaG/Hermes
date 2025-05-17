function updatePrecioValue() {
    const precio = document.getElementById("precio").value;
    document.getElementById("precioValor").textContent = "$" + precio;

    // Filtrar productos por precio (sin recargar la página)
    visualizarProductos("", precio);
}
// Funciones para almacenar y traer los datos que se almacenan
//function guardarAlmacenamientoLocal(llave, valor_a_guardar) {
 //   localStorage.setItem(llave, JSON.stringify(valor_a_guardar))
//}
//function obtenerAlmacenamientoLocal(llave) {
//    const datos = JSON.parse(localStorage.getItem(llave))
 //   return datos
//}
//let productos = obtenerAlmacenamientoLocal('productos') || [];

//if (productos.length === 0) {
//    productos = [
 //       { nombre: "Producto A", valor: 150, existencia: 5, urlImagen: "https://i.pinimg.com/474x/d0/84/9e/d0849ef8583ee3d834542b5d02832bab.jpg" },
 //       { nombre: "Producto B", valor: 200, existencia: 3, urlImagen: "https://i.pinimg.com/474x/a6/4c/23/a64c2327f410f1f91abff4db7ef4e555.jpg" },
//       { nombre: "Producto C", valor: 150, existencia: 5, urlImagen: "https://i.pinimg.com/474x/d0/84/9e/d0849ef8583ee3d834542b5d02832bab.jpg" },
 //       { nombre: "Producto D", valor: 200, existencia: 3, urlImagen: "https://i.pinimg.com/474x/a6/4c/23/a64c2327f410f1f91abff4db7ef4e555.jpg" },
 //       { nombre: "Producto E", valor: 150, existencia: 5, urlImagen: "https://i.pinimg.com/474x/d0/84/9e/d0849ef8583ee3d834542b5d02832bab.jpg" },
//        { nombre: "Producto F", valor: 200, existencia: 3, urlImagen: "https://i.pinimg.com/474x/a6/4c/23/a64c2327f410f1f91abff4db7ef4e555.jpg" },
 //       { nombre: "Producto G", valor: 150, existencia: 5, urlImagen: "https://i.pinimg.com/474x/d0/84/9e/d0849ef8583ee3d834542b5d02832bab.jpg" },
 //       { nombre: "Producto H", valor: 200, existencia: 3, urlImagen: "https://i.pinimg.com/474x/a6/4c/23/a64c2327f410f1f91abff4db7ef4e555.jpg" },
 //       { nombre: "Producto I", valor: 150, existencia: 5, urlImagen: "https://i.pinimg.com/474x/d0/84/9e/d0849ef8583ee3d834542b5d02832bab.jpg" },
//        { nombre: "Producto J", valor: 200, existencia: 3, urlImagen: "https://i.pinimg.com/474x/a6/4c/23/a64c2327f410f1f91abff4db7ef4e555.jpg" },
 //   ];
 //   guardarAlmacenamientoLocal("productos", productos);
//}
// Variables que traemos de nuestro html
const informacionCompra = document.getElementById('informacionCompra');
const contenedorCompra = document.getElementById('contenedorCompra');
const productosCompra = document.getElementById('productosCompra');
const contenedor = document.getElementById('contenedor');
const carrito = document.getElementById('carrito');
const numero = document.getElementById("numero");
const header = document.querySelector("#header");
const filtros = document.querySelector("#filtros");
const total = document.getElementById('total');
const body = document.querySelector("body");
const x = document.getElementById('x')


let lista = []
let valortotal = 0

// Scroll de nuestra pagina
window.addEventListener("scroll", function () {
    if (contenedor.getBoundingClientRect().top < 10) {
        header.classList.add("scroll")
        filtros.classList.add("scroll")
    }
    else {
        header.classList.remove("scroll")
        filtros.classList.remove("scroll")
    }
})


window.addEventListener('load', () => {
    visualizarProductos();
    contenedorCompra.classList.add("none")
})
function aplicarFiltros() {
    const precio = document.getElementById("precio").value;
    const venta = document.querySelector('input[name="venta"]:checked')?.value || "";
    const calificacion = document.getElementById("calificacion").value;

    // Llamar a visualizarProductos con los filtros aplicados
    visualizarProductos("", precio, venta, calificacion);
}


async function visualizarProductos(busqueda = "", usarFiltros = true) {
    try {
        let url = "http://localhost/Hermes/PHP/BuscarProductos.php";

        if (usarFiltros && busqueda) {
            // Si se están usando los filtros (por ejemplo, en el dashboard)
            url += `?buscar=${encodeURIComponent(busqueda)}`;
            const precio = document.getElementById("precio") ? document.getElementById("precio").value : "";
            if (precio) {
                url += `&precio=${encodeURIComponent(precio)}`;
            }
        }

        const respuesta = await fetch(url);
        const productos = await respuesta.json();

        // Limpiar los productos mostrados
        const contenedor = document.getElementById("contenedor");
        contenedor.innerHTML = "";

        // Mostrar los productos
        for (let i = 0; i < productos.length; i++) {
            const producto = productos[i];

            contenedor.innerHTML += `
                <div class="cardProducto" onclick="irADetalle(${i})">
                    <img src="${producto.imagenes[0]}">
                    <div class="informacion">
                        <p class="precio">$${producto.precio}</p>
                        <p class="nombre">${producto.nombre}</p>
                      
                    </div>
                </div>`;
        }

        window.productos = productos;

    } catch (error) {
        console.error("Error al cargar productos:", error);
    }
}






function comprar(indice) {
    lista.push({ nombre: productos[indice].nombre, precio: productos[indice].valor })

    let van = true
    let i = 0
    while (van == true) {
        if (productos[i].nombre == productos[indice].nombre) {
            productos[i].existencia -= 1
            if (productos[i].existencia == 0) {
                visualizarProductos()
            }
            van = false
        }
        guardarAlmacenamientoLocal("productos", productos)
        i += 1
    }
    numero.innerHTML = lista.length
    numero.classList.add("diseñoNumero")
    return lista
}

carrito.addEventListener("click", function(){
    body.style.overflow = "hidden"
    contenedorCompra.classList.remove('none')
    contenedorCompra.classList.add('contenedorCompra')
    informacionCompra.classList.add('informacionCompra')
    mostrarElemtrosLista()
})

function mostrarElemtrosLista() {
    productosCompra.innerHTML = ""
    valortotal = 0
    for (let i = 0; i < lista.length; i++){
        productosCompra.innerHTML += `<div><div class="img"><button onclick=eliminar(${i}) class="botonTrash"><img src="/img/trash.png"></button><p>${lista[i].nombre}</p></div><p> $${lista[i].precio}</p></div>`
        valortotal += parseInt(lista[i].precio)
    }
    total.innerHTML = `<p>Valor Total</p> <p><span>$${valortotal}</span></p>`
}

function eliminar(indice){
    let van = true
    let i = 0
    while (van == true) {
        if (productos[i].nombre == lista[indice].nombre) {
            productos[i].existencia += 1
            lista.splice(indice, 1)
            van = false
        }
        i += 1
    }
    guardarAlmacenamientoLocal("productos", productos)

    numero.innerHTML = lista.length
    if (lista.length == 0){
        numero.classList.remove("diseñoNumero")
    }
    visualizarProductos()
    mostrarElemtrosLista()
}

x.addEventListener("click", function(){
    body.style.overflow = "auto"
    contenedorCompra.classList.add('none')
    contenedorCompra.classList.remove('contenedorCompra')
    informacionCompra.classList.remove('informacionCompra')
})

function irADetalle(indice) {
    const producto = window.productos[indice];
    localStorage.setItem("productoSeleccionado", JSON.stringify(producto));
    window.location.href = "producto.html";
}

function irADetalle2(productId) {
    console.log("Product ID: " + productId);  // Muestra el ID en la consola
    localStorage.setItem("producto_" + producto.id, JSON.stringify(producto));
    window.location.href = "Producto.html?id=" + encodeURIComponent(producto.id);
    // Redirige a Producto.html con el ID del producto en la URL
    window.location.href = "Producto.html?id=" + encodeURIComponent(productId);
}





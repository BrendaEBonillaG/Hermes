const modalCotizacion = document.getElementById('modalCotizacion');
const listaProductos = document.getElementById('listaProductos');
const btnGuardarCotizacion = document.getElementById('btnGuardarCotizacion');
const cantidadInput = document.getElementById('cantidadCotizacion');
const precioInput = document.getElementById('precioCotizacion');
const formCotizacion = document.getElementById('formCotizacion');
const closeBtn = modalCotizacion.querySelector('.close-cotizacion');

let productos = [];

function cargarProductosDesdeServidor() {
  fetch('PHP/ObtenerProductos.php')
    .then(response => response.json())
    .then(data => {
      productos = data;
      renderizarProductos();
    })
    .catch(() => {
      listaProductos.innerHTML = '<p>No se pudo cargar productos.</p>';
    });
}

function renderizarProductos() {
  if (productos.length === 0) {
    listaProductos.innerHTML = '<p>No hay productos disponibles.</p>';
    return;
  }
  listaProductos.innerHTML = '';
  productos.forEach(prod => {
    const div = document.createElement('div');
    div.classList.add('form-check');
    div.innerHTML = `
      <input class="form-check-input" type="radio" name="producto" id="prod-${prod.id}" value="${prod.id}">
      <label class="form-check-label" for="prod-${prod.id}">
        ${prod.nombre} (Cantidad: ${prod.cantidad}, Precio: $${parseFloat(prod.precio).toFixed(2)})
      </label>
    `;
    listaProductos.appendChild(div);
  });
  const radios = listaProductos.querySelectorAll('input[name="producto"]');
  radios.forEach(radio => {
    radio.addEventListener('change', onProductoSeleccionado);
  });
}

function onProductoSeleccionado(e) {
  const idSeleccionado = parseInt(e.target.value);
  const producto = productos.find(p => p.id === idSeleccionado);
  if (producto) {
    cantidadInput.disabled = false;
    precioInput.disabled = false;
    btnGuardarCotizacion.disabled = false;

    cantidadInput.value = producto.cantidad;
    precioInput.value = parseFloat(producto.precio).toFixed(2);
  }
}

function resetModal() {
  cantidadInput.value = 1;
  precioInput.value = 0;
  cantidadInput.disabled = true;
  precioInput.disabled = true;
  btnGuardarCotizacion.disabled = true;

  const radios = listaProductos.querySelectorAll('input[name="producto"]');
  radios.forEach(radio => radio.checked = false);
}

function abrirModal() {
  modalCotizacion.style.display = 'flex';
  resetModal();
  cargarProductosDesdeServidor();
}

closeBtn.onclick = () => {
  modalCotizacion.style.display = 'none';
}

window.onclick = (event) => {
  if (event.target === modalCotizacion) {
    modalCotizacion.style.display = 'none';
  }
}

formCotizacion.onsubmit = (e) => {
  e.preventDefault();

  const seleccionado = listaProductos.querySelector('input[name="producto"]:checked');
  if (!seleccionado) {
    alert("Debes seleccionar un producto.");
    return;
  }

  const productoId = parseInt(seleccionado.value);
  const cantidad = parseInt(cantidadInput.value);
  const precio = parseFloat(precioInput.value);

  if (cantidad <= 0 || precio <= 0) {
    alert("Cantidad y precio deben ser mayores a cero.");
    return;
  }

  const data = {
    id_producto: productoId,
    cantidad: cantidad,
    precio: precio
  };

  const idChatActual = window.idChatActivo;

if (!idChatActual) {
  alert('No se ha seleccionado ningún chat.');
  return;
}

console.log({
  id_producto: productoId,
  cantidad: cantidad,
  precio: precio,
  id_chat: idChatActual
});

fetch('PHP/guardarCotizacion.php', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    id_producto: productoId,
    cantidad: cantidad,
    precio: precio,
    id_chat: idChatActual
  })
})

.then(response => response.text()) 
.then(text => {
  console.log("Respuesta del servidor (texto crudo):", text);
  try {
    const json = JSON.parse(text);
    if (json.success) {
      alert('Cotización guardada correctamente.');
    } else {
      alert('Error al guardar cotización: ' + json.message);
    }
  } catch (e) {
    console.error("Error al parsear JSON:", e);
    alert('Error inesperado en la respuesta del servidor.');
  }
})
.catch(error => {
  console.error('Error en fetch:', error);
  alert('Error en la conexión con el servidor.');
});

}

const btnOpcionesVendedor = document.getElementById('btnOpcionesVendedor');
if (btnOpcionesVendedor) {
  btnOpcionesVendedor.addEventListener('click', abrirModal);
}

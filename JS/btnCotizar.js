

  const modalCotizacion = document.getElementById('modalCotizacion');
  const listaProductos = document.getElementById('listaProductos');
  const btnGuardarCotizacion = document.getElementById('btnGuardarCotizacion');
  const cantidadInput = document.getElementById('cantidadCotizacion');
  const precioInput = document.getElementById('precioCotizacion');
  const formCotizacion = document.getElementById('formCotizacion');
  const closeBtn = modalCotizacion.querySelector('.close-cotizacion');

  // Ejemplo productos, reemplaza con tu PHP o AJAX que cargue productos reales
  const productos = [
    { id: 1, nombre: "Producto A", cantidad: 5, precio: 10.50 },
    { id: 2, nombre: "Producto B", cantidad: 3, precio: 20.00 }
  ];

  // Función para renderizar productos con radio buttons
  function cargarProductos() {
    if(productos.length === 0){
      listaProductos.innerHTML = '<p>No hay productos disponibles.</p>';
      return;
    }
    listaProductos.innerHTML = ''; // limpiar

    productos.forEach(prod => {
      const div = document.createElement('div');
      div.classList.add('form-check');
      div.innerHTML = `
        <input class="form-check-input" type="radio" name="producto" id="prod-${prod.id}" value="${prod.id}">
        <label class="form-check-label" for="prod-${prod.id}">
          ${prod.nombre} (Cantidad: ${prod.cantidad}, Precio: $${prod.precio.toFixed(2)})
        </label>
      `;
      listaProductos.appendChild(div);
    });

    // Asignar evento para cuando se seleccione un producto
    const radios = listaProductos.querySelectorAll('input[name="producto"]');
    radios.forEach(radio => {
      radio.addEventListener('change', onProductoSeleccionado);
    });
  }

  // Cuando se selecciona un producto habilitamos inputs y boton
  function onProductoSeleccionado(e) {
    const idSeleccionado = parseInt(e.target.value);
    const producto = productos.find(p => p.id === idSeleccionado);
    if(producto) {
      cantidadInput.disabled = false;
      precioInput.disabled = false;
      btnGuardarCotizacion.disabled = false;

      cantidadInput.value = producto.cantidad;
      precioInput.value = producto.precio.toFixed(2);
    }
  }

  // Resetea el formulario y deshabilita inputs y botón
  function resetModal() {
    cantidadInput.value = 1;
    precioInput.value = 0;
    cantidadInput.disabled = true;
    precioInput.disabled = true;
    btnGuardarCotizacion.disabled = true;

    // Desmarcar radios
    const radios = listaProductos.querySelectorAll('input[name="producto"]');
    radios.forEach(radio => radio.checked = false);
  }

  // Mostrar modal, cargar productos y resetear formulario
  function abrirModal() {
   modalCotizacion.style.display = 'flex';

    cargarProductos();
    resetModal();
  }

  // Cerrar modal
  closeBtn.onclick = () => {
    modalCotizacion.style.display = 'none';
  }

  // Cerrar modal si se hace clic fuera del contenido
  window.onclick = (event) => {
    if(event.target === modalCotizacion) {
      modalCotizacion.style.display = 'none';
    }
  }

  // Manejar submit
  formCotizacion.onsubmit = (e) => {
    e.preventDefault();

    const seleccionado = listaProductos.querySelector('input[name="producto"]:checked');
    if(!seleccionado){
      alert("Debes seleccionar un producto.");
      return;
    }

    const productoId = parseInt(seleccionado.value);
    const cantidad = parseInt(cantidadInput.value);
    const precio = parseFloat(precioInput.value);

    // Aquí envías al backend o haces AJAX para guardar la cotización
    console.log("Guardar cotización:", { productoId, cantidad, precio });

    // Cerrar modal y resetear
    modalCotizacion.style.display = 'none';
    resetModal();
  };

  // Asociar tu botón PHP para abrir modal (asegúrate que el botón tenga id btnOpcionesVendedor)
  const btnOpcionesVendedor = document.getElementById('btnOpcionesVendedor');
  if(btnOpcionesVendedor){
    btnOpcionesVendedor.addEventListener('click', abrirModal);
  }

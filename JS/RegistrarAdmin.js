document.getElementById('registerForm').addEventListener('submit', function (e) {
    e.preventDefault(); // Evita que el formulario se envíe de manera tradicional

    // Crear un objeto FormData con los datos del formulario
    const formData = new FormData(this);

    // Enviar los datos mediante AJAX
    fetch(this.action, {
        method: this.method,
        body: formData
    })
    .then(response => response.text()) // Obtener la respuesta del servidor como texto
    .then(data => {
        // Mostrar el mensaje en el modal
        document.getElementById('mensajeModal').innerText = data;
        document.getElementById('modalMensaje').style.display = "block"; // Mostrar el modal
    })
    .catch(error => {
        // Mostrar un mensaje de error en el modal
        document.getElementById('mensajeModal').innerText = "Error al registrar el administrador.";
        document.getElementById('modalMensaje').style.display = "block"; // Mostrar el modal
    });
});

// Cerrar el modal al hacer clic en la "X"
document.getElementById('cerrarModal').addEventListener('click', function () {
    document.getElementById('modalMensaje').style.display = "none"; // Ocultar el modal
});

// Cerrar el modal al hacer clic fuera del contenido
window.addEventListener('click', function (event) {
    const modal = document.getElementById('modalMensaje');
    if (event.target === modal) {
        modal.style.display = "none"; // Ocultar el modal
    }
});
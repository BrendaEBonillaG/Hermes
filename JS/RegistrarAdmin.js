document.getElementById('registerForm').addEventListener('submit', function (e) {
    e.preventDefault(); 


    const formData = new FormData(this);


    fetch(this.action, {
        method: this.method,
        body: formData
    })
    .then(response => response.text()) 
    .then(data => {
      
        document.getElementById('mensajeModal').innerText = data;
        document.getElementById('modalMensaje').style.display = "block"; 
    })
    .catch(error => {
    
        document.getElementById('mensajeModal').innerText = "Error al registrar el administrador.";
        document.getElementById('modalMensaje').style.display = "block";
    });
});


document.getElementById('cerrarModal').addEventListener('click', function () {
    document.getElementById('modalMensaje').style.display = "none"; 
});


window.addEventListener('click', function (event) {
    const modal = document.getElementById('modalMensaje');
    if (event.target === modal) {
        modal.style.display = "none"; 
    }
});
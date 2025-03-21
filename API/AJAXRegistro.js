document.getElementById('registerForm').addEventListener('submit', function(event) {
    event.preventDefault();  // Evitar la recarga de la página al enviar el formulario

    // Recoger los datos del formulario
    let formData = new FormData();
    formData.append('correo', document.getElementById('correo').value);
    formData.append('nombreUsu', document.getElementById('nombreUsu').value);
    formData.append('contrasena', document.getElementById('contrasena').value);
    formData.append('rol', document.getElementById('rol').value);
    formData.append('nombres', document.getElementById('nombres').value);
    formData.append('apePa', document.getElementById('apePa').value);
    formData.append('apeMa', document.getElementById('apeMa').value);
    formData.append('fechaNacim', document.getElementById('fechaNacim').value);
    formData.append('sexo', document.getElementById('sexo').value);
    formData.append('privacidad', document.getElementById('privacidad').value);

    // Validación de correo
    const email = document.getElementById('correo').value;
    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (!emailRegex.test(email)) {
        alert('Por favor ingrese un correo electrónico válido');
        return;  // Detener el envío del formulario si el correo no es válido
    }

    // Validación de la contraseña
    const password = document.getElementById('contrasena').value;
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    if (!passwordRegex.test(password)) {
        alert('La contraseña debe tener al menos 8 caracteres, 1 mayúscula, 1 minúscula, 1 número y 1 carácter especial');
        return;  // Detener el envío del formulario si la contraseña no es válida
    }

    // Manejar la carga de la imagen si existe
    let imageFile = document.getElementById('imageUpload').files[0];
    if (imageFile) {
        formData.append('foto', imageFile);
        formData.append('fotoNombre', imageFile.name);
    }

    // Enviar los datos al servidor
    fetch('API/API.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        // Verificar si la respuesta es exitosa
        if (!response.ok) {
            throw new Error('Error en la solicitud');
        }
        return response.json();  // Convertir la respuesta a JSON
    })
    .then(data => {
        console.log(data);
        if (data.status === 'success') {
            alert(data.message);  // Mostrar el mensaje de éxito
            window.location.href = 'Dashboard.html'; // Redirigir a otra página
            // O limpiar el formulario
            document.getElementById('registerForm').reset();
        } else {
            alert(data.message);  // Mostrar el mensaje de error
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Hubo un error al registrar el usuario');
    });
});

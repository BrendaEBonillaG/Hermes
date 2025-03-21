// Variable Declaration
const registerBtn = document.querySelector("#register");
const registerForm = document.querySelector(".register-form");

// Expresiones regulares para validaciones
const usernameRegex = /^[a-zA-Z0-9_]+$/; // Permite letras, números y guiones bajos (_)
const passwordRegex = /^(?=.*[A-ZÑ])(?=.*[a-zñ])(?=.*\d)(?=.*[¡”#$%&'()*+,-./:;<=>?@[\\\]_{}|~]).{8,}$/;
const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

document.getElementById("imageUpload").addEventListener("change", function() {
    let fileName = this.files.length > 0 ? this.files[0].name : "Ningún archivo seleccionado";
    document.getElementById("file-name").textContent = fileName;
});

// Elimina la funcionalidad de alternar entre login y registro, ya que no la necesitas
registerBtn.addEventListener('click', () => {
    // Ya no es necesario alternar la visibilidad
    // Aquí puedes agregar alguna acción si es necesario al hacer clic en el botón
    console.log("Botón de registro clickeado");
});

document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.querySelector('.register-form');

    // Validación del formulario de registro
    registerForm.addEventListener('submit', function(event) {
        const username = registerForm.querySelector('input[type="text"]').value;
        const password = registerForm.querySelector('input[type="password"]').value;
        const email = registerForm.querySelector('input[type="email"]').value;

        if (!username || !password || !email) {
            event.preventDefault(); // Evita el envío del formulario
            alert('Por favor, complete todos los campos.');
        } else if (!usernameRegex.test(username)) {
            event.preventDefault();
            alert('El nombre de usuario solo puede contener letras, números y guiones bajos (_).');
        } else if (!passwordRegex.test(password)) {
            event.preventDefault();
            alert('La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula (incluyendo "ñ"), un número y un carácter especial.');
        } else if (!emailPattern.test(email)) {
            event.preventDefault();
            alert('Por favor, ingrese un correo electrónico válido.');
        }
    });
});

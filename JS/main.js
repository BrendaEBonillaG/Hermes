// Variable Declaration
const registerBtn = document.querySelector("#register");
const loginBtn = document.querySelector("#login");
const registerForm = document.querySelector(".register-form");
const loginForm = document.querySelector(".login-form");

// Expresiones regulares para validaciones
const usernameRegex = /^[a-zA-Z0-9_]+$/; // Permite letras, números y guiones bajos (_)
const passwordRegex = /^(?=.*[A-ZÑ])(?=.*[a-zñ])(?=.*\d)(?=.*[¡”#$%&'()*+,-./:;<=>?@[\\\]_{}|~]).{8,}$/;
const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

// Inicializa las posiciones de los formularios
document.addEventListener("DOMContentLoaded", () => {
    registerForm.style.left = "100%";
    registerForm.style.opacity = "0";
    loginForm.style.left = "50%";
    loginForm.style.opacity = "1";
});

// Evento para mostrar el formulario de registro y ocultar el de login
registerBtn.addEventListener("click", () => {
    registerForm.style.left = "50%";
    registerForm.style.opacity = "1";
    loginForm.style.left = "-100%";
    loginForm.style.opacity = "0";
});

// Evento para mostrar el formulario de login y ocultar el de registro
loginBtn.addEventListener("click", () => {
    loginForm.style.left = "50%";
    loginForm.style.opacity = "1";
    registerForm.style.left = "100%";
    registerForm.style.opacity = "0";
});

// Muestra el nombre del archivo seleccionado para la imagen de perfil
document.getElementById("imageUpload").addEventListener("change", function () {
    let fileName = this.files.length > 0 ? this.files[0].name : "Ningún archivo seleccionado";
    document.getElementById("file-name").textContent = fileName;
});

// Evento para mostrar el formulario de registro y cambiar color de botón
registerBtn.addEventListener("click", () => {
    registerForm.style.left = "50%";
    registerForm.style.opacity = "1";
    loginForm.style.left = "-100%";
    loginForm.style.opacity = "0";

    // Cambio de color en los botones
    registerBtn.style.backgroundColor = "#ffbd59";
    loginBtn.style.backgroundColor = "rgba(255, 255, 255, 0.2)";
});

// Evento para mostrar el formulario de login y cambiar color de botón
loginBtn.addEventListener("click", () => {
    loginForm.style.left = "50%";
    loginForm.style.opacity = "1";
    registerForm.style.left = "100%";
    registerForm.style.opacity = "0";

    // Cambio de color en los botones
    loginBtn.style.backgroundColor = "#ffbd59";
    registerBtn.style.backgroundColor = "rgba(255, 255, 255, 0.2)";
});


// Validación del formulario de registro
document.addEventListener("DOMContentLoaded", function () {
    const registerForm = document.querySelector(".register-form");

    registerForm.addEventListener("submit", function (event) {
        const username = registerForm.querySelector("#nombreUsu").value;
        const password = registerForm.querySelector("#contrasena").value;
        const email = registerForm.querySelector("#correo").value;

        if (!username || !password || !email) {
            event.preventDefault();
            alert("Por favor, complete todos los campos.");
        } else if (!usernameRegex.test(username)) {
            event.preventDefault();
            alert("El nombre de usuario solo puede contener letras, números y guiones bajos (_).");
        } else if (!passwordRegex.test(password)) {
            event.preventDefault();
            alert("La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula (incluyendo 'ñ'), un número y un carácter especial.");
        } else if (!emailPattern.test(email)) {
            event.preventDefault();
            alert("Por favor, ingrese un correo electrónico válido.");
        }
    });
});

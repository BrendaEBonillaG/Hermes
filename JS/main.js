console.log("si entro"); // DEBUG
document.addEventListener("DOMContentLoaded", function () {
    // Variables de elementos del DOM
    const registrarForm = document.querySelector(".register-form");
    const registerForm = document.querySelector(".register-form");
    const loginForm = document.querySelector(".login-form");
    const registerBtn = document.querySelector("#register");
    const loginBtn = document.querySelector("#login");

    // Expresiones regulares para validaciones
    const usernameRegex = /^[a-zA-Z0-9_]+$/; // Permite letras, números y guiones bajos (_)
    const passwordRegex = /^(?=.*[A-ZÑ])(?=.*[a-zñ])(?=.*\d)(?=.*[¡”#$%&'()*+,-./:;<=>?@[\\\]_{}|~]).{8,}$/;
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // Posiciones iniciales
    registerForm.style.left = "100%";
    registerForm.style.opacity = "0";
    loginForm.style.left = "50%";
    loginForm.style.opacity = "1";

    // Mostrar formulario de registro
    registerBtn.addEventListener("click", () => {
        registerForm.style.left = "50%";
        registerForm.style.opacity = "1";
        loginForm.style.left = "-100%";
        loginForm.style.opacity = "0";
        registerBtn.style.backgroundColor = "#ffbd59";
        loginBtn.style.backgroundColor = "rgba(255, 255, 255, 0.2)";
    });

    // Mostrar formulario de login
    loginBtn.addEventListener("click", () => {
        loginForm.style.left = "50%";
        loginForm.style.opacity = "1";
        registerForm.style.left = "100%";
        registerForm.style.opacity = "0";
        loginBtn.style.backgroundColor = "#ffbd59";
        registerBtn.style.backgroundColor = "rgba(255, 255, 255, 0.2)";
    });

    // Mostrar nombre del archivo cargado
    document.getElementById("imageUpload").addEventListener("change", function () {
        let fileName = this.files.length > 0 ? this.files[0].name : "Ningún archivo seleccionado";
        document.getElementById("file-name").textContent = fileName;
    });

    // Validación y envío del formulario de registro
    registrarForm.addEventListener("submit", async function (event) {
        event.preventDefault();
        console.log("Interceptado el submit correctamente"); // DEBUG
        const username = registrarForm.querySelector('#nombreUsu').value;
        const password = registrarForm.querySelector('#contrasena').value;
        const email = registrarForm.querySelector('#correo').value;
        const fullName = registrarForm.querySelector('#nombres').value;
        const lastName = registrarForm.querySelector('#apePa').value;
        const maternalLastName = registrarForm.querySelector('#apeMa').value;
        const birthDate = registrarForm.querySelector('#fechaNacim').value;
        const gender = registrarForm.querySelector('#sexo').value;
        const privacy = registrarForm.querySelector('#privacidad').value;
        const role = registrarForm.querySelector('#rol').value;
        const profileImage = registrarForm.querySelector('#imageUpload').files[0];

        // Validaciones
        if (!username || !password || !email || !fullName || !lastName || !maternalLastName || !birthDate || !gender || !privacy || !role) {
            alert('Por favor, complete todos los campos.');
            return;
        } else if (!usernameRegex.test(username)) {
            alert('El nombre de usuario solo puede contener letras, números y guiones bajos (_).');
            return;
        } else if (!passwordRegex.test(password)) {
            alert('La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula (incluyendo "ñ"), un número y un carácter especial.');
            return;
        } else if (!emailPattern.test(email)) {
            alert('Por favor, ingrese un correo electrónico válido.');
            return;
        } else if (!profileImage) {
            alert('Por favor, cargue una imagen de perfil.');
            return;
        }

        // Enviar datos al backend
        const formData = new FormData();
        formData.append('correo', email);
        formData.append('nombreUsu', username);
        formData.append('contrasena', password);
        formData.append('rol', role);
        formData.append('nombres', fullName);
        formData.append('apePa', lastName);
        formData.append('apeMa', maternalLastName);
        formData.append('fechaNacim', birthDate);
        formData.append('sexo', gender);
        formData.append('privacidad', privacy);
        formData.append('imageUpload', profileImage);

        try {
            const response = await fetch('PHP/Registro.php', {
                method: 'POST',
                body: formData
            });
        
            const text = await response.text(); // Leer como texto
            console.log("Respuesta del servidor: ", text); // Aquí se imprimirá en la consola la respuesta
        
            try {
                const data = JSON.parse(text);  // Intentar convertirlo en JSON
        
                if (data.success) {
                    alert('Registro exitoso');
                    registrarForm.reset();  // Limpiar el formulario tras el registro exitoso
                    window.location.href = './Dashboard.html'; // Redirigir al dashboard
                } else {
                    alert('Error al registrar el usuario: ' + data.message);
                }
            } catch (error) {
                console.error('Error al parsear la respuesta JSON:', error);
                alert('Hubo un error al procesar la respuesta. Por favor, inténtelo nuevamente.');
            }
        
        } catch (error) {
            console.error('Error en la solicitud:', error);
            alert('Hubo un error al procesar la solicitud. Por favor, inténtelo nuevamente.');
        }
        
        
    });
});

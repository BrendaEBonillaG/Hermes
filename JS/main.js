console.log("si entro"); 
document.addEventListener("DOMContentLoaded", function () {
    async function verificarNombreUsuario(username) {
        try {
            const response = await fetch('PHP/verificar_usuario.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `nombreUsu=${encodeURIComponent(username)}`
            });
    
            const data = await response.json();
            return data.exists;
        } catch (error) {
            console.error('Error al verificar nombre de usuario:', error);
            return false; 
        }
    }
    


    const registrarForm = document.getElementById("registerForm");
    const registerForm = document.querySelector(".register-form");
    const loginForm = document.querySelector(".login-form");
    const registerBtn = document.querySelector("#register");
    const loginBtn = document.querySelector("#login");

    // validaciones
    const usernameRegex = /^[a-zA-Z_]+$/; 
    const passwordRegex = /^(?=.*[A-ZÑ])(?=.*[a-zñ])(?=.*\d)(?=.*[!¡”#$%&'()*+,-./:;<=>?@[\\\]_{}|~]).{8,}$/;
    const emailPattern = /^[^\s@]+@(gmail\.com|hotmail\.com)$/;


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
        const fileInput = this;
        const fileName = fileInput.files.length > 0 ? fileInput.files[0].name : "Ningún archivo seleccionado";
        const fileNameDisplay = document.getElementById("file-name");
    
        // Validar extensión
        const allowedExtensions = /\.(jpg|jpeg|png)$/i;
    
        if (!allowedExtensions.test(fileName)) {
            alert("Por favor selecciona un archivo de imagen válido (.jpg, .jpeg, .png)");
            fileInput.value = ''; 
            fileNameDisplay.textContent = "Ningún archivo seleccionado";
        } else {
            fileNameDisplay.textContent = fileName;
        }
    });
    

  
        registrarForm.addEventListener("submit", async function (event) {
            event.preventDefault();
            console.log("Interceptado el submit correctamente"); // DEBUG
            const username = registrarForm.querySelector('#nombreUsu').value;
       

            const password = registrarForm.querySelector('#contrasena').value;
            const email = registrarForm.querySelector('#correo').value;
            const fullName = registrarForm.querySelector('#nombres').value;
            const lastName = registrarForm.querySelector('#apePa').value;
            const maternalLastName = registrarForm.querySelector('#apeMa').value;
            const birthDateStr = registrarForm.querySelector('#fechaNacim').value;
            const gender = registrarForm.querySelector('#sexo').value;
            const privacy = registrarForm.querySelector('#privacidad').value;
            const role = registrarForm.querySelector('#rol').value;
            const profileImage = registrarForm.querySelector('#imageUpload').files[0];
    
    
            if (!username || !password || !email || !fullName || !lastName || !maternalLastName || !birthDateStr || !gender || !privacy || !role) {
                alert('Por favor, complete todos los campos.');
                return;
            } else if (!usernameRegex.test(username)) {
                alert('El nombre de usuario solo puede contener letras y guiones bajos (_).');
                return;

            } else if (!usernameRegex.test(fullName)) {
                alert('El nombre solo puede contener letras y guiones bajos (_).');
                return;
                
            } else if (!usernameRegex.test(lastName)) {
                alert('El apellido paterno solo puede contener letras y guiones bajos (_).');
                return;
                
            } else if (!usernameRegex.test(maternalLastName)) {
                alert('El apellido materno solo puede contener letras y guiones bajos (_).');
                return;
                
            }
             else if (!passwordRegex.test(password)) {
                alert('La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula (incluyendo "ñ"), un número y un carácter especial.');
                return;
            } else if (!emailPattern.test(email)) {
                alert('Por favor, ingrese un correo electrónico válido.');
                return;
            } else if (!profileImage) {
                alert('Por favor, cargue una imagen de perfil.');
                return;
            }

           

            // Obtener la fecha de hoy en formato YYYY-MM-DD 
const todayStr = new Date().toISOString().split("T")[0];

// Comparar las fechas 
if (birthDateStr > todayStr) {
    alert("La fecha de nacimiento no puede ser en el futuro.");
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
            formData.append('fechaNacim', birthDateStr);
            formData.append('sexo', gender);
            formData.append('privacidad', privacy);
            formData.append('imageUpload', profileImage);

            const nombreData = new FormData();
            nombreData.append('nombreUsu', username);

            
const nombreRepetido = await verificarNombreUsuario(username);
if (nombreRepetido) {
    alert("El nombre de usuario ya está en uso. Por favor elige otro.");
    return;
}

    
            try {
                const response = await fetch('PHP/Registro.php', {
                    method: 'POST',
                    body: formData
                });
            
                const text = await response.text(); 
                console.log("Respuesta del servidor: ", text); 
            
                try {
                    const data = JSON.parse(text);  
            
                    if (data.success) {
                        alert('Registro exitoso');
                        registrarForm.reset();  
                        window.location.href = './Dashboard.php'; 
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

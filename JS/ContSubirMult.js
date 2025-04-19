document.addEventListener("DOMContentLoaded", function() {
    // Elementos del DOM
    const btnFotos = document.getElementById("btnFotos");
    const btnVideos = document.getElementById("btnVideos");
    const fotosContainer = document.getElementById("fotosContainer");
    const videosContainer = document.getElementById("videosContainer");
    const productForm = document.getElementById("productForm");
    const categoriaSelect = document.querySelector('select[name="categoria"]');
    const imagenesInput = document.getElementById("imagenesInput");
    const videosInput = document.getElementById("videosInput");

    // Verificar que todos los elementos existen
    if (!btnFotos || !btnVideos || !fotosContainer || !videosContainer || !productForm || !categoriaSelect || !imagenesInput || !videosInput) {
        console.error("Error: Algunos elementos del DOM no fueron encontrados");
        return;
    }

    // Manejo de pestañas (fotos/videos)
    btnFotos.addEventListener("click", function() {
        fotosContainer.style.display = "block";
        videosContainer.style.display = "none";
        btnFotos.classList.add("active");
        btnVideos.classList.remove("active");
    });

    btnVideos.addEventListener("click", function() {
        fotosContainer.style.display = "none";
        videosContainer.style.display = "block";
        btnVideos.classList.add("active");
        btnFotos.classList.remove("active");
    });

    // Cargar categorías al iniciar
    cargarCategorias();

    // Manejar envío del formulario
    productForm.addEventListener("submit", async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        
        try {
            submitBtn.disabled = true;
            submitBtn.textContent = "Procesando...";
            
            const response = await fetch("../PHP/RegistrarProducto.php", {
                method: "POST",
                body: formData
            });
            
            // Verificar si la respuesta es JSON válido
            const contentType = response.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                const textResponse = await response.text();
                throw new Error(textResponse || "Respuesta no válida del servidor");
            }
            
            const result = await response.json();
            
            if (result.success) {
                alert(result.message);
                // Redirigir o limpiar el formulario
                window.location.href = "exito.html?productId=" + result.productId;
            } else {
                throw new Error(result.error || "Error desconocido");
            }
        } catch (error) {
            console.error("Error al enviar el formulario:", error);
            alert("Error: " + error.message);
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = "Crear Producto";
            }
        }
    });

    // Función para cargar categorías
    async function cargarCategorias() {
        try {
            const response = await fetch("../PHP/ObtenerCategoria.php");
            
            // Verificar si la respuesta es JSON válido
            const contentType = response.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                const textResponse = await response.text();
                throw new Error(textResponse || "Respuesta no válida del servidor");
            }
            
            const categorias = await response.json();
            
            // Verificar que sea un array
            if (!Array.isArray(categorias)) {
                throw new Error("Formato de datos inválido");
            }
            
            // Limpiar opciones excepto la primera
            while (categoriaSelect.options.length > 1) {
                categoriaSelect.remove(1);
            }
            
            // Agregar nuevas opciones
            categorias.forEach(cat => {
                const option = document.createElement("option");
                option.value = cat.nombre;
                option.textContent = cat.nombre;
                categoriaSelect.appendChild(option);
            });
            
        } catch (error) {
            console.error("Error al cargar categorías:", error);
            // Mostrar mensaje al usuario (opcional)
            alert("No se pudieron cargar las categorías. Puedes escribir una nueva manualmente.");
        }
    }

    // Permitir texto libre en el select de categorías
    categoriaSelect.addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
            e.preventDefault();
            const inputValue = this.value.trim();
            
            // Validación básica
            if (inputValue.length < 2) {
                alert("La categoría debe tener al menos 2 caracteres");
                return;
            }
            
            // Verificar si ya existe (case insensitive)
            const existe = Array.from(this.options).some(
                opt => opt.value.toLowerCase() === inputValue.toLowerCase()
            );
            
            if (!existe) {
                const newOption = document.createElement("option");
                newOption.value = inputValue;
                newOption.textContent = inputValue;
                this.appendChild(newOption);
                this.value = inputValue;
            }
        }
    });

    // Previsualización de imágenes
    imagenesInput.addEventListener("change", function(e) {
        const previewContainer = document.getElementById("imagePreviewContainer");
        previewContainer.innerHTML = "";
        
        Array.from(e.target.files).forEach(file => {
            if (file.type.match("image.*")) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewDiv = document.createElement("div");
                    previewDiv.className = "image-preview";
                    previewDiv.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                    previewContainer.appendChild(previewDiv);
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // Previsualización de videos
    videosInput.addEventListener("change", function(e) {
        const previewContainer = document.getElementById("videoPreviewContainer");
        previewContainer.innerHTML = "";
        
        Array.from(e.target.files).forEach(file => {
            if (file.type.match("video.*")) {
                const previewDiv = document.createElement("div");
                previewDiv.className = "video-preview";
                
                const video = document.createElement("video");
                video.src = URL.createObjectURL(file);
                video.controls = true;
                video.style.width = "100%";
                
                previewDiv.appendChild(video);
                previewContainer.appendChild(previewDiv);
            }
        });
    });
});
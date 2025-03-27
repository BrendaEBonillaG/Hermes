document.addEventListener("DOMContentLoaded", function() {
    const btnFotos = document.getElementById("btnFotos");
    const btnVideos = document.getElementById("btnVideos");
    const fotosContainer = document.getElementById("fotosContainer");
    const videosContainer = document.getElementById("videosContainer");

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
});

document.addEventListener('DOMContentLoaded', function() {
    // Manejo de pestañas
    document.getElementById('btnFotos').addEventListener('click', function() {
        this.classList.add('active');
        document.getElementById('btnVideos').classList.remove('active');
        document.getElementById('fotosContainer').style.display = 'block';
        document.getElementById('videosContainer').style.display = 'none';
    });

    document.getElementById('btnVideos').addEventListener('click', function() {
        this.classList.add('active');
        document.getElementById('btnFotos').classList.remove('active');
        document.getElementById('fotosContainer').style.display = 'none';
        document.getElementById('videosContainer').style.display = 'block';
    });

    // Previsualización de imágenes
    document.getElementById('imagenesInput').addEventListener('change', function(e) {
        const previewContainer = document.getElementById('imagePreviewContainer');
        previewContainer.innerHTML = '';
        
        Array.from(e.target.files).forEach(file => {
            if (file.type.match('image.*')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgDiv = document.createElement('div');
                    imgDiv.className = 'image-preview';
                    imgDiv.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                    previewContainer.appendChild(imgDiv);
                }
                reader.readAsDataURL(file);
            }
        });
    });

    // Previsualización de videos (similar a imágenes)
    document.getElementById('videosInput').addEventListener('change', function(e) {
        // Implementación similar a la de imágenes
    });

    // Envío del formulario
    document.getElementById('productForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        try {
            const response = await fetch('guardar_producto.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                alert('¡Producto registrado con éxito!');
                // Limpiar formulario o redirigir
            } else {
                alert('Error: ' + result.error);
            }
        } catch (error) {
            alert('Error de conexión: ' + error.message);
        }
    });
});
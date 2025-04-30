function mostrarInputCategoria(select) {
    const nuevaCategoriaDiv = document.getElementById('nuevaCategoriaDiv');
    const nuevaCategoriaInput = document.getElementById('nuevaCategoriaInput');

    if (select.value === 'nueva') {
        nuevaCategoriaDiv.style.display = 'block';
        nuevaCategoriaInput.required = true;
    } else {
        nuevaCategoriaDiv.style.display = 'none';
        nuevaCategoriaInput.required = false;
        nuevaCategoriaInput.value = "";
    }
}

function normalizarTexto(texto) {
    return texto
        .normalize("NFD") // descompone acentos
        .replace(/[\u0300-\u036f]/g, "") // quita los acentos
        .toLowerCase()
        .trim();
}

function agregarNuevaCategoria() {
    const select = document.getElementById("categoriaSelect");
    const input = document.getElementById("nuevaCategoriaInput");
    const nombreNueva = input.value.trim();

    if (nombreNueva === "") {
        alert("Por favor escribe un nombre de categoría.");
        return;
    }

    const nombreNormalizado = normalizarTexto(nombreNueva);

    // Verifica que no se repita (ignorando acentos, mayúsculas, espacios)
    const opciones = Array.from(select.options);
    const existe = opciones.some(opt => normalizarTexto(opt.text) === nombreNormalizado);

    if (existe) {
        alert("Esa categoría ya existe.");
        return;
    }

    // Crear y agregar nueva opción justo antes del "+ Agregar nueva"
    const nuevaOpcion = document.createElement("option");
    nuevaOpcion.value = "nueva_" + Date.now(); // valor único
    nuevaOpcion.text = nombreNueva;

    select.add(nuevaOpcion, select.options.length - 1);
    select.value = nuevaOpcion.value;

    // Enviar el valor real
    input.name = "categoria";
    select.name = "categoria_omitida";

    // Ocultar campo de texto
    input.value = "";
    document.getElementById("nuevaCategoriaDiv").style.display = "none";
}

// Vista previa de imágenes y videos al seleccionarlos
document.addEventListener('DOMContentLoaded', function() {
    // Vista previa de imagen
    const imagenInput = document.getElementById('imagenInput');
    const imageList = document.querySelector('.image-list');

    imagenInput.addEventListener('change', function(event) {
        const files = event.target.files;
        
        // Limpiar la lista de imágenes antes de agregar las nuevas
        Array.from(files).forEach(file => {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const imgElement = document.createElement('img');
                imgElement.src = e.target.result;
                imgElement.alt = "Imagen subida";
                imgElement.style.maxWidth = '100%'; // Asegura que la imagen no se desborde

                const divItem = document.createElement('div');
                divItem.classList.add('image-item');
                divItem.style.marginBottom = '10px'; // Espacio entre imágenes
                divItem.appendChild(imgElement);

                const deleteButton = document.createElement('button');
                deleteButton.classList.add('delete-button');
                deleteButton.title = 'Eliminar imagen';
                deleteButton.textContent = '🗑️';
                deleteButton.onclick = function() {
                    divItem.remove();
                };

                divItem.appendChild(deleteButton);
                imageList.appendChild(divItem);
            };
            
            reader.readAsDataURL(file); // Lee el archivo como una URL de datos
        });
    });

    // Vista previa de video
    const videoInput = document.getElementById('videoInput');
    const videoList = document.querySelector('.video-list');

    videoInput.addEventListener('change', function(event) {
        const files = event.target.files;
        
        // Limpiar la lista de videos antes de agregar los nuevos
        Array.from(files).forEach(file => {
            const videoElement = document.createElement('video');
            videoElement.src = URL.createObjectURL(file);
            videoElement.controls = true;
            videoElement.width = 250; // Establecer un tamaño adecuado para el video

            const divItem = document.createElement('div');
            divItem.classList.add('video-item');
            divItem.style.marginBottom = '10px'; // Espacio entre videos
            divItem.appendChild(videoElement);

            const deleteButton = document.createElement('button');
            deleteButton.classList.add('delete-button');
            deleteButton.title = 'Eliminar video';
            deleteButton.textContent = '🗑️';
            deleteButton.onclick = function() {
                divItem.remove();
            };

            divItem.appendChild(deleteButton);
            videoList.appendChild(divItem);
        });
    });
});
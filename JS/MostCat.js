function mostrarInputCategoria(select) {
    const nuevaCategoriaDiv = document.getElementById('nuevaCategoriaDiv');
    const nuevaCategoriaInput = document.getElementById('nuevaCategoriaInput');

    if (select.value === 'nueva') {
        // Mostrar el campo para agregar nueva categoría
        nuevaCategoriaDiv.style.display = 'block';
    } else {
       
        nuevaCategoriaDiv.style.display = 'none';
    }
}

// Validación al enviar el formulario
document.querySelector('form').addEventListener('submit', function(event) {
    const categoriaSelect = document.getElementById('categoriaSelect');
    const nuevaCategoriaInput = document.getElementById('nuevaCategoriaInput');

    if (categoriaSelect.value === 'nueva' && nuevaCategoriaInput.value.trim() === '') {
        event.preventDefault();  
        alert('Por favor, ingresa el nombre de la nueva categoría.');
        nuevaCategoriaInput.focus(); 
    }
});

function normalizarTexto(texto) {
    return texto
        .normalize("NFD") 
        .replace(/[\u0300-\u036f]/g, "") 
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


    const opciones = Array.from(select.options);
    const existe = opciones.some(opt => normalizarTexto(opt.text) === nombreNormalizado);

    if (existe) {
        alert("Esa categoría ya existe.");
        return;
    }


    const nuevaOpcion = document.createElement("option");
    nuevaOpcion.value = nombreNueva; 
    nuevaOpcion.text = nombreNueva;

    select.add(nuevaOpcion, select.options.length - 1);
    select.value = nuevaOpcion.value;

    input.name = "nuevaCategoria";  
    select.name = "categoria_omitida";  


    input.value = "";
    document.getElementById("nuevaCategoriaDiv").style.display = "none";
}





document.addEventListener('DOMContentLoaded', function() {
    // Vista previa de imagen
    const imagenInput = document.getElementById('imagenInput');
    const imageList = document.querySelector('.image-list');

    imagenInput.addEventListener('change', function(event) {
        const files = event.target.files;
        
 
        Array.from(files).forEach(file => {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const imgElement = document.createElement('img');
                imgElement.src = e.target.result;
                imgElement.alt = "Imagen subida";
                imgElement.style.maxWidth = '100%'; 

                const divItem = document.createElement('div');
                divItem.classList.add('image-item');
                divItem.style.marginBottom = '10px'; 
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
            
            reader.readAsDataURL(file); 
        });
    });


    const videoInput = document.getElementById('videoInput');
    const videoList = document.querySelector('.video-list');

    videoInput.addEventListener('change', function(event) {
        const files = event.target.files;
        
  
        Array.from(files).forEach(file => {
            const videoElement = document.createElement('video');
            videoElement.src = URL.createObjectURL(file);
            videoElement.controls = true;
            videoElement.width = 250; 

            const divItem = document.createElement('div');
            divItem.classList.add('video-item');
            divItem.style.marginBottom = '10px';
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
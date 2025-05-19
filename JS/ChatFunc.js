// Definir globalmente la función para obtener el chat activo
window.getChatActivo = function () {
    return window.idChatActivo || null;
};

// Función global para obtener los mensajes
function obtenerMensajes() {
    const idChat = getChatActivo();

    if (!idChat) return;

    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'PHP/Obtener_Mensajes.php?id_chat=' + idChat, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const chatContainer = document.querySelector('.chat-container');
            chatContainer.innerHTML = xhr.responseText;
            chatContainer.scrollTop = chatContainer.scrollHeight;
        } else {
            console.error('Error al obtener mensajes:', xhr.status, xhr.responseText);
        }
    };
    xhr.onerror = function () {
        console.error('Error de red al obtener mensajes.');
    };
    xhr.send();
}

document.addEventListener('DOMContentLoaded', function () {
    const inputMensaje = document.getElementById('mensajeInput');
    const btnEnviar = document.getElementById('btnEnviar');

    if (inputMensaje && btnEnviar) {
        btnEnviar.addEventListener('click', function () {
            const mensaje = inputMensaje.value.trim();
            const idChat = getChatActivo();

            if (!idChat) {
                console.warn('No hay chat activo definido.');
                return;
            }

            if (mensaje === '') {
                console.warn('El mensaje está vacío.');
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'PHP/Insertar_Mensaje.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    console.log('Respuesta del servidor:', xhr.responseText);
                    inputMensaje.value = '';
                    obtenerMensajes();
                } else {
                    console.error('Error al enviar mensaje:', xhr.status, xhr.responseText);
                }
            };
            xhr.onerror = function () {
                console.error('Error de red al enviar mensaje.');
            };

            console.log('Enviando mensaje:', mensaje, 'al chat', idChat);
            xhr.send('mensaje=' + encodeURIComponent(mensaje) + '&id_chat=' + idChat);
        });
    }

    setInterval(() => {
        const chatContainer = document.querySelector('.chat-container');
        if (!chatContainer) return;

        const isBottom = chatContainer.scrollTop + chatContainer.clientHeight >= chatContainer.scrollHeight - 5;

        if (isBottom) {
            obtenerMensajes();
        }
    }, 1000);
});

document.addEventListener('click', function (e) {
    if (e.target && e.target.classList.contains('btn-ver-cotizacion')) {
        const idProducto = e.target.getAttribute('data-producto-id');

    
        function abrirVentanaPago() {
            window.open('tarjeta.html', '_blank', 'width=600,height=600');
        }
   abrirVentanaPago();
    }
   
});


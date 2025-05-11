// Hacer getChatActivo global
window.getChatActivo = function () {
    return window.idChatActivo || null;
};

// Hacer obtenerMensajes global
function obtenerMensajes() {
    var idChat = getChatActivo();

    if (!idChat) return;

    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'PHP/Obtener_Mensajes.php?id_chat=' + idChat, true);
    xhr.onload = function () {
        if (xhr.status == 200) {
            var chatContainer = document.querySelector('.chat-container');
            chatContainer.innerHTML = xhr.responseText;
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    };
    xhr.send();
}

document.addEventListener('DOMContentLoaded', function () {
    var inputMensaje = document.getElementById('mensajeInput');
    var btnEnviar = document.getElementById('btnEnviar');

    function getChatActivo() {
        return window.idChatActivo || null;
    }

    if (inputMensaje && btnEnviar) {
        btnEnviar.addEventListener('click', function () {
            var mensaje = inputMensaje.value.trim();
            var idChat = getChatActivo();

            if (!idChat || mensaje === '') return;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'PHP/Insertar_Mensaje.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status == 200) {
                    inputMensaje.value = '';
                    obtenerMensajes();
                }
            };

            xhr.send('mensaje=' + encodeURIComponent(mensaje) + '&id_chat=' + idChat);
        });
    }



    setInterval(() => {
        const chatContainer = document.querySelector('.chat-container');
        const isBottom = chatContainer.scrollTop + chatContainer.clientHeight >= chatContainer.scrollHeight - 5;

        if (isBottom) {
            obtenerMensajes();
        }
    }, 1000);
});

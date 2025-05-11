document.addEventListener('DOMContentLoaded', function() {
    const usuarios = document.querySelectorAll('.usuario-chat');
    const userNameSpan = document.querySelector('.user-name');
    const userAvatarImg = document.querySelector('.user-avatar');
    const mainContent = document.querySelector('.main-content'); // Asegúrate de que tienes esta clase

    window.idChatActivo = null;

    usuarios.forEach(function(usuario) {
        usuario.addEventListener('click', function(e) {
            e.preventDefault();
            const nombreUsuario = usuario.getAttribute('data-nombre');
            const fotoUsuario = usuario.getAttribute('data-foto');
            const idChat = usuario.getAttribute('data-id-chat');

            // Actualizar nombre y foto del usuario
            userNameSpan.textContent = nombreUsuario;
            userAvatarImg.src = fotoUsuario;

            // Activar el chat
            window.idChatActivo = parseInt(idChat);

            // Mostrar main-content cuando se hace clic en un usuario
            mainContent.style.display = 'flex'; // Asegúrate de que está visible

            // Aquí puedes agregar más lógica para cargar los mensajes si es necesario
            obtenerMensajes();
        });
    });
});

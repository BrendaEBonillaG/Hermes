document.addEventListener('DOMContentLoaded', function() {
    const usuarios = document.querySelectorAll('.usuario-chat');
    const userNameSpan = document.querySelector('.user-name');
    const userAvatarImg = document.querySelector('.user-avatar');
    const mainContent = document.querySelector('.main-content');
    const btnVendedor = document.getElementById('btnOpcionesVendedor'); // ← nuevo

    window.idChatActivo = null;

    usuarios.forEach(function(usuario) {
        usuario.addEventListener('click', function(e) {
            e.preventDefault();
            const nombreUsuario = usuario.getAttribute('data-nombre');
            const fotoUsuario = usuario.getAttribute('data-foto');
            const idChat = usuario.getAttribute('data-id-chat');

    
            userNameSpan.textContent = nombreUsuario;
            userAvatarImg.src = fotoUsuario;

            // Activar el chat
            window.idChatActivo = parseInt(idChat);


            mainContent.style.display = 'flex';

            // Mostrar el botón solo si existe (rol vendedor)
            if (btnVendedor) {
                btnVendedor.classList.remove('d-none');
            }


            obtenerMensajes();
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const usuarios = document.querySelectorAll('.usuario-chat');
    const userNameSpan = document.querySelector('.user-name');
    const userAvatarImg = document.querySelector('.user-avatar');

    window.idChatActivo = null; 

    usuarios.forEach(function(usuario) {
        usuario.addEventListener('click', function(e) {
            e.preventDefault();
            const nombreUsuario = usuario.getAttribute('data-nombre');
            const fotoUsuario = usuario.getAttribute('data-foto');
            const idChat = usuario.getAttribute('data-id-chat');

            userNameSpan.textContent = nombreUsuario;
            userAvatarImg.src = fotoUsuario;

            window.idChatActivo = parseInt(idChat);

        });
    });
});

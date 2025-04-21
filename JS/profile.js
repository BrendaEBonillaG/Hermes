// Simulando el rol del usuario (puedes modificar esta variable para cambiar el comportamiento)
let role = vendedor; // Opciones: 'comprador', 'vendedor', 'administrador', 'privado'

// Función para cargar el perfil según el rol
function loadUserProfile(role) {
    // Ocultamos todo el contenido primero
    document.getElementById('privateMessage').classList.add('hidden');
    document.getElementById('publicProfile').classList.add('hidden');
    document.getElementById('sellerProfile').classList.add('hidden');
    document.getElementById('adminProfile').classList.add('hidden');
    
    if (role === 'privado') {
        document.getElementById('privateMessage').classList.remove('hidden');
    } else if (role === 'comprador') {
        document.getElementById('publicProfile').classList.remove('hidden');
    } else if (role === 'vendedor') {
        document.getElementById('sellerProfile').classList.remove('hidden');
    } else if (role === 'administrador') {
        document.getElementById('adminProfile').classList.remove('hidden');
    }
}







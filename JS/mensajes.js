document.addEventListener("DOMContentLoaded", function () {
    const chatInput = document.querySelector("#message-input");  // Cambiado a ID 'message-input'
    const sendButton = document.querySelector(".send-btn");    // Cambiado a clase 'send-btn'
    const chatContainer = document.querySelector(".chat-box");  // Cambiado a la clase 'chat-box'

    sendButton.addEventListener("click", function () {
        sendMessage();
    });

    // También enviar con la tecla "Enter"
    chatInput.addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
            sendMessage();
        }
    });

    function sendMessage() {
        const messageText = chatInput.value.trim();

        if (messageText !== "") {
            // Obtener la hora con AM/PM
            const now = new Date();
            const hours = now.getHours() % 12 || 12; // Convertir formato 12 horas
            const minutes = now.getMinutes().toString().padStart(2, "0");
            const ampm = now.getHours() >= 12 ? "PM" : "AM";
            const formattedTime = `${hours}:${minutes} ${ampm}`;

            // Crear el mensaje nuevo
            const messageDiv = document.createElement("div");
            messageDiv.classList.add("message", "sent");  // Cambié a "sent" para indicar que el mensaje es enviado por el usuario

            const messageContent = `
                <p>${messageText}</p>
                <span class="message-time">${formattedTime}</span>
            `;

            messageDiv.innerHTML = messageContent;

            // Agregar el mensaje al chat
            chatContainer.appendChild(messageDiv);

            // Limpiar el input
            chatInput.value = "";

            // Hacer scroll hacia abajo para ver el último mensaje
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    }
});

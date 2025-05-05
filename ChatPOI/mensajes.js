document.addEventListener("DOMContentLoaded", function () {
    const chatInput = document.querySelector(".chat-input input");
    const sendButton = document.querySelector(".chat-input button");
    const chatContainer = document.querySelector(".chat-container");

    sendButton.addEventListener("click", function () {
        sendMessage();
    });

    chatInput.addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
            sendMessage();
        }
    });

    function sendMessage() {
        const messageText = chatInput.value.trim();

        if (messageText !== "") {

            const now = new Date();
            const hours = now.getHours() % 12 || 12; 
            const minutes = now.getMinutes().toString().padStart(2, "0");
            const ampm = now.getHours() >= 12 ? "PM" : "AM";
            const formattedTime = `${hours}:${minutes} ${ampm}`;

            const messageDiv = document.createElement("div");
            messageDiv.classList.add("chat-message", "outgoing");

            const messageContent = `
                <div class="message-content">
                    <p>${messageText}</p>
                    <span class="message-time">${formattedTime}</span>
                </div>
            `;

            messageDiv.innerHTML = messageContent;

            chatContainer.appendChild(messageDiv);

            chatInput.value = "";

            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const taskModal = document.getElementById("taskModal");
    const taskBtn = document.querySelector(".btn-tasks");
    const closeBtn = document.querySelector(".close-btn");
    const taskList = document.getElementById("taskList");
    const taskInput = document.getElementById("taskInput");
    const taskAssignee = document.getElementById("taskAssignee");
    const addTaskBtn = document.getElementById("addTaskBtn");

    // CIERRA LA VETANA 
    taskBtn.addEventListener("click", function () {
        taskModal.style.display = "flex";
    });

    // LA X
    closeBtn.addEventListener("click", function () {
        taskModal.style.display = "none";
    });

    // AÑADE LAS TAREAS
    addTaskBtn.addEventListener("click", function () {
        const taskText = taskInput.value.trim();
        const assigneeText = taskAssignee.value;

        if (taskText !== "") {
            const li = document.createElement("li");
            li.innerHTML = `
                <strong>${taskText}</strong> <br>
                <small>Por: Brenda01 | Para: ${assigneeText}</small>
                <span class="delete-task">&times;</span>
            `;
            taskList.appendChild(li);
            taskInput.value = "";
        }
    });

    taskList.addEventListener("click", function (event) {
        if (event.target.classList.contains("delete-task")) {
            event.target.parentElement.remove();
        }
    });
});

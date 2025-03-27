document.addEventListener("DOMContentLoaded", function() {
    const btnFotos = document.getElementById("btnFotos");
    const btnVideos = document.getElementById("btnVideos");
    const fotosContainer = document.getElementById("fotosContainer");
    const videosContainer = document.getElementById("videosContainer");

    btnFotos.addEventListener("click", function() {
        fotosContainer.style.display = "block";
        videosContainer.style.display = "none";
        btnFotos.classList.add("active");
        btnVideos.classList.remove("active");
    });

    btnVideos.addEventListener("click", function() {
        fotosContainer.style.display = "none";
        videosContainer.style.display = "block";
        btnVideos.classList.add("active");
        btnFotos.classList.remove("active");
    });
});

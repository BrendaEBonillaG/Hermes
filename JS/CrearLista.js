
    const openBtn = document.getElementById("openWishlistModal");
    const modal = document.getElementById("wishlistModal");
    const closeBtn = document.getElementById("closeWishlistModal");

    openBtn.onclick = () => {
        modal.style.display = "block";
    }

    closeBtn.onclick = () => {
        modal.style.display = "none";
    }

    window.onclick = (event) => {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }


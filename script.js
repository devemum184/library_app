document.addEventListener("DOMContentLoaded", function() {
    const filterBtns = document.querySelectorAll(".filter-btn");
    const bookCards = document.querySelectorAll(".card-item");

    filterBtns.forEach(btn => {
        btn.addEventListener("click", function() {
            filterBtns.forEach(b => b.classList.remove("active"));
            this.classList.add("active");
            
            const categoryId = this.getAttribute("data-id");
            
            bookCards.forEach(card => {
                if (categoryId === "all" || card.getAttribute("data-category") === categoryId) {
                    card.style.display = "flex";
                } else {
                    card.style.display = "none";
                }
            });
        });
    });
});
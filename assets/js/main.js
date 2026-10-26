/* ==========================================
   NexusShop - Client Interactions
   ========================================== */

document.addEventListener('DOMContentLoaded', () => {
    console.log('⚡ NexusShop client JS initialized.');

    // 1. Client-Side Search Filter (for shop.php)
    const searchInput = document.getElementById('shop-search');
    const productCards = document.querySelectorAll('.product-card');

    if (searchInput && productCards.length > 0) {
        searchInput.addEventListener('keyup', (e) => {
            const searchTerm = e.target.value.toLowerCase().trim();

            productCards.forEach(card => {
                const title = card.querySelector('.product-title')?.textContent.toLowerCase() || '';
                if (title.includes(searchTerm)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    // 2. Add-to-Cart Animation / Feedback
    const addToCartBtns = document.querySelectorAll('.btn-add-cart');
    addToCartBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            btn.textContent = 'Added! ✓';
            btn.style.backgroundColor = '#10b981'; // Green confirmation color
            setTimeout(() => {
                btn.textContent = 'Add to Cart 🛒';
                btn.style.backgroundColor = 'var(--primary-color)';
            }, 1500);
        });
    });
});
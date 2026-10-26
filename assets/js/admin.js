/* ==========================================
   NexusShop - Admin Interactivity
   ========================================== */

document.addEventListener('DOMContentLoaded', () => {
    console.log('⚡ Admin JS loaded.');

    // Confirm deletion actions
    const deleteLinks = document.querySelectorAll('.btn-delete');
    deleteLinks.forEach(link => {
        link.addEventListener('click', (event) => {
            if (!confirm('Are you sure you want to delete this record? This action cannot be undone.')) {
                event.preventDefault();
            }
        });
    });
});
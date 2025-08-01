document.addEventListener('DOMContentLoaded', function () {
    const deleteForm = document.getElementById('deleteForm');
    const modal = document.getElementById('deleteModal');

    if (!modal || !deleteForm) {
        console.error("Modal or form not found!");
        return;
    }

    document.body.addEventListener('click', function (event) {
        if (event.target.matches('.deleteButton')) {
            let userId = event.target.getAttribute('data-user-id');
            deleteForm.setAttribute('action', `/admin/users/${userId}`);

            // Show modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        if (event.target.matches('.closeModal') || event.target === modal) {
            // Hide modal
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    });
});


function confirmDelete(lineID) {
    
    const modal = document.getElementById('deleteModal');
    const confirmButton = document.getElementById('confirmDeleteButton');

    // Show modal
    modal.classList.remove('hidden');

    // Set up confirmation button
    confirmButton.onclick = function() {
        document.getElementById('deleteForm').lineID.value = lineID;
        document.getElementById('deleteForm').submit();
    };
}

function closeModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

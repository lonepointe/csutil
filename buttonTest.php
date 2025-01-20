<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Delete Example</title>
    <style>
        .hidden {
            display: none;
        }
        .modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            z-index: 1000;
        }
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
    </style>
</head>
<body>

<h1>Delete Confirmation Example</h1>

<!-- Example Delete Button -->
<button onclick="confirmDelete(1)">Delete Item 1</button>
<button onclick="confirmDelete(2)">Delete Item 2</button>

<!-- Modal -->
<div id="deleteModal" class="hidden">
    <div class="modal-overlay" onclick="closeModal()"></div>
    <div class="modal">
        <p>Are you sure you want to delete this item?</p>
        <button id="confirmDeleteButton">Confirm</button>
        <button onclick="closeModal()">Cancel</button>
    </div>
</div>

<!-- Hidden Form -->
<form id="deleteForm" method="POST" action="delete.php">
    <input type="hidden" name="lineID" value="">
</form>

<script>
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
</script>

</body>
</html>

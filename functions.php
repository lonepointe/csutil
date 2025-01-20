<?php
function deleteRow(PDO $db, $lineID) {
    if ($lineID !== null) {
        try {
            // Prepare the DELETE SQL statement
            $sql = "DELETE FROM orders WHERE id = :id";
            $stmt = $db->prepare($sql);

            // Bind the ID parameter to the query
            $stmt->bindParam(':id', $lineID, PDO::PARAM_INT);

            // Execute the query
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo "<div class='text-green-500'>Row with ID $lineID deleted successfully.</div>";
            } else {
                echo "<div class='text-yellow-500'>No row found with ID $lineID.</div>";
            }
        } catch (PDOException $e) {
            echo "<div class='text-red-500'>Error deleting row: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    } else {
        echo "<div class='text-red-500'>No ID specified for deletion.</div>";
    }
}


function newEntryButton() {
    return '
    <form action="index.php" method="POST" class="my-4">
        <button type="submit" name="action" value="new" 
            class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
            New Entry
        </button>
    </form>';
}

// Example usage
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? null) === 'delete') {
//     $lineID = $_POST['lineID'] ?? null;
//     deleteRow($db, $lineID); -->
// }

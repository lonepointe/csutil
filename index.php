<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Interaction</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="script.js"></script>
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="container mx-auto p-8">
<?php

require 'testingenv.php';
require'functions.php';
require 'BoxCalculator.php';


loadEnv();

$dbPath = $_ENV['database_name'] ?? null;

if (!$dbPath) {
    die("<div class='text-red-500'>Database path not set in .env file.</div>");
}

// Check if the database exists
if (!file_exists($dbPath)) {
    echo "<div class='text-yellow-500'>Database does not exist. Creating a new database...</div>";

    $db = new PDO('sqlite:' . $dbPath);
    echo "<div class='text-green-500'>Database created successfully!</div>";

    $createTableSQL = "CREATE TABLE IF NOT EXISTS orders (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        cycleTime INT NOT NULL,
        numBox INT NOT NULL,
        cavities INT NOT NULL,
        family INT NOT NULL
    )";

    $db->exec($createTableSQL);
    echo "<div class='text-green-500'>Table 'orders' created successfully!</div>";
} else {
    echo "<div class='text-blue-500'>Database exists. Connecting to the database...</div>";
    $db = new PDO('sqlite:' . $dbPath);
    echo "<div class='text-green-500'>Connected to the existing database!</div>";
}




echo "<div class='text-gray-700'>Using database at: <span class='font-semibold'>" . htmlspecialchars($dbPath) . "</span></div>";

if ($_SERVER['REQUEST_METHOD'] === 'GET' && empty($_GET) && empty($_POST)) {
    echo "<div class='my-4'>This is a fresh load with no data submitted.</div>";
    echo newEntryButton();
    $action = "none";
    $lineID = "none";
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? "error";
    $cycleTime = $_POST['cycleTime'] ?? 60;
    $cavities = $_POST['cavities'] ?? 1;
    $numBox = $_POST['numBox'] ?? 1;
    $family = $_POST['family'] ?? 0;

    $action = $_POST['action'] ?? "none";
    $lineID = $_POST['lineID'] ?? "none";
}

    echo "<div class='my-4'>Action: <span class='font-semibold'>" . htmlspecialchars($action) . "</span>, Line ID: <span class='font-semibold'>" . htmlspecialchars($lineID) . "</span></div>";


    
    switch ($action) {
        case 'edit':
            echo "<div class='text-yellow-500'>Editing item ID: " . htmlspecialchars($lineID) . "</div>";
            
            echo newEntryButton();
            break;
    
        case 'delete':
            echo "<div class='text-red-500'>Deleting item ID: " . htmlspecialchars($lineID) . "</div>";
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? "none") === 'delete') {
            $lineID = $_POST['lineID'] ?? "none";
            deleteRow($db, $lineID);
            // should probably put a "are you sure" in here somewhere lol
            // and some error checking.
        }
            echo newEntryButton();
            break;
    
        case 'new':
            echo '<div class="bg-gray-200 p-4 rounded-md my-4">
                <h2 class="text-lg font-semibold">Add a New Entry</h2>
                <form action="index.php" method="POST" class="space-y-4">
                    <input type="hidden" name="action" value="insert">
                    <div>
                        <label for="name" class="block font-medium">Name:</label>
                        <input type="text" id="name" name="name" required
                            class="border border-gray-300 rounded w-full p-2">
                    </div>
                    <div>
                        <label for="cycleTime" class="block font-medium">Cycle Time (seconds):</label>
                        <input type="number" id="cycleTime" name="cycleTime" required
                            class="border border-gray-300 rounded w-full p-2">
                    </div>
                    <div>
                        <label for="numBox" class="block font-medium">Quant Box:</label>
                        <input type="number" id="numBox" name="numBox" required
                            class="border border-gray-300 rounded w-full p-2">
                    </div>
                    <div>
                        <label for="cavities" class="block font-medium">Cavities:</label>
                        <input type="number" id="cavities" name="cavities" required
                            class="border border-gray-300 rounded w-full p-2">
                    </div>
                    <div>
                        <label for="family" class="block font-medium">Family Tool:</label>
                        <input type="number" id="family" name="family" required
                            class="border border-gray-300 rounded w-full p-2">
                    </div>
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded">
                        Submit
                    </button>
                </form>
            </div>';
            break;
    
        case 'insert':
            try {
                $checkSql = "SELECT COUNT(*) FROM orders WHERE name = :name AND cycleTime = :cycleTime AND numBox = :numBox AND cavities = :cavities AND family = :family";
                $checkStmt = $db->prepare($checkSql);
                $checkStmt->execute([
                    ':name' => $name,
                    ':cycleTime' => $cycleTime,
                    ':numBox' => $numBox,
                    ':cavities' => $cavities,
                    ':family' => $family,
                ]);
    
                $count = $checkStmt->fetchColumn();
    
                if ($count == 0) {
                    $sql = "INSERT INTO orders (name, cycleTime, numBox, cavities, family) VALUES (:name, :cycleTime, :numBox, :cavities, :family)";
                    $stmt = $db->prepare($sql);
                    $stmt->execute([
                        ':name' => $name,
                        ':cycleTime' => $cycleTime,
                        ':numBox' => $numBox,
                        ':cavities' => $cavities,
                        ':family' => $family,
                    ]);
                    echo "<div class='text-green-500'>New entry added successfully!</div>";
                    echo newEntryButton();
                } else {
                    echo "<div class='text-yellow-500'>Duplicate entry detected. No data was added.</div>";
                    echo newEntryButton();
                }
            } catch (PDOException $e) {
                echo "<div class='text-red-500'>Error inserting data: " . htmlspecialchars($e->getMessage()) . "</div>";
            }
            break;
    
        default:
            echo "<div class='text-red-500'>Unknown action specified.</div>";
            break;
    }
    

// Listing Orders
echo "<div class='my-4'>Listing Orders:</div>";

try {
    $sql = "SELECT * FROM orders";
    $stmt = $db->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<table class='table-auto w-full border-collapse border border-gray-300'>
        <thead class='bg-gray-100'>
            <tr>
                <th class='border border-gray-300 px-4 py-2'>ID</th>
                <th class='border border-gray-300 px-4 py-2'>Name</th>
                <th class='border border-gray-300 px-4 py-2'>Cycle Time</th>
                <th class='border border-gray-300 px-4 py-2'>Quant Box</th>
                <th class='border border-gray-300 px-4 py-2'>Cavities</th>
                <th class='border border-gray-300 px-4 py-2'>Family</th>
                <th class='border border-gray-300 px-4 py-2'>Actions</th>
            </tr>
        </thead>
        <tbody>";

    foreach ($results as $row) {
        echo "<tr class='hover:bg-gray-50'>
            <td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['id']) . "</td>
            <td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['name']) . "</td>
            <td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['cycleTime']) . "</td>
            <td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['numBox']) . "</td>
            <td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['cavities']) . "</td>
            <td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['family']) . "</td>
            <td class='border border-gray-300 px-4 py-2'>
                <form action='index.php' method='POST' class='inline-block'>
                    <input type='hidden' name='lineID' value='" . htmlspecialchars($row['id']) . "'>
                    <button type='submit' name='action' value='edit' class='bg-blue-500 text-white px-2 py-1 rounded'>Edit</button>
                </form>
                <form action='index.php' method='POST' class='inline-block'>
                    <input type='hidden' name='lineID' value='" . htmlspecialchars($row['id']) . "'>
                    <button onclick='confirmDelete(1)' type='submit' name='action' value='delete' class='bg-red-500 text-white px-2 py-1 rounded'>Delete</button>
                   
                </form>
            </td>
        </tr>";
    }

    echo "</tbody></table>";
} catch (PDOException $e) {
    echo "<div class='text-red-500'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
}
?>
    </div>
</body>
</html>

<?php /*
<!--  stuff I gotta figure out, I don't know javascript really.
<button onclick="confirmDelete(1)">Delete Item 1</button>
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

// Example usage
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? null) === 'delete') {
    $lineID = $_POST['lineID'] ?? null;
    deleteRow($db, $lineID);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Delete Modal</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="container mx-auto p-8">

        <!-- Modal -->
        <div id="deleteModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white p-6 rounded shadow-lg text-center">
                <h2 class="text-lg font-semibold mb-4">Are you sure you want to delete this row?</h2>
                <div class="flex justify-center space-x-4">
                    <button onclick="closeModal()" class="bg-gray-300 hover:bg-gray-400 text-black px-4 py-2 rounded">Cancel</button>
                    <button id="confirmDeleteButton" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Delete</button>
                </div>
            </div>
        </div>

        <!-- Delete Form -->
        <form id="deleteForm" action="index.php" method="POST">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="lineID" value="">
        </form>

        <!-- Example Delete Button -->
        <button onclick="confirmDelete(123)" class="bg-red-500 text-white px-4 py-2 rounded">Delete Row</button>

    </div>
</body>
</html>

-->
*/
?>


<?php

// Include the env loader
require 'testingenv.php';

$database = new SQLite3('testing.sqlite');
echo "Database created successfully!";

// Load the .env file
loadEnv();
// Get the database path from the .env file
$dbPath = $_ENV['database_name'] ?? null;

if (!$dbPath) {
    die("Database path not set in .env file.<br>");
}

// Connect to the SQLite database
try {
    $db = new PDO('sqlite:' . $dbPath);
    echo "Connected to SQLite database successfully!<br>";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "<br>";
}

try {
    $sql = "CREATE TABLE IF NOT EXISTS orders (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        cycleTime INT NOT NULL,
        numBox INT NOT NULL, 
        cavities INT NOT NULL,
        family INT NOT NULL
    )";

    $db->exec($sql);
    echo "orders Table created successfully!<br>";
} catch (PDOException $e) {
    echo "orders table not created for some reason: " . $e->getMessage() . "<br>";
}

// id
// name
// cycle time
// # per box
// cavities
// family tool (assume cavities equals both halves, ie. 2 cavites is l & r not 2left and 2right.) This needs to be bold red and error checked, can't be odd.

// future, all the other data.


echo "Using database at: " . $dbPath . "<br>";



?>

<form action="setup2.php" method="POST">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" placeholder="Enter name" required><br>

    <label for="cycleTime">Cycle Time (seconds):</label>
    <input type="number" id="cycleTime" name="cycleTime" placeholder="Enter cycle time" required><br>

    <label for="numBox">Number of Boxes:</label>
    <input type="number" id="numBox" name="numBox" placeholder="Enter number of boxes" required><br>

    <label for="cavities">Cavities:</label>
    <input type="number" id="cavities" name="cavities" placeholder="Enter cavities" required><br>

    <label for="family">Family Tool:</label>
    <input type="number" id="family" name="family" placeholder="Enter family tool number" required><br>

    <button type="submit">Submit</button>
</form>

<?php
echo "I'm inserting some data!<br>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? "error";
    $cycleTime = $_POST['cycleTime'] ?? 60; // Default to 1 if not set
    $cavities = $_POST['cavities'] ?? 1; // Default to 1 if not set
    $numBox = $_POST['numBox'] ?? 1; // Default to 1 if not set
    $family = $_POST['family'] ?? 0;
}




// Check for duplicate data before inserting
try {
    $checkSql = "SELECT COUNT(*) FROM orders WHERE name = :name AND cycleTime = :cycleTime AND numBox = :numBox AND cavities = :cavities AND family = :family";
    $checkStmt = $db->prepare($checkSql);
    $checkStmt->execute([
        ':name' => $name,
        ':cycleTime' => $cycleTime,
        ':numBox' => $numBox,
        ':cavities' => $cavities,
        ':family' => $family
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
            ':family' => $family
        ]);
        echo "Test data inserted successfully!<br>";
    } else {
        echo "Test data already exists. Skipping insert.<br>";
    }
} catch (PDOException $e) {
    echo "inserting garbage didn't work for some reason: " . $e->getMessage() . "<br>";
}

echo "I'm at the listing part! <br>";
try {
    $sql = "SELECT * FROM orders";
    $stmt = $db->query($sql);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($results as $row) {
        echo "ID: {$row['id']}, Name: {$row['name']}, Cycle Time: {$row['cycleTime']}, Box Quant: {$row['numBox']}, Cavities: {$row['cavities']}, Family: {$row['family']}<br>";
    }
} catch (PDOException $e) {
    echo "sql list didn't work for some reason: " . $e->getMessage() . "<br>";
}

echo "I'm at the end!<br>";

?>
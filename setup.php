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
   // $db = new PDO('sqlite:my_database.sqlite');

    $sql = "CREATE TABLE IF NOT EXISTS  orders (
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

echo "I'm inserting some data!<br>";

// we need to encapsulate this shit.


try {
    $sql = "INSERT INTO orders (name, cycleTime, numBox, cavities, family) VALUES ('Test Order', 60, 15, 2, 1);";
    $stmt = $db->query($sql);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($results as $row) {
        echo "ID: {$row['id']}, Name: {$row['name']}, Cycle Time: {$row['cycleTime']}, Box Quant: {$row['numBox']}, Cavities: {$row['Cavities']}, Family: {$row['family']}<br>";
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

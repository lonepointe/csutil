<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Box Calculator</title>
</head>

<body>
    <h1>Box Calculator Results</h1>





    <pre>
<form method="POST" action="">
<label for="hours">Hours Runtime:</label>
<input type="number" id="number" name="hours" value="1" min="1" max="24" step="1">

<label for="cycle_time">Cycle Time:</label>
<input type="number" id="number" name="cycleTime" value="1" min="1" max="1000" step="1">

<label for="cavities">Cavities:</label>
<input type="number" id="number" name="cavities" value="1" min="1" max="99999" step="1">

<label for="number">Parts per Box:</label>
<input type="number" id="number" name="partsPerBox" value="1" min="1" max="9999" step="1">



<button type="submit">Submit</button>
</form>



<?php
require_once "BoxCalculator.php";

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hours = $_POST['hours'] ?? 8; // Default to 1 if not set
    $cycleTime = $_POST['cycleTime'] ?? 60; // Default to 1 if not set
    $cavities = $_POST['cavities'] ?? 1; // Default to 1 if not set
    $partsPerBox = $_POST['partsPerBox'] ?? 1; // Default to 1 if not set
}


$calculator = new BoxCalculator($cycleTime, $cavities, $hours, $partsPerBox);
$results = $calculator->calculate();


// Output the results
foreach ($results as $key => $value) {
    echo "$key: $value\n <br>";
}

?>

</pre>
</body>

</html>
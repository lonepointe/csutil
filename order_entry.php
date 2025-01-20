<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Input Form</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Order Input Form</h1>
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
</body>
</html>
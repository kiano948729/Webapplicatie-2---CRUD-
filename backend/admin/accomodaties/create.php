<?php
session_start();
require '../../conn.php';

// Verwerk formulier
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("INSERT INTO accommodaties (name, type, location, description, photo_url, price) VALUES (:name, :type, :location, :description, :photo_url, :price)");
    $stmt->execute([
        ':name' => $_POST['name'],
        ':type' => $_POST['type'],
        ':location' => $_POST['location'],
        ':description' => $_POST['description'],
        ':photo_url' => $_POST['photo_url'],
        ':price' => $_POST['price']
    ]);
    header("Location: ../admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Nieuwe Accommodatie</title>
</head>

<body>
    <h1>Nieuwe Accommodatie Toevoegen</h1>
    <form method="post">
        <label>Naam:</label><br>
        <input name="name" required><br><br>

        <label>Type:</label><br>
        <select name="type" required>
            <option value="hotel">hotel</option>
            <option value="huis">huis</option>
            <option value="camping">camping</option>
            <option value="hostel">hostel</option>
            <option value="anders">anders</option>
        </select><br><br>

        <label>Locatie:</label><br>
        <input name="location" required><br><br>

        <label>Beschrijving:</label><br>
        <textarea name="description"></textarea><br><br>

        <label>Foto URL:</label><br>
        <input name="photo_url"><br><br>

        <label>Prijs:</label><br>
        <input name="price" type="number" step="0.01" required><br><br>

        <button type="submit">Opslaan</button>
        <a href="../admin.php">Annuleren</a>
    </form>
</body>

</html>
<?php
session_start();
require_once __DIR__ . '../../../../config/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Init foto_url als NULL voor het geval er geen foto geupload wordt
    $photo_url = null;

    // Foto upload checken
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['photo']['tmp_name'];
        $fileName = $_FILES['photo']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExtension, $allowedExtensions)) {
            // Unieke bestandsnaam genereren
            $newFileName = uniqid('img_', true) . '.' . $fileExtension;
            $uploadDir = '../../img/';
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                // Relatief pad opslaan voor gebruik in HTML
                $photo_url = 'img/' . $newFileName;
            } else {
                die('Fout bij het verplaatsen van het geüploade bestand.');
            }
        } else {
            die('Ongeldig bestandstype. Alleen jpg, jpeg, png, gif toegestaan.');
        }
    }

    // Accommodatie toevoegen aan DB
    $stmt = $conn->prepare("
        INSERT INTO accommodaties (name, type, location, description, photo_url, price) 
        VALUES (:name, :type, :location, :description, :photo_url, :price)
    ");

    $stmt->execute([
        ':name' => $_POST['name'],
        ':type' => $_POST['type'],
        ':location' => $_POST['location'],
        ':description' => $_POST['description'],
        ':photo_url' => $photo_url,
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
    <title>Nieuwe Accommodatie Toevoegen</title>
</head>

<body>
    <h1>Nieuwe Accommodatie Toevoegen</h1>
    <form method="post" enctype="multipart/form-data">
        <label>Naam:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Type:</label><br>
        <select name="type" required>
            <option value="hotel">hotel</option>
            <option value="huis">huis</option>
            <option value="camping">camping</option>
            <option value="hostel">hostel</option>
            <option value="anders">anders</option>
        </select><br><br>

        <label>Locatie:</label><br>
        <input type="text" name="location" required><br><br>

        <label>Beschrijving:</label><br>
        <textarea name="description"></textarea><br><br>

        <label>Foto uploaden:</label><br>
        <input type="file" name="photo" accept="image/*"><br><br>

        <label>Prijs:</label><br>
        <input type="number" name="price" step="0.01" required><br><br>

        <button type="submit">Opslaan</button>
        <a href="../admin.php">Annuleren</a>
    </form>
</body>

</html>
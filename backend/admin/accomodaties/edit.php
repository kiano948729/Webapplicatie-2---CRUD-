<?php
session_start();
require '../../conn.php';

if (!isset($_GET['id'])) {
    die("Geen ID opgegeven.");
}
$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM accommodaties WHERE accommodation_id = :id");
$stmt->execute([':id' => $id]);
$accommodation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$accommodation) {
    die("Accommodatie niet gevonden.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_photo_url = $accommodation['photo_url'];
    $new_photo_url = $current_photo_url;

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['photo']['tmp_name'];
        $fileName = $_FILES['photo']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExtension, $allowedExtensions)) {
            $newFileName = uniqid('img_', true) . '.' . $fileExtension;
            $uploadDir = '../../img/';
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $new_photo_url = 'img/' . $newFileName;

                if (!empty($current_photo_url) && file_exists($uploadDir . basename($current_photo_url))) {
                    unlink($uploadDir . basename($current_photo_url));
                }
            } else {
                die("Fout bij het verplaatsen van het bestand.");
            }
        } else {
            die("Ongeldig bestandstype. Alleen jpg, jpeg, png of gif toegestaan.");
        }
    }

    $stmt = $conn->prepare("
        UPDATE accommodaties 
        SET 
            name = :name, 
            type = :type, 
            location = :location, 
            description = :description, 
            photo_url = :photo_url, 
            price = :price 
        WHERE accommodation_id = :id
    ");
    $stmt->execute([
        ':name' => $_POST['name'],
        ':type' => $_POST['type'],
        ':location' => $_POST['location'],
        ':description' => $_POST['description'],
        ':photo_url' => $new_photo_url,
        ':price' => $_POST['price'],
        ':id' => $id
    ]);

    header("Location: ../admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Accommodatie Bewerken</title>
</head>

<body>
    <h1>Accommodatie Bewerken</h1>
    <form method="post" enctype="multipart/form-data">
        <label>Naam:</label><br>
        <input name="name" value="<?= htmlspecialchars($accommodation['name']) ?>" required><br><br>

        <label>Type:</label><br>
        <select name="type" required>
            <?php
            $types = ['hotel', 'huis', 'camping', 'hostel', 'anders'];
            foreach ($types as $type) {
                $selected = ($accommodation['type'] === $type) ? 'selected' : '';
                echo "<option value=\"$type\" $selected>$type</option>";
            }
            ?>
        </select><br><br>

        <label>Locatie:</label><br>
        <input name="location" value="<?= htmlspecialchars($accommodation['location']) ?>" required><br><br>

        <label>Beschrijving:</label><br>
        <textarea name="description"><?= htmlspecialchars($accommodation['description']) ?></textarea><br><br>

        <label>Huidige foto:</label><br>
        <?php if (!empty($accommodation['photo_url'])): ?>
            <img src="<?= htmlspecialchars($accommodation['photo_url']) ?>" alt="Huidige foto"
                style="max-width: 200px;"><br><br>
        <?php else: ?>
            <p>Geen foto beschikbaar.</p>
        <?php endif; ?>

        <label>Nieuwe foto uploaden (optioneel):</label><br>
        <input type="file" name="photo" accept="image/*"><br><br>

        <label>Prijs:</label><br>
        <input name="price" type="number" step="0.01" value="<?= htmlspecialchars($accommodation['price']) ?>"
            required><br><br>

        <button type="submit">Opslaan</button>
        <a href="../admin.php">Annuleren</a>
    </form>
</body>

</html>
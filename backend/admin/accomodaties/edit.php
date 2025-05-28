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
    $stmt = $conn->prepare("UPDATE accommodaties SET name = :name, type = :type, location = :location, description = :description, photo_url = :photo_url, price = :price WHERE accommodation_id = :id");
    $stmt->execute([
        ':name' => $_POST['name'],
        ':type' => $_POST['type'],
        ':location' => $_POST['location'],
        ':description' => $_POST['description'],
        ':photo_url' => $_POST['photo_url'],
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
    <form method="post">
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

        <label>Foto URL:</label><br>
        <input name="photo_url" value="<?= htmlspecialchars($accommodation['photo_url']) ?>"><br><br>

        <label>Prijs:</label><br>
        <input name="price" type="number" step="0.01" value="<?= htmlspecialchars($accommodation['price']) ?>"
            required><br><br>

        <button type="submit">Opslaan</button>
        <a href="../admin.php">Annuleren</a>
    </form>
</body>

</html>
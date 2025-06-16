<?php
session_start();
require '../../conn.php';

if (!isset($_GET['id'])) {
    die("Geen boeking ID opgegeven.");
}
$id = (int)$_GET['id'];

// Ophalen van de huidige boeking
$stmt = $conn->prepare("SELECT * FROM boeking_accommodaties WHERE boeking_accommodatie_id = :id");
$stmt->execute([':id' => $id]);
$boeking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$boeking) {
    die("Boeking niet gevonden.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gebruik POST-data
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $aantal_personen = (int)$_POST['aantal_personen'];
    $total_price = floatval($_POST['total_price']);

    // Update de boeking
    $stmt = $conn->prepare("
        UPDATE boeking_accommodaties 
        SET 
            start_date = :start_date,
            end_date = :end_date,
            aantal_personen = :aantal_personen,
            total_price = :total_price
        WHERE boeking_accommodatie_id = :id
    ");
    $stmt->execute([
        ':start_date' => $start_date,
        ':end_date' => $end_date,
        ':aantal_personen' => $aantal_personen,
        ':total_price' => $total_price,
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
    <title>Boeking Bewerken</title>
</head>
<body>
    <h1>Boeking Bewerken</h1>
    <form method="post">
        <label for="start_date">Startdatum:</label><br>
        <input type="date" name="start_date" value="<?= htmlspecialchars($boeking['start_date']) ?>" required><br><br>

        <label for="end_date">Einddatum:</label><br>
        <input type="date" name="end_date" value="<?= htmlspecialchars($boeking['end_date']) ?>" required><br><br>

        <label for="aantal_personen">Aantal personen:</label><br>
        <input type="number" name="aantal_personen" min="1" value="<?= htmlspecialchars($boeking['aantal_personen']) ?>" required><br><br>

        <label for="total_price">Totale Prijs:</label><br>
        <input type="number" step="0.01" name="total_price" value="<?= htmlspecialchars($boeking['total_price']) ?>" required><br><br>

        <button type="submit">Opslaan</button>
        <a href="../admin.php">Annuleren</a>
    </form>
</body>
</html>
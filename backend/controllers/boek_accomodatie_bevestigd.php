<?php
session_start();
require 'databaseConnect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$boeking_id = $_GET['boeking_accommodatie_id'] ?? null;

if (!$boeking_id) {
    die("Geen boekings-ID opgegeven.");
}

try {
    // Haal de boeking op
    $stmt = $conn->prepare("
        SELECT 
            ba.*,
            a.name AS accommodation_name,
            a.location,
            a.type
        FROM boeking_accommodaties ba
        JOIN accommodaties a ON ba.accommodation_id = a.accommodation_id
        WHERE ba.boeking_accommodatie_id = ?
    ");
    $stmt->execute([$boeking_id]);
    $boeking = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$boeking) {
        throw new Exception("Boeking niet gevonden.");
    }

} catch (Exception $e) {
    die("Fout bij ophalen van boeking: " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Bevestiging Boeking</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="bevestiging-container">
        <h1>Je accommodatie is succesvol geboekt!</h1>
        <p><strong>Accommodatie:</strong> <?= htmlspecialchars($boeking['accommodation_name']) ?>
            (<?= htmlspecialchars($boeking['type']) ?>)</p>
        <p><strong>Locatie:</strong> <?= htmlspecialchars($boeking['location']) ?></p>
        <p><strong>Van:</strong> <?= date('d-m-Y', strtotime($boeking['start_date'])) ?></p>
        <p><strong>Tot:</strong> <?= date('d-m-Y', strtotime($boeking['end_date'])) ?></p>
        <p><strong>Aantal personen:</strong> <?= htmlspecialchars($boeking['aantal_personen']) ?></p>
        <p><strong>Totaalbedrag:</strong> &euro; <?= number_format($boeking['total_price'], 2, ',', '.') ?></p>

        <a href="../../frontend/templates/vakanties.php">Terug naar overzicht</a>
    </div>
</body>

</html>
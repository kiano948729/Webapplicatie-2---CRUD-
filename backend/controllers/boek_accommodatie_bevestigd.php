<?php
// Laad init.php
global $conn;
require_once __DIR__ . '/../../config/init.php';

// Controleer login
if (!isset($_SESSION['user_id'])) {
    header("Location: " . TEMPLATES_URL . "/login.php");
    exit();
}

// Haal boeking_id uit GET
$boeking_id = $_GET['boeking_accommodatie_id'] ?? null;

if (!$boeking_id) {
    die("Geen geldige boekings-ID opgegeven.");
}

try {
    // Haal boeking op
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
    // Foutmelding loggen
    error_log("Fout in boek_accommodatie_bevestigd.php: " . $e->getMessage());
    die("Er ging iets fout: " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Bevestiging Boeking</title>
    <link rel="stylesheet" href="<?= CSS_URL ?>/style.css">
</head>
<body>
<div class="bevestiging-container">
    <h1>✅ Je accommodatie is succesvol geboekt!</h1>

    <p><strong>Accommodatie:</strong> <?= htmlspecialchars($boeking['accommodation_name']) ?> (<?= htmlspecialchars($boeking['type']) ?>)</p>
    <p><strong>Locatie:</strong> <?= htmlspecialchars($boeking['location']) ?></p>
    <p><strong>Datum:</strong>
        <?= date('d-m-Y', strtotime($boeking['start_date'])) ?> -
        <?= date('d-m-Y', strtotime($boeking['end_date'])) ?>
    </p>
    <p><strong>Aantal personen:</strong> <?= htmlspecialchars($boeking['aantal_personen']) ?></p>
    <p><strong>Totaalbedrag:</strong> €<?= number_format($boeking['total_price'], 2, ',', '.') ?></p>

    <a href="<?= TEMPLATES_URL ?>/vakanties.php" class="terug-knop">Terug naar vakantieoverzicht</a>
</div>
</body>
</html>
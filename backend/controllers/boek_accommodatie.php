<?php
global $conn;
require_once __DIR__ . '/../../config/init.php';

// Controleer login
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Je moet ingelogd zijn om te boeken.";
    header("Location: " . TEMPLATES_URL . "/login.php");
    exit();
}

// Ontvang POST-data
$accommodation_id = $_POST['accommodation_id'] ?? null;
$start_date = $_POST['start_date'] ?? null;
$end_date = $_POST['end_date'] ?? null;
$aantal_personen = isset($_POST['aantal_personen']) ? (int)$_POST['aantal_personen'] : 1;

// Basisvalidatie
if (!$accommodation_id || !$start_date || !$end_date) {
    $_SESSION['error'] = "Ongeldige invoer. Vul alle velden in.";
    header("Location: " . TEMPLATES_URL . "/vakanties.php");
    exit();
}

if ($aantal_personen < 1) {
    $_SESSION['error'] = "Aantal personen moet minimaal 1 zijn.";
    header("Location: " . TEMPLATES_URL . "/vakanties.php");
    exit();
}

try {
    // Haal prijs op van accommodatie
    $stmt = $conn->prepare("SELECT price FROM accommodaties WHERE accommodation_id = ?");
    $stmt->execute([$accommodation_id]);
    $accommodation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$accommodation) {
        throw new Exception("Accommodatie niet gevonden.");
    }

    $price_per_night = $accommodation['price'];

    // Bereken aantal nachten
    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    $interval = $start->diff($end)->days;

    if ($interval <= 0) {
        throw new Exception("Einddatum moet na begindatum liggen.");
    }

    // Bereken totaalprijs (optioneel: extra toeslagen uit config)
    $toeslag_per_persoon = 25;
    $total_price = $interval * $price_per_night + ($aantal_personen * $toeslag_per_persoon);

    // Voeg boeking toe
    $stmt = $conn->prepare("
        INSERT INTO boeking_accommodaties 
        (user_id, accommodation_id, start_date, end_date, total_price, aantal_personen) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $_SESSION['user_id'],
        $accommodation_id,
        $start_date,
        $end_date,
        $total_price,
        $aantal_personen
    ]);

    // Haal ID van nieuwe boeking op
    $boeking_id = $conn->lastInsertId();

    // Stuur door naar bevestigingspagina
    header("Location: " . CONTROLLERS_URL . "/boek_accommodatie_bevestigd.php?boeking_accommodatie_id=" . $boeking_id);
    exit();

} catch (Exception $e) {
    error_log("Boekfout: " . $e->getMessage());
    $_SESSION['error'] = "Er ging iets mis bij het boeken: " . htmlspecialchars($e->getMessage());
    header("Location: " . TEMPLATES_URL . "/vakanties.php");
    exit();
}
?>
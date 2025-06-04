<?php
session_start();
require 'databaseConnect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$accommodation_id = $_POST['accommodation_id'] ?? null;
$start_date = $_POST['start_date'] ?? null;
$end_date = $_POST['end_date'] ?? null;
$aantal_personen = isset($_POST['aantal_personen']) ? (int) $_POST['aantal_personen'] : 1;

if ($aantal_personen < 1) {
    die("Aantal personen moet minstens 1 zijn.");
}

if (!$accommodation_id || !$start_date || !$end_date) {
    die("Ongeldige invoer.");
}

try {
    // Haal prijs per nacht op van de accommodatie
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
        throw new Exception("Einddatum moet na begindatum zijn.");
    }

    $total_price = $interval * $price_per_night + ($aantal_personen * 25); // kleine toeslag per persoon

    // Voeg boeking toe aan 'boeking_accommodaties'
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


    $boeking_id = $conn->lastInsertId();

    header("Location: boek_accomodatie_bevestigd.php?boeking_accommodatie_id=" . $boeking_id);
    exit();

} catch (Exception $e) {
    echo "Er ging iets mis tijdens het boeken van de accommodatie: " . htmlspecialchars($e->getMessage());
}

<?php
session_start();
require 'databaseConnect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$deal_id = $_POST['deal_id'] ?? null;
$start_date = $_POST['start_date'] ?? null;
$end_date = $_POST['end_date'] ?? null;
$aantal_personen = isset($_POST['aantal_personen']) ? (int) $_POST['aantal_personen'] : 1;
if ($aantal_personen < 1) {
    die("Aantal personen moet minstens 1 zijn.");
}

if (!$deal_id || !$start_date || !$end_date) {
    die("Ongeldige invoer.");
}

try {
    // Begin transactie
    $conn->beginTransaction();

    // Haal dealprijs op
    $stmt = $conn->prepare("SELECT price FROM vakantie_deals WHERE deal_id = ?");
    $stmt->execute([$deal_id]);
    $deal = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$deal) {
        throw new Exception("Deal niet gevonden.");
    }

    $total_price = $deal['price'] + ($aantal_personen * 100);

    // Voeg boeking toe
    $stmt = $conn->prepare("
    INSERT INTO boekingen (user_id, deal_id, start_date, end_date, total_price, aantal_personen)
    VALUES (?, ?, ?, ?, ?, ?)
");
    $stmt->execute([
        $_SESSION['user_id'],
        $deal_id,
        $start_date,
        $end_date,
        $total_price,
        $aantal_personen
    ]);


    $booking_id = $conn->lastInsertId();

    // Update deal als geboekt
    $stmt = $conn->prepare("UPDATE vakantie_deals SET is_booked = TRUE WHERE deal_id = ?");
    $stmt->execute([$deal_id]);

    // Commit transactie
    $conn->commit();
    
    header("Location: boek_deal_bevestigd.php?booking_id=" . $booking_id);
    exit();

} catch (Exception $e) {
    $conn->rollBack();
    echo "Er ging iets mis tijdens het boeken: " . htmlspecialchars($e->getMessage());
}
?>
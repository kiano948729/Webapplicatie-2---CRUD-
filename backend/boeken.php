<?php
session_start();
require 'databaseConnect.php'; // Of je eigen PDO connectie

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$deal_id = $_POST['deal_id'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

try {
    // Begin transaction
    $conn->beginTransaction();

    // Haal prijs uit deal
    $stmt = $conn->prepare("SELECT price FROM vakantie_deals WHERE deal_id = ?");
    $stmt->execute([$deal_id]);
    $deal = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_price = $deal['price'];

    // Voeg boeking toe
    $stmt = $conn->prepare("
        INSERT INTO boekingen (user_id, deal_id, start_date, end_date, total_price)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $_SESSION['user_id'],
        $deal_id,
        $start_date,
        $end_date,
        $total_price
    ]);

    // Markeer deal als geboekt
    $stmt = $conn->prepare("UPDATE vakantie_deals SET is_booked = TRUE WHERE deal_id = ?");
    $stmt->execute([$deal_id]);

    $conn->commit();

    // Redirect naar succespagina
    header("Location: ../boeking_bevestigd.php?booking_id=" . $conn->lastInsertId());
    exit();

} catch (PDOException $e) {
    $conn->rollBack();
    echo "Er ging iets mis: " . $e->getMessage();
}
?>
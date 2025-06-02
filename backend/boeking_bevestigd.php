<?php
session_start();
$booking_id = $_GET['booking_id'] ?? null;

if (!$booking_id) {
    echo "Geen geldige boeking gevonden.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Boeking bevestigd</title>
</head>
<body>
    <h1>Bedankt voor je boeking!</h1>
    <p>Je boeking-ID is: <strong><?= $booking_id ?></strong></p>
    <p>We hebben een bevestiging gestuurd naar je e-mailadres.</p>
    <a href="../vakantie.php">Terug naar homepage</a>
</body>
</html>
<?php
session_start();
require '../../conn.php';

if (!isset($_GET['id'])) {
    die("Geen boeking ID opgegeven.");
}
$id = (int)$_GET['id'];

// Controleer of de boeking bestaat
$stmt = $conn->prepare("SELECT * FROM boeking_accommodaties WHERE boeking_accommodatie_id = :id");
$stmt->execute([':id' => $id]);
$boeking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$boeking) {
    die("Boeking niet gevonden.");
}

// Boeking verwijderen
$stmt = $conn->prepare("DELETE FROM boeking_accommodaties WHERE boeking_accommodatie_id = :id");
$stmt->execute([':id' => $id]);

header("Location: ../admin.php");
exit;
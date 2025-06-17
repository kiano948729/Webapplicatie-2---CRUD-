<?php
// Verbind met de database
require_once __DIR__ . '/../../config/init.php';
// Query om bestemmingen uit de database op te halen
$sql = "SELECT * FROM boeking_accommodaties";

$stmt = $conn->prepare($sql);
$stmt->execute();
$accommodatieBoekingen = $stmt->fetchAll(PDO::FETCH_ASSOC);

// echo json_encode($results);
?>

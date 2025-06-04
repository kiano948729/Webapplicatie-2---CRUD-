<?php
// Verbind met de database
require 'conn.php';

// Query om bestemmingen uit de database op te halen
$sql = "SELECT * FROM boekingen";

$stmt = $conn->prepare($sql);
$stmt->execute();
$boekingen = $stmt->fetchAll(PDO::FETCH_ASSOC);

// echo json_encode($results);
?>

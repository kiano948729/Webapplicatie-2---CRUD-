<?php
// Verbind met de database
require 'conn.php';

// Controleer of er een sorteerparameter is meegegeven
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

// Standaard query zonder sortering
$sql = "SELECT * FROM accommodaties";

// Voeg sortering toe als nodig
if ($sort === 'low_to_high') {
    $sql .= " ORDER BY price ASC";
} elseif ($sort === 'high_to_low') {
    $sql .= " ORDER BY price DESC";
}

// Voer de query uit
$stmt = $conn->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
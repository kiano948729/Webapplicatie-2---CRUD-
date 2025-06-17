<?php
// Toon PHP-fouten voor debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Laad init.php
require_once __DIR__ . '/../../config/init.php';

// Voorbeeld debug (verwijder later)
// print_r($_SESSION);

// Query om boekingen + foto te halen
$sql = "
    SELECT 
        ba.boeking_accommodatie_id,
        ba.user_id,
        ba.accommodation_id,
        ba.start_date,
        ba.end_date,
        ba.aantal_personen,
        ba.total_price,
        a.name AS accommodation_name,
        a.photo_url,
        a.location,
        a.type
    FROM boeking_accommodaties ba
    JOIN accommodaties a ON ba.accommodation_id = a.accommodation_id
    WHERE 1=1
";

$params = [];

// Optioneel: filter op ingelogde gebruiker
if (isset($_SESSION['user_id'])) {
    $sql .= " AND ba.user_id = :user_id";
    $params[':user_id'] = $_SESSION['user_id'];
}

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $accommodatieBoekingen = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Fout bij ophalen boekingen: " . $e->getMessage());
    $accommodatieBoekingen = [];
}
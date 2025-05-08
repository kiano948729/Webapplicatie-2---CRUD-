<?php
// Verbind met de database
require 'conn.php';

// Query om bestemmingen uit de database op te halen
$sql = "SELECT vd.destination, vd.photo_url, vd.price, a.location 
        FROM vakantie_deals vd 
        JOIN accommodaties a ON vd.accommodation_id = a.accommodation_id
        LIMIT 8";  // Beperk het aantal bestemmingen dat wordt opgehaald

$stmt = $conn->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($results);
?>

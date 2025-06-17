<?php
// Laad centrale configuratie
global $conn;
require_once __DIR__ . '/../../config/init.php';

// Query om alleen goedgekeurde recensies op te halen
$sql = "
    SELECT 
        r.accommodation_id,
        r.comment,
        r.rating,
        r.review_date,
        u.username
    FROM accommodatie_reviews r
    JOIN users u ON r.user_id = u.user_id
    WHERE r.approved = 1
";

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $accommodatie_reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Optioneel: Geef JSON terug als het bestand direct wordt opgeroepen via AJAX
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode($accommodatie_reviews);
        exit;
    }

} catch (PDOException $e) {
    // Foutmelding loggen (niet tonen aan eindgebruiker)
    error_log("Databasefout in fetch_reviews.php: " . $e->getMessage());
    // Optioneel: JSON-fout retourneren bij AJAX
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Er ging iets mis bij het ophalen van recensies.']);
        exit;
    }
}
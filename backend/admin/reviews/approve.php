<?php
require '../../conn.php';
$review_id = $_GET['id'] ?? null;

if ($review_id) {
    $stmt = $conn->prepare("UPDATE accommodatie_reviews SET approved = 1 WHERE review_id = ?");
    $stmt->execute([$review_id]);
}

header("Location: ../admin.php"); // Terug naar overzichtspagina
exit;
?>
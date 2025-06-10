<?php
session_start();
require '../../conn.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    die("Je hebt geen toestemming om deze actie uit te voeren.");
}

require '../../conn.php';

$review_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($review_id <= 0) {
    die("Ongeldig recensie ID.");
}

try {
    // Verwijder de recensie uit de database
    $stmt = $conn->prepare("DELETE FROM accommodatie_reviews WHERE review_id = ?");
    $stmt->execute([$review_id]);

    header("Location: ../admin.php");
    exit();
} catch (PDOException $e) {
    die("Fout bij verwijderen: " . $e->getMessage());
}
?>
<?php
global $conn;
session_start();
require_once __DIR__ . '/../../config/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $accommodation_id = intval($_POST['accommodation_id']);
    $rating = intval($_POST['rating']);
    $comment = trim($_POST['comment']);

    if ($rating < 1 || $rating > 5) {
        die("Ongeldige beoordeling.");
    }

    if (empty($comment)) {
        die("Recensie mag niet leeg zijn.");
    }

    // Verander $pdo naar $conn als dat je verbindingsvariabele is
    $stmt = $conn->prepare("INSERT INTO accommodatie_reviews (user_id, accommodation_id, rating, comment) VALUES (?, ?, ?, ?)");
    $success = $stmt->execute([$user_id, $accommodation_id, $rating, $comment]);

    if ($success) {
        $_SESSION['review_submitted'] = "Je recensie is succesvol ingediend en wordt beoordeeld door de administrator.";
    } else {
        $_SESSION['review_submitted'] = "Er is een fout opgetreden bij het indienen van je recensie.";
    }
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
} else {
    echo "Je moet ingelogd zijn om een recensie te plaatsen.";
}
?>
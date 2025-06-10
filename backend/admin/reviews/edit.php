<?php
session_start();
require '../../conn.php';


// Controleer of gebruiker admin is
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    die("Je hebt geen toestemming om deze pagina te bekijken.");
}

// Controleer of er een review_id is meegegeven
if (!isset($_GET['id'])) {
    die("Geen recensie ID opgegeven.");
}

$review_id = (int)$_GET['id'];

// Haal de recensie op uit de database
$stmt = $conn->prepare("
    SELECT r.*, u.username, a.name AS accommodation_name 
    FROM accommodatie_reviews r
    INNER JOIN users u ON r.user_id = u.user_id
    INNER JOIN accommodaties a ON r.accommodation_id = a.accommodation_id
    WHERE r.review_id = :id
");
$stmt->execute([':id' => $review_id]);
$review = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$review) {
    die("Recensie niet gevonden.");
}

// Verwerk POST-verzoek (formulier-opslag)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update':
                // Bewerk comment en rating
                $stmt = $conn->prepare("UPDATE accommodatie_reviews SET comment = :comment, rating = :rating WHERE review_id = :id");
                $stmt->execute([
                    ':comment' => $_POST['comment'],
                    ':rating' => (int)$_POST['rating'],
                    ':id' => $review_id
                ]);
                break;

            case 'delete':
                // Verwijder de recensie
                $stmt = $conn->prepare("DELETE FROM accommodatie_reviews WHERE review_id = :id");
                $stmt->execute([':id' => $review_id]);
                header("Location: admin_recensies.php");
                exit;

            case 'approve':
                // Zet approved op 1
                $stmt = $conn->prepare("UPDATE accommodatie_reviews SET approved = 1 WHERE review_id = :id");
                $stmt->execute([':id' => $review_id]);
                header("Location: review_edit.php?id=$review_id");
                exit;

            case 'disapprove':
                // Zet approved op 0
                $stmt = $conn->prepare("UPDATE accommodatie_reviews SET approved = 0 WHERE review_id = :id");
                $stmt->execute([':id' => $review_id]);
                header("Location: review_edit.php?id=$review_id");
                exit;
        }
    }

    // Herlaad de pagina met bijgewerkte data
    header("Location: review_edit.php?id=$review_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Recensie Bewerken</title>
</head>
<body>

    <h1>Recensie Bewerken</h1>

    <p><strong>Gebruiker:</strong> <?= htmlspecialchars($review['username']) ?></p>
    <p><strong>Accommodatie:</strong> <?= htmlspecialchars($review['accommodation_name']) ?></p>
    <p><strong>Datum:</strong> <?= date('d-m-Y H:i', strtotime($review['review_date'])) ?></p>
    <p><strong>Status:</strong>
        <?php if ($review['approved']): ?>
            <span class="status approved">Goedgekeurd</span>
        <?php else: ?>
            <span class="status pending">In afwachting</span>
        <?php endif; ?>
    </p>

    <form method="post">
        <label>Beoordeling (1-5):</label>
        <select name="rating" required>
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <option value="<?= $i ?>" <?= $i == $review['rating'] ? 'selected' : '' ?>>
                    <?= $i ?> ster<?= $i != 1 ? 'ren' : '' ?>
                </option>
            <?php endfor; ?>
        </select>

        <label>Opmerking:</label>
        <textarea name="comment"><?= htmlspecialchars($review['comment']) ?></textarea>

        <br><br>
        <button type="submit">Wijzigen</button>
        <input type="hidden" name="action" value="update">
    </form>

    <form method="post" style="margin-top: 20px;">
        <input type="hidden" name="action" value="delete">
        <button type="submit" onclick="return confirm('Weet je zeker dat je deze recensie wilt verwijderen?')">Verwijderen</button>
    </form>

    <div style="margin-top: 20px;">
        <?php if ($review['approved']): ?>
            <form method="post">
                <input type="hidden" name="action" value="disapprove">
                <button type="submit">Stop goedkeuring</button>
            </form>
        <?php else: ?>
            <form method="post">
                <input type="hidden" name="action" value="approve">
                <button type="submit">Goedkeuren</button>
            </form>
        <?php endif; ?>
    </div>

    <br>
    <a href="admin_recensies.php">Terug naar overzicht</a>

</body>
</html>
<?php
session_start();
require '../../conn.php';

if (!isset($_GET['id'])) {
    die("Geen ID opgegeven.");
}
$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM boekingen WHERE booking_id = :id");
$stmt->execute([':id' => $id]);
$boeking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$boeking) {
    die("Boeking niet gevonden.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("UPDATE boekingen SET user_id = :user_id, deal_id = :deal_id, start_date = :start_date, end_date = :end_date, total_price = :total_price WHERE booking_id = :id");
    $stmt->execute([
        ':user_id' => $_POST['user_id'],
        ':deal_id' => $_POST['deal_id'],
        ':start_date' => $_POST['start_date'],
        ':end_date' => $_POST['end_date'],
        ':total_price' => $_POST['total_price'],
        ':id' => $id
    ]);
    header("Location: ../admin.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Boeking Bewerken</title>
</head>

<body>
    <h1>Boeking Bewerken</h1>
    <form method="post">
        <label>User ID:</label><br>
        <input name="user_id" value="<?= htmlspecialchars($boeking['user_id']) ?>" required><br><br>

        <label>Deal ID:</label><br>
        <input name="deal_id" value="<?= htmlspecialchars($boeking['deal_id']) ?>" required><br><br>

        <label>Startdatum:</label><br>
        <input type="date" name="start_date" value="<?= htmlspecialchars($boeking['start_date']) ?>" required><br><br>

        <label>Einddatum:</label><br>
        <input type="date" name="end_date" value="<?= htmlspecialchars($boeking['end_date']) ?>" required><br><br>

        <label>Totale prijs (&euro;):</label><br>
        <input type="number" step="0.01" name="total_price" value="<?= htmlspecialchars($boeking['total_price']) ?>"
            required><br><br>

        <button type="submit">Opslaan</button>
        <a href="../admin.php">Annuleren</a>
    </form>
</body>

</html>
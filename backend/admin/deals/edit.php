<?php
session_start();
require '../../conn.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM vakantie_deals WHERE deal_id = :id");
$stmt->execute([':id' => $id]);
$deal = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "UPDATE vakantie_deals SET destination = :destination, description = :description, price = :price, backpacking_type = :type, photo_url = :photo WHERE deal_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':destination' => $_POST['destination'],
        ':description' => $_POST['description'],
        ':price' => $_POST['price'],
        ':type' => $_POST['type'],
        ':photo' => $_POST['photo_url'],
        ':id' => $id
    ]);
    header("Location: ../admin.php");
    exit;
}
?>

<form method="post">
    <input name="destination" value="<?= $deal['destination'] ?>"><br>
    <textarea name="description"><?= $deal['description'] ?></textarea><br>
    <input type="number" name="price" value="<?= $deal['price'] ?>"><br>
    <select name="type">
        <option value="natuur" <?= $deal['backpacking_type'] === 'natuur' ? 'selected' : '' ?>>Natuur</option>
        <option value="stedelijk" <?= $deal['backpacking_type'] === 'stedelijk' ? 'selected' : '' ?>>Stedelijk</option>
    </select><br>
    <input name="photo_url" value="<?= $deal['photo_url'] ?>"><br>
    <button type="submit">Bijwerken</button>
</form>

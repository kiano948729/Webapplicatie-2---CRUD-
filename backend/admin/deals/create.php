<?php
session_start();
require '../../conn.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $sql = "INSERT INTO vakantie_deals (destination, description, price, backpacking_type, photo_url, created_by_admin)
            VALUES (:destination, :description, :price, :type, :photo, :admin_id)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':destination' => $_POST['destination'],
        ':description' => $_POST['description'],
        ':price' => $_POST['price'],
        ':type' => $_POST['type'],
        ':photo' => $_POST['photo_url'],
        ':admin_id' => 1 // hardcoded of uit sessie
    ]);
    header("Location: ../admin.php");
    exit;
}
?>

<form method="post">
    <input name="destination" placeholder="Bestemming" required><br>
    <textarea name="description" placeholder="Beschrijving" required></textarea><br>
    <input type="number" name="price" placeholder="Prijs" required><br>
    <select name="type">
        <option value="natuur">Natuur</option>
        <option value="stedelijk">Stedelijk</option>
    </select><br>
    <input name="photo_url" placeholder="Foto URL"><br>
    <button type="submit">Opslaan</button>
</form>

<?php
session_start();
require '../../conn.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM ContactBerichten WHERE Id = :id");
$stmt->execute([':id' => $id]);
$deal = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "UPDATE ContactBerichten SET Naam = :Naam, Achternaam = :Achternaam, Email = :Email, Nummer = :Nummer, Bericht = :Bericht WHERE Id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':Naam' => $_POST['Naam'],
        ':Achternaam' => $_POST['Achternaam'],
        ':Email' => $_POST['Email'],
        ':Nummer' => $_POST['Nummer'],
        ':Bericht' => $_POST['Bericht'],
        ':id' => $id
    ]);
    header("Location: ../admin.php");
    exit;
}
?>

<form method="post">
    <input name="Naam" value="<?= $deal['Naam'] ?>"><br>
    <input name="Achternaam" value="<?= $deal['Achternaam'] ?>"><br>
    <input name="Email" value="<?= $deal['Email'] ?>"><br>
    <input name="Nummer" value="<?= $deal['Nummer'] ?>"><br>
    <input name="Bericht" value="<?= $deal['Bericht'] ?>"><br>
    <button type="submit">Bijwerken</button>
</form>

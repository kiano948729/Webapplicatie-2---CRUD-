<?php
global $conn;
$current_page = basename($_SERVER['PHP_SELF']);
?>
<?php
session_start();
require_once 'backend/databaseConnect.php';
require 'backend/databaseConnect.php';
require 'backend/conn.php';
include 'backend/fetch_bestemmingen.php';
include 'backend/fetch_deals.php';
// Controleer of er een ingelogde gebruiker is
$current_user = null;
if (isset($_SESSION['user_id'])) {
    $query = "SELECT username FROM users WHERE user_id = :id";
    $statement = $conn->prepare($query);
    $statement->execute([':id' => $_SESSION['user_id']]);
    $current_user = $statement->fetch(PDO::FETCH_ASSOC)['username'];
}
try {
    $connection = new PDO("mysql:host=webabb2;dbname=reizen;charset=utf8mb4", "root", "rootpassword", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage());
}

if (isset($_POST["Registreren-Knop"])) {
    $sql = "INSERT INTO ContactBerichten (Naam, Achternaam, Email, Nummer, Bericht)
            VALUES (:Naam, :Achternaam, :Email, :Nummer, :Bericht)";
    $statement = $connection->prepare($sql);
    $statement->bindParam(":Naam", $_POST['Naam']);
    $statement->bindParam(":Achternaam", $_POST['Achternaam']);
    $statement->bindParam(":Email", $_POST['Email']);
    $statement->bindParam(":Nummer", $_POST['Nummer']);
    $statement->bindParam(":Bericht", $_POST['Bericht']);
    $statement->execute();
    echo "<script>alert('Bericht verzonden');</script>";
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google" content="notranslate">
    <title>Vakanties Boeken | Naam</title>
    <link rel="stylesheet" href="../public/css/JoeStyle.css">
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="icon" href="../img/CompassLogo.png" type="Images/png">
    <script src="https://kit.fontawesome.com/5321476408.js" crossorigin="anonymous"></script>
    <script src="script.js" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap"
          rel="stylesheet">
</head>

<body>
<div class="Afbeelding-Achtergrond-Login">
    <header>
        <?php require_once("components/header.php") ?>
    </header>
    <main>
        <div class="loginFrameRij">
            <div class="contactFrame">
                <div class="handLogoFrame">
                    <i id="TelefoonLogo" class="fa-solid fa-phone"></i>
                </div>
                <div class="textFrameLogin">
                    <h1 class="grijsText">Contact ons</h1>
                </div>

                <form action="Contact.php" method="post">
                    <div class="contactRijNaam">
                        <input class="contactInputNaam"  name="Naam" placeholder="Naam" type="text">
                        <input class="contactInputNaam" name="Achternaam" placeholder="Achternaam" type="text">
                    </div>
                    <div class="contactRijNaam">
                        <input class="contactInputNaam" name="Email" placeholder="Email" type="text">
                        <input class="contactInputNaam" type="number" name="Nummer" placeholder="Telefoon nummer">
                    </div>
                    <div class="contactRijNaam">
                        <input class="contactInputBericht" name="Bericht" placeholder="Bericht" type="text">
                    </div>
                    <button class="filter-knop" name="Registreren-Knop" type="submit">
                        <h2 class="Witte-Text">Verstuur</h2>
                    </button>
                </form>
            </div>
    </main>
    <footer>
    </footer>
</body>
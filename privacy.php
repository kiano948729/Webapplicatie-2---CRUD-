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
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google" content="notranslate">
    <title>Over ons | Backpack & go</title>
    <link rel="stylesheet" href="css/JoeStyle.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="../img/CompassLogo.png" type="Images/png">
    <script src="https://kit.fontawesome.com/5321476408.js" crossorigin="anonymous"></script>
    <script src="script.js" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
</head>
<body>
<header>
    <?php require_once("components/header.php") ?>
</header>
<main class="privacyMain">
    <div class="achtergrondOverOns">
        <h1 class="Witte-Text">Privacy</h1>
    </div>
    <div class="centerTextDiv">
        <p>Ons platform Backpack en go is betrokken om u informatie te beschermen, hieronder vind u informatie over wat voor informatie we verzamelen en wat we daarmee doen.</p>
    </div>
    <div class="splitDivRij">
        <img class="afbeeldingDivLinks" src="img/cyber-security-concept_23-2148532223.avif">
        <div class="textColumnDiv">
            <h1 class="boldText">Wat voor informatie word gebruikt?</h1>
            <p>Backpack & go gebruikt informatie zoals:</p>
            <ol>
                <li>Emails</li>
                <li>Gebruikers namen</li>
                <li>Wachtwoorden</li>
                <li>Andere informatie die u aan u account kan koppelen zoals</li>
                <li>achternamen en geboortedatums</li>
            </ol>
        </div>
    </div>
    <div class="splitDivRijTwee">
        <div class="textColumnDiv">
            <h1 class="boldText">Waar word de informatie voor gebruikt?</h1>
            <p class="centerText">Wij gebruiken de informatie als volgt:</p>
            <ol>
                <li>
                    Account gegevens vergelijken: Om de gebruikers informatie zoals wachtwoorden en namen te vergelijken om u in te kunnen loggen.
                </li>
                <li>
                    Communicatie: Om te communiceren met u via u email als u vragen heeft over onze service.
                </li>
                <li>
                    Service verbeteren: Als u bijvoorbeeld reviews achterlaat of andere feedback dan kunnen wij die feedback gebruiken om onze service te verbeteren.
                </li>
            </ol>
        </div>
        <img class="afbeeldingDivLinks" src="img/flat-background-safer-internet-day_23-2151127509.avif">
    </div>
</main>
</body>
</html>

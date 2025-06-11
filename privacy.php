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
        <h1 class="Witte-Text">Over ons</h1>
    </div>
    <div class="centerTextDiv">
        <p>Als een backpack vakantiebureau platform specialiseren wij in het maken van een platform die makkelijk is om te gebruiken waar je backpack vakanties kan boeken</p>
    </div>
    <div class="splitDivRij">
        <img class="afbeeldingDivLinks" src="img/imageMain.png">
        <div class="textColumnDiv">
            <h1 class="boldText">Wat is backpack & go?</h1>
            <p>Backpack & go is een backpacking vakantie service waar als gebruiker u een backpack vakantie reis kan boeken via onze service.</p>
        </div>
    </div>
    <div class="splitDivRijTwee">
        <div class="textColumnDiv">
            <h1 class="boldText">Waarom Backpack & go?</h1>
            <p class="centerText">Backpack & go is een service die constant word verbeterd en dat komt voornamelijk door feedback die wij ontvangen van onze gebruikers.<a class="blauwText" href="Contact.php">als u feedback wilt geven over onze service klik dan hier</a> </p>
        </div>
        <img class="afbeeldingDivLinks" src="img/imageBerg.png">
    </div>
</main>
</body>
</html>

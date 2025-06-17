<?php
// Laad centrale configuratie
require_once __DIR__ . '/../../config/init.php';

// $current_user is al beschikbaar via init.php
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Over ons | Backpack & go</title>

    <!-- CSS -->
    <link rel="stylesheet" href="<?= CSS_URL ?>/JoeStyle.css">
    <link rel="stylesheet" href="<?= CSS_URL ?>/style.css">
    <link rel="icon" href="<?= IMG_URL ?>/CompassLogo.png" type="image/png">

    <!-- Icons -->
    <script src="https://kit.fontawesome.com/5321476408.js"  crossorigin="anonymous"></script>
    <script src="<?= JS_URL ?>/jsK.js" defer></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com"  crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
</head>
<body>
<header>
    <?php include PUBLIC_PATH . '/components/header.php'; ?>
</header>

<main class="privacyMain">
    <div class="achtergrondOverOns">
        <h1 class="Witte-Text">Over ons</h1>
    </div>
    <div class="centerTextDiv">
        <p>Als een backpack vakantiebureau platform specialiseren wij in het maken van een platform die makkelijk is om te gebruiken waar je backpack vakanties kan boeken.</p>
    </div>
    <div class="splitDivRij">
        <img class="afbeeldingDivLinks" src="<?= IMG_URL ?>/imageMain.png" alt="Backpack reizen">
        <div class="textColumnDiv">
            <h1 class="boldText">Wat is Backpack & Go?</h1>
            <p>Backpack & Go is een backpacking vakantie service waar jij als gebruiker een backpack vakantie kunt boeken via onze gebruiksvriendelijke webapplicatie.</p>
        </div>
    </div>
    <div class="splitDivRijTwee">
        <div class="textColumnDiv">
            <h1 class="boldText">Waarom Backpack & Go?</h1>
            <p class="centerText">Backpack & Go is een service die continu verbeterd wordt aan de hand van feedback van onze gebruikers.
                <a class="blauwText" href="<?= TEMPLATES_URL ?>/Contact.php">Klik hier om feedback te geven</a>.
            </p>
        </div>
        <img class="afbeeldingDivLinks" src="<?= IMG_URL ?>/imageBerg.png" alt="Berglandschap">
    </div>
</main>
</body>
</html>
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
        <h1 class="Witte-Text">Privacy</h1>
    </div>
    <div class="centerTextDiv">
        <p>Ons platform Backpack en go is betrokken om u informatie te beschermen, hieronder vind u informatie over wat voor informatie we verzamelen en wat we daarmee doen.</p>
    </div>
    <div class="splitDivRij">
        <img class="afbeeldingDivLinks" src="<?= IMG_URL ?>/cyber-security-concept_23-2148532223.avif" alt="Cybersecurity">
        <div class="textColumnDiv">
            <h1 class="boldText">Wat voor informatie word gebruikt?</h1>
            <p>We verzamelen de volgende gegevens:</p>
            <ol>
                <li>Emails</li>
                <li>Gebruikersnamen</li>
                <li>Wachtwoorden (hash)</li>
                <li>Achternamen</li>
                <li>Geboortedatums</li>
            </ol>
        </div>
    </div>
    <div class="splitDivRijTwee">
        <div class="textColumnDiv">
            <h1 class="boldText">Waar wordt de informatie voor gebruikt?</h1>
            <p class="centerText">Wij gebruiken de informatie als volgt:</p>
            <ol>
                <li><strong>Account gegevens vergelijken:</strong> Om je in te loggen.</li>
                <li><strong>Communicatie:</strong> Contact opnemen via e-mail.</li>
                <li><strong>Service verbeteren:</strong> Feedback gebruiken om beter te worden.</li>
            </ol>
        </div>
        <img class="afbeeldingDivLinks" src="<?= IMG_URL ?>/flat-background-safer-internet-day_23-2151127509.avif" alt="Privacy">
    </div>
</main>
</body>
</html>
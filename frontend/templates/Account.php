<?php
// Laad centrale configuratie
require_once __DIR__ . '/../../config/init.php';

// Redirect als gebruiker niet is ingelogd
if (!isset($_SESSION['user_id'])) {
    header("Location: VeranderGegevens.php");
    exit();
}

// Gebruik $conn uit init.php
global $conn;

// Haal gebruikersinfo op
$current_user = null;
$registration_date = null;
$user_id = null;
$lastName = null;
$dateOfBirth = null;
$email = null;

if (isset($_SESSION['user_id'])) {
    $query = "SELECT username, registration_date, user_id, email, lastName, dateOfBirth FROM users WHERE user_id = :id";
    $statement = $conn->prepare($query);
    $statement->execute([':id' => $_SESSION['user_id']]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $current_user = $user['username'];
        $registration_date = $user['registration_date'];
        $user_id = $user['user_id'];
        $lastName = $user['lastName'];
        $dateOfBirth = $user['dateOfBirth'];
        $email = $user['email'];
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account | <?= htmlspecialchars($current_user ?? 'Naam') ?></title>

    <!-- CSS -->
    <link rel="stylesheet" href="<?= CSS_URL ?>/style.css">
    <link rel="stylesheet" href="<?= CSS_URL ?>/JoeStyle.css">

    <!-- Favicon -->
    <link rel="icon" href="<?= IMG_URL ?>/CompassLogo.png" type="image/png">

    <!-- Icons -->
    <script src="https://kit.fontawesome.com/5321476408.js"  crossorigin="anonymous"></script>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>
<header>
    <?php include PUBLIC_PATH . '/components/header.php'; ?>
</header>

<main>
    <div class="accountRij">
        <div class="accountLogoFrame">
            <img class="afbeeldingAccount" src="<?= IMG_URL ?>/profile-icon-9.png" alt="Profiel afbeelding" />
        </div>
        <?php if ($current_user): ?>
            <h1><?= htmlspecialchars($current_user) ?></h1>
        <?php endif; ?>
    </div>

    <div class="persoonlijkeInformatieRij">
        <div class="topRijInfo">
            <h1 class="informatieTitel">Persoonlijke Informatie</h1>
            <a href="VeranderGegevens.php">
                <button class="filter-knop-account" type="button">
                    <h2 class="Witte-Text">Bewerk</h2>
                    <i id="Icon" class="fa-solid fa-pen-to-square"></i>
                </button>
            </a>
        </div>

        <div class="tweedeRijInfo">
            <div class="informatieFrame">
                <h2 class="grijsText">Gebruikersnaam</h2>
                <h2><?= htmlspecialchars($current_user ?: 'Onbekend') ?></h2>
            </div>
            <div class="informatieFrame">
                <h2 class="grijsText">Achternaam</h2>
                <h2><?= htmlspecialchars($lastName ?: 'Onbekend') ?></h2>
            </div>
            <div class="informatieFrame">
                <h2 class="grijsText">Geboortedatum</h2>
                <h2><?= htmlspecialchars($dateOfBirth ?: 'Onbekend') ?></h2>
            </div>
        </div>

        <div class="tweedeRijInfo">
            <div class="informatieFrame">
                <h2 class="grijsText">Email</h2>
                <h2><?= htmlspecialchars($email ?: 'Onbekend') ?></h2>
            </div>
            <div class="informatieFrame">
                <h2 class="grijsText">Registratiedatum</h2>
                <h2><?= htmlspecialchars($registration_date ?: 'Onbekend') ?></h2>
            </div>
            <div class="informatieFrame">
                <h2 class="grijsText">GebruikerID</h2>
                <h2><?= htmlspecialchars($user_id ?: 'Onbekend') ?></h2>
            </div>
        </div>
    </div>
</main>
<footer></footer>
</body>
</html>
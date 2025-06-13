<?php
session_start();
require_once 'backend/databaseConnect.php';
global $conn;
if (!isset($_SESSION["Gebruiker"])) {
    header("Location: Index.php");
    exit();
}

$current_user = null;
$registration_date = null;
$user_id = null;

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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="google" content="notranslate" />
    <title>Account | <?php echo htmlspecialchars($current_user ?? 'Naam'); ?></title>
    <link rel="stylesheet" href="css/JoeStyle.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="icon" href="../img/CompassLogo.png" type="image/png" />
    <script src="https://kit.fontawesome.com/5321476408.js" crossorigin="anonymous"></script>
    <script src="script.js" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
            href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap"
            rel="stylesheet"
    />
</head>

<body>
<header>
    <?php require_once("components/header.php") ?>
</header>
<main>
    <div class="accountRij">
        <div class="accountLogoFrame">
            <img class="afbeeldingAccount" src="img/profile-icon-9.png" alt="Profiel afbeelding" />
        </div>
        <?php if ($current_user): ?>
            <h1><?php echo htmlspecialchars($current_user); ?></h1>
        <?php endif; ?>
    </div>

    <div class="persoonlijkeInformatieRij">
        <div class="topRijInfo">
            <h1 class="informatieTitel">Persoonlijke Informatie</h1>
            <a href="VeranderGegevens.php">
                <button class="filter-knop-account" name="Registreren-Knop" type="submit">
                    <h2 class="Witte-Text">Bewerk</h2>
                    <i id="Icon" class="fa-solid fa-pen-to-square"></i>
                </button>
            </a>


        </div>
        <div class="tweedeRijInfo">
            <div class="informatieFrame">
                <h2 class="grijsText">Gebruikersnaam</h2>
                <?php if ($current_user): ?>
                    <h2><?php echo htmlspecialchars($current_user); ?></h2>
                <?php endif; ?>
            </div>
            <div class="informatieFrame">
                <h2 class="grijsText">Achternaam</h2>
                <?php if ($lastName): ?>
                    <h2><?php echo htmlspecialchars($lastName); ?></h2>
                <?php else: ?>
                    <h2>Onbekend</h2>
                <?php endif; ?>
            </div>
            <div class="informatieFrame">
                <h2 class="grijsText">Geboortedatum</h2>
                <?php if ($dateOfBirth): ?>
                    <h2><?php echo htmlspecialchars($dateOfBirth); ?></h2>
                <?php else: ?>
                    <h2>Onbekend</h2>
                <?php endif; ?>
            </div>
        </div>
        <div class="tweedeRijInfo">
            <div class="informatieFrame">
                <h2 class="grijsText">email</h2>
                <?php if ($email): ?>
                    <h2><?php echo htmlspecialchars($email); ?></h2>
                <?php endif; ?>
            </div>
            <div class="informatieFrame">
                <h2 class="grijsText">Registratiedatum</h2>
                <?php if ($registration_date): ?>
                    <h2><?php echo htmlspecialchars($registration_date); ?></h2>
                <?php else: ?>
                    <h2>Onbekend</h2>
                <?php endif; ?>
            </div>
            <div class="informatieFrame">
                <h2 class="grijsText">GebruikerID</h2>
                <?php if ($user_id): ?>
                    <h2><?php echo htmlspecialchars($user_id); ?></h2>
                <?php else: ?>
                    <h2>Onbekend</h2>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
<footer></footer>
</body>

</html>

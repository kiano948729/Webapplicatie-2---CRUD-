<?php
// Laad centrale configuratie
require_once __DIR__ . '/../../config/init.php';

// Gebruik $conn uit init.php
global $conn;

// Redirect als gebruiker al ingelogd is
if (isset($_SESSION['user_id'])) {
    header("Location: VeranderGegevens.php");
    exit;
}

// Foutmelding leeg beginnen
$error = '';

if (isset($_POST["Registreren-Knop"])) {
    $email = $_POST['email'];

    // Zoek gebruiker op email
    $sql = "SELECT * FROM users WHERE email = :email";
    $statement = $conn->prepare($sql);
    $statement->bindParam(":email", $email);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Sla gebruikersinfo op in sessie
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];

        // Redirect naar veranderpagina
        header("Location: VeranderGegevens.php");
        exit;
    } else {
        $error = 'Geen gebruiker gevonden met deze e-mail.';
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wachtwoord Reset | Backpack & Go</title>
    <link rel="stylesheet" href="<?= CSS_URL ?>/style.css">
    <link rel="stylesheet" href="<?= CSS_URL ?>/JoeStyle.css">
    <link rel="icon" href="<?= IMG_URL ?>/CompassLogo.png" type="image/png">
    <script src="https://kit.fontawesome.com/5321476408.js"  crossorigin="anonymous"></script>
    <script src="<?= JS_URL ?>/jsK.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>
<header>
    <?php include PUBLIC_PATH . '/components/header.php'; ?>
</header>

<main>
    <div class="Afbeelding-Achtergrond-Login">
        <div class="loginFrameRij">
            <div class="loginResetFrame">
                <div class="handLogoFrame">
                    <i id="emailLogo" class="fa-solid fa-envelope"></i>
                </div>
                <div class="textFrameLogin">
                    <h1 class="grijsText">Typ uw e-mail in</h1>
                </div>

                <!-- Toon foutmelding -->
                <?php if (!empty($error)): ?>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <!-- Login formulier -->
                <form action="Reset.php" method="post">
                    <input class="loginInputNaam" name="email" placeholder="E-mailadres" type="email" required>
                    <button class="filter-knop" name="Registreren-Knop" type="submit">
                        <h2 class="Witte-Text">Verifiëren</h2>
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

<footer></footer>
</body>
</html>
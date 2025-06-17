<?php
// Laad centrale configuratie
require_once __DIR__ . '/../../config/init.php';

// Redirect als gebruiker al ingelogd is
if (isset($_SESSION['user_id'])) {
    header("Location: VeranderGegevens.php");
    exit;
}

$error = '';

if (isset($_POST["Registreren-Knop"])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Valideer invoer
    if (empty($username) || empty($email) || empty($password)) {
        $error = "Alle velden zijn verplicht.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Ongeldig e-mailadres.";
    } else {
        try {
            // Controleer of e-mail al bestaat
            $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            if ($stmt->fetchColumn() > 0) {
                $error = "Dit e-mailadres is al in gebruik.";
            } else {
                // Hash wachtwoord
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Voeg gebruiker toe
                $sql = "INSERT INTO users (username, email, password)
                        VALUES (:username, :email, :password)";
                $statement = $conn->prepare($sql);
                $statement->execute([
                    ':username' => $username,
                    ':email' => $email,
                    ':password' => $hashedPassword
                ]);

                // Redirect naar login
                header("Location: login.php");
                exit;
            }
        } catch (PDOException $e) {
            $error = "Registratie mislukt: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registreer | Backpack & Go</title>
    <link rel="stylesheet" href="<?= CSS_URL ?>/style.css" />
    <link rel="stylesheet" href="<?= CSS_URL ?>/JoeStyle.css" />
    <link rel="icon" href="<?= IMG_URL ?>/CompassLogo.png" type="image/png" />
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
            <div class="registrerenFrame">
                <div class="handLogoFrame">
                    <i id="regristreerLogo" class="fa-solid fa-right-to-bracket"></i>
                </div>
                <div class="textFrameLogin">
                    <h1 class="grijsText">Maak een account aan</h1>
                </div>

                <!-- Toon foutmelding -->
                <?php if (!empty($error)): ?>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <!-- Registratie formulier -->
                <form action="registreren.php" method="post">
                    <input class="loginInputNaam" name="username" placeholder="Naam" type="text" required>
                    <input class="loginInputWachtwoord" name="email" placeholder="E-mailadres" type="email" required>
                    <input class="loginInputWachtwoord" name="password" placeholder="Wachtwoord" type="password" required>
                    <button class="filter-knop" name="Registreren-Knop" type="submit">
                        <h2 class="Witte-Text">Maken</h2>
                    </button>
                    <h4 class="grijsText">Al een account?&nbsp;<a class="blauwText" href="login.php">Klik hier</a></h4>
                </form>
            </div>
        </div>
    </div>
</main>

<footer></footer>
</body>
</html>
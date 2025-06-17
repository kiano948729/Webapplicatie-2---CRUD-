<?php
// Laad centrale configuratie
global $conn;
require_once __DIR__ . '/../../config/init.php';

// Redirect als gebruiker al ingelogd is
if (isset($_SESSION['user_id'])) {
    header("Location: " . TEMPLATES_URL . "/Account.php");
    exit;
}

$error = '';

if (isset($_POST["Registreren-Knop"])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Vul alle velden in.";
    } else {
        // Zoek in users-tabel
        $stmt = $conn->prepare("SELECT user_id, username, password FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Zoek in admins-tabel
        $stmt_admin = $conn->prepare("SELECT admin_id, username, password FROM admins WHERE username = :username");
        $stmt_admin->execute([':username' => $username]);
        $admin = $stmt_admin->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Login als gebruiker
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            header("Location: " . TEMPLATES_URL . "/Account.php");
            exit;

        } elseif ($admin && password_verify($password, $admin['password'])) {
            // Login als admin
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['username'] = $admin['username'];
            $_SESSION['is_admin'] = true;
            header("Location: " . BACKEND_URL . "/admin/admin.php");
            exit;

        } else {
            $error = "Onjuiste gebruikersnaam of wachtwoord";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Backpack & Go</title>

    <!-- Stylesheets -->
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
    <div class="Afbeelding-Achtergrond-Login">
        <div class="loginFrameRij">
            <div class="loginFrame">
                <div class="handLogoFrame">
                    <i id="HandLogo" class="fa-solid fa-hand"></i>
                </div>
                <div class="textFrameLogin">
                    <h1 class="grijsText">Welkom terug</h1>
                </div>

                <!-- Toon foutmelding -->
                <?php if (!empty($error)): ?>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <!-- Login formulier -->
                <form action="login.php" method="post">
                    <input class="loginInputNaam" name="username" placeholder="Gebruikersnaam" type="text" required>
                    <input class="loginInputWachtwoord" name="password" placeholder="Wachtwoord" type="password" required>
                    <button class="filter-knop" name="Registreren-Knop" type="submit">
                        <h2 class="Witte-Text">Login</h2>
                    </button>
                    <h4 class="grijsText">Nog geen account?&nbsp;<a class="blauwText" href="registreren.php">Klik hier</a></h4>
                    <a class="blauwText" href="Reset.php">Wachtwoord vergeten?</a>
                </form>
            </div>
        </div>
    </div>
</main>
</body>
</html>
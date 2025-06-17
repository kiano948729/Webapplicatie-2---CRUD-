<?php
session_start();
require_once 'backend/databaseConnect.php';

// Verbind met database
try {
    $conn = new PDO("mysql:host=webabb2;dbname=reizen;charset=utf8mb4", "root", "rootpassword", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage());
}

// Login-verwerking voor gebruikers en admins
if (isset($_POST["Registreren-Knop"])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query voor gebruikers
    $query_users = "SELECT user_id, username, password FROM users WHERE username = :username";
    $statement_users = $conn->prepare($query_users);
    $statement_users->execute([':username' => $username]);
    $gebruiker = $statement_users->fetch();

    // Query voor admins
    $query_admins = "SELECT admin_id, username, password FROM admins WHERE username = :username";
    $statement_admins = $conn->prepare($query_admins);
    $statement_admins->execute([':username' => $username]);
    $admin = $statement_admins->fetch();

    if ($gebruiker && password_verify($password, $gebruiker['password'])) {
        $_SESSION['Gebruiker'] = true;
        $_SESSION['user_id'] = $gebruiker['user_id'];
        $_SESSION['username'] = $gebruiker['username'];
        header("Location: Account.php");
        exit;
    } elseif ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['Admin'] = true;
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['username'] = $admin['username'];
        header("Location: backend/admin/admin.php");
        exit;
    } else {
        echo "<script>alert('Onjuiste gebruikersnaam of wachtwoord');</script>";
    }
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
                <div class="loginFrame">
                    <div class="handLogoFrame">
                        <i id="HandLogo" class="fa-solid fa-hand"></i>
                    </div>
                    <div class="textFrameLogin">
                        <h1 class="grijsText">Welkom terug</h1>
                    </div>

                    <form action="login.php" method="post">
                        <input class="loginInputNaam" name="username" placeholder="Naam" type="text">
                        <input class="loginInputWachtwoord" name="password" placeholder="Wachtwoord" type="password">
                        <button class="filter-knop" name="Registreren-Knop" type="submit">
                            <h2 class="Witte-Text">Login</h2>
                        </button>
                        <h4 class="grijsText">Nog geen account?&nbsp;<a class="blauwText" href="registreren.php">Klik
                                hier</a></h4>
                        <a class="blauwText" href="Reset.php">Wachtwoord of Naam vergeten?</a>
                    </form>
                </div>
        </main>
        <footer>
        </footer>
</body>
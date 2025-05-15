<?php
global $conn;
$current_page = basename($_SERVER['PHP_SELF']);
?>
<?php
session_start();
require_once 'backend/databaseConnect.php';

// Controleer of er een ingelogde gebruiker is
$current_user = null;
if (isset($_SESSION['user_id'])) {
    $query = "SELECT username FROM users WHERE id = :id";
    $statement = $conn->prepare($query);
    $statement->execute([':id' => $_SESSION['user_id']]);
    $current_user = $statement->fetch(PDO::FETCH_ASSOC)['username'];
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google" content="notranslate">
    <title>Vakanties Boeken | Naam</title>
    <link rel="stylesheet" href="css/JoeStyle.css">
    <link rel="icon" href="../img/CompassLogo.png" type="Images/png">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://kit.fontawesome.com/5321476408.js" crossorigin="anonymous"></script>
    <script src="script.js" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
</head>
<body>
<div class="Afbeelding-Achtergrond-Login">

    <header>
        <?php require_once("components/header.php")?> 
    </header>
    <main>
        <div class="loginFrameRij">
            <div class="onzichtbareFrame">
                <div class="loginFrame">
                <div class="loginTextFrame">
                    <h1>Login</h1>
                </div>
                <div class="textBovenInput">
                    <h2 class="normaalText">Naam</h2>
                </div>
                <form action="Login.php" method="post">
                    <input class="loginInput" type="text">
                </form>
                 <div class="textBovenInput">
                    <h2 class="normaalText">Wachtwoord</h2>
                </div>
                <form action="Login.php" method="post">
                    <input class="loginInput" type="text">
                </form>
                <div class="knopRij">
                    <button class="loginKnop" type="submit">
                            <h2 class="Witte-Text">LOGIN</h2>
                    </button>
                </div>
            </div>
             <div class="loginFrameRedirect">
                <div class="loginTextFrameRedirect">
                    <h1 class="boldText">Nieuw hier?</h1>
                </div>
                <div class="TextFrameRedirect">
                    <h2 class="normaalText">U kan hier onder registreren.</h2>
                </div>
                <div class="logoRij">
                    <i class="fa-solid fa-arrow-down"></i>
                </div>
                
                <div class="knopRij">
                    <button class="loginKnop" type="submit">
                        <h2 class="Witte-Text">registreren</h2>
                    </button>
                </div>
            </div>
            </div>
        </div>
    </main>
    <footer>
    </footer>
</body>
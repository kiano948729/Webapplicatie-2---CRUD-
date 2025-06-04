<?php
session_start();
?>
<?php
global $conn;
$current_page = basename($_SERVER['PHP_SELF']);
?>
<?php
try {
    $connection = new PDO("mysql:host=webabb2;dbname=reizen;charset=utf8mb4", "root", "rootpassword", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage());
}

if (isset($_POST["Registreren-Knop"])) {
    $sql = "SELECT * FROM users WHERE email = :email";
    $statement = $connection->prepare($sql);
    $statement->bindParam(":email", $_POST['email']);
    $statement->execute();
    $Bewerker = $statement->fetch();
    if ($Bewerker) {
        $_SESSION["Gebruiker"] = true;
        $_SESSION['user_id'] = $Bewerker['user_id'];
        $_SESSION['username'] = $Bewerker['username'];
        header("Location: Account.php");
        exit;
    } else {
        // Optioneel: foutmelding
        echo "<script>alert('Onjuiste email');</script>";
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
<div class="Afbeelding-Achtergrond-Login">

    <header>
        <?php require_once("components/header.php")?>
    </header>
    <main>
        <div class="loginFrameRij">
            <div class="loginResetFrame">
                <div class="handLogoFrame">
                    <i id="emailLogo" class="fa-solid fa-envelope"></i>
                </div>
                <div class="textFrameLogin">
                    <h1 class="grijsText">Typ u email in</h1>
                </div>
                <form action="Reset.php" method="post">
                    <input class="loginInputNaam" name="email" placeholder="email" type="text">
                    <button class="filter-knop" name="Registreren-Knop" type="submit">
                        <h2 class="Witte-Text">verifiëren</h2>
                    </button>
                </form>
            </div>
        </div>
    </main>
    <footer>
    </footer>
</body>
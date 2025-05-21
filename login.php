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

<?php
try {
    $connection = new PDO("mysql:host=webabb2;dbname=reizen;charset=utf8mb4", "root", "rootpassword", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage());
}
if(isset($_POST["Registreren-Knop"])){
    $sql = "SELECT * FROM `users` WHERE password = :password AND username = :username";
    $statement = $connection->prepare($sql);
    $statement->bindParam(":username", $_POST['username']);
    $statement->bindParam(":password", $_POST['password']);
    $statement->execute();
    $gebruiker = $statement->fetch();
    if($gebruiker) {
        header("Location: registreren.php");
        exit;
    } else {
        header("Location: login.php");
        exit;
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
           <div class="loginFrame">
               <div class="handLogoFrame">
                   <i class="fa-solid fa-hand"></i>
               </div>
               <div class="textFrameLogin">
                   <h1 class="grijsText">Welkom terug</h1>
               </div>
               <form action="login.php" method="post">

                   <input class="loginInputNaam" name="username" placeholder="Naam" type="text">
                   <input class="loginInputWachtwoord" name="username" placeholder="Wachtwoord" type="password">
               </form>
           </div>
        </div>
    </main>
    <footer>
    </footer>
</body>
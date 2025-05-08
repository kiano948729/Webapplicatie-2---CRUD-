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
    <title>Menu</title>
    <link rel="stylesheet" href="css/JoeStyle.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://kit.fontawesome.com/5321476408.js" crossorigin="anonymous"></script>
    <script src="script.js" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
</head>
<body>
<div class="Afbeelding-Berg">

    <header>
        <?php require_once("components/header.php")?> 
    </header>
    <main>
        <h1 class="Witte-Text">DE BESTE VAKANTIES</h1>
        <h2 class="Minder-Ruimte-Witte-Text">Ontdek de wereld.</h2>
      <div class="VakantieBlokMargin">
      <?php require_once("components/vakanties-blok.php")?> 
      </div>
    </main>
    <footer>

    </footer>
    </div>
</body>
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
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/5321476408.js" crossorigin="anonymous"></script>
    <script src="script.js" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap"
        rel="stylesheet">
</head>

<body>
    <div class="vakanties-main">
        <header>
            <?php require_once("components/header.php") ?>
        </header>
        <main>
            <div class="trip-advisor">
                <h2>trip advisor</h2>
            </div>
            <div class="vakanties-main-text">
                <h1>Ontdek de wereld zoals jij wilt.</h1>
                <h3>reisbureau met een proffensiele aanpak zodat jij een zorgloze reis hebt!</h3>
            </div>
            <div>

            </div>
    </div>
    </main>
    <footer>

    </footer>
</body>
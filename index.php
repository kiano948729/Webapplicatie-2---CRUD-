<?php
global $conn;
$current_page = basename($_SERVER['PHP_SELF']);
?>
<?php
session_start();
require_once 'backend/databaseConnect.php';
require 'backend/databaseConnect.php';
require 'backend/conn.php';
include 'backend/fetch_bestemmingen.php';
include 'backend/fetch_deals.php';
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
    <link rel="icon" href="../img/CompassLogo.png" type="Images/png">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://kit.fontawesome.com/5321476408.js" crossorigin="anonymous"></script>
    <script src="script.js" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap"
        rel="stylesheet">
</head>

<body>
    <div class="Afbeelding-Berg">

        <header>
            <?php require_once("components/header.php") ?>
        </header>
        <main>
            <div>
                <div class="mini-dashboard">
                    <div class="dash-card">
                        <h4>Totaal accommodaties</h4>
                        <p id="aantal-accommodaties"><?= count($results) ?></p>
                    </div>
                    <div class="dash-card">
                        <h4>Aantal deals</h4>
                        <p id="aantal-deals"><?= count($deals) ?></p>
                    </div>
                    <div class="dash-card">
                        <h4>Populair</h4>
                        <p>Spanje </p> <!-- eventueel dynamisch maken -->
                    </div>
                </div>

            </div>
            <div class="index-info">
                <div class="VakantieBlokMargin">
                    <?php require_once("components/vakanties-blok.php") ?>
                </div>
                <section class="populaire-themas">
                    <h2>Populaire Thema's</h2>
                    <div class="thema-grid">
                        <a href="#" class="thema-kaart zonvakantie">
                            <h3>Zonvakanties</h3>
                        </a>
                        <a href="#" class="thema-kaart citytrip">
                            <h3>Citytrips</h3>
                        </a>
                        <a href="#" class="thema-kaart avontuur">
                            <h3>Avontuurlijke Reizen</h3>
                        </a>
                        <a href="#" class="thema-kaart allinclusive">
                            <h3>All-inclusive</h3>
                        </a>
                        <a href="#" class="thema-kaart lastminute">
                            <h3>Last Minutes</h3>
                        </a>
                    </div>
                </section>
            </div>
        </main>
        <footer>

        </footer>
    </div>
</body>
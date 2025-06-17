<?php
global $conn;
$current_page = basename($_SERVER['PHP_SELF']);

session_start();
require_once __DIR__ . '/config/init.php';

include FETCH_PATH . '/fetch_bestemmingen.php';
include FETCH_PATH . '/fetch_deals.php';

// Controleer of er een ingelogde gebruiker is
$current_user = null;
if (isset($_SESSION['user_id'])) {
    $query = "SELECT username FROM users WHERE user_id = :id";
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
    <link rel="stylesheet" href="<?= CSS_URL ?>/style.css">
    <link rel="stylesheet" href="<?= CSS_URL ?>/JoeStyle.css">
    <script src="https://kit.fontawesome.com/5321476408.js" crossorigin="anonymous"></script>
    <script src="frontend/public/js/jsK.js" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap"
          rel="stylesheet">
</head>

<body>
<header>

    <?php include PUBLIC_PATH . '/components/header.php'; ?>

</header>
<main class="indexMain">
    <div class="afbeeldingIndexBerg">
        <div class="indexTextDiv">
            <div class="blauweOnderkant">
                <h1 class="boldWitteText">Ontdek de mooiste backpack vakanties</h1>
            </div>
        </div>
    </div>
    <div class="indexSplitDiv">
        <div class="indexLinkerDiv">
            <h1 class="Titel-Zwarte-Text">Boek vakanties makkelijker met backpack & go</h1>
        </div>
        <div class="indexRechterDiv">
            <h2 class="normaalText">Welkom bij backpack & go, hier kan u op een makkelijke manier leuke backpacking
                vakanties boeken</h2>
            <a href="frontend/templates/overOns.php">
                <button class="filter-knop-account">
                    <h2 class="Witte-Text">Lees meer</h2>
                    <i id="Icon" class="fa-solid fa-arrow-right"></i>
                </button>
            </a>
        </div>
    </div>
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
    <div class="alignsearch">
        <div class="VakantieBlokMargin">
            <form id="zoekForm" method="get">
                <div class="input-rij">
                    <!-- Locatie -->
                    <div class="invoer-blok">
                        <input type="hidden" name="type" value="accommodation">
                        <label for="locatie"><i class="fas fa-map-marker-alt"></i> Locatie</label>
                        <input type="text" id="locatie" name="locatie"
                               placeholder="Bijv. Amsterdam, Parijs, Rome">
                    </div>

                    <!-- Check-in datum -->
                    <div class="invoer-blok">
                        <label for="check-in"><i class="fas fa-calendar-check"></i> Check-in</label>
                        <input type="date" id="check-in" name="check-in">
                    </div>

                    <!-- Check-out datum -->
                    <div class="invoer-blok">
                        <label for="check-out"><i class="fas fa-calendar-times"></i> Check-out</label>
                        <input type="date" id="check-out" name="check-out">
                    </div>
                </div>

                <!-- Accommodatie type filter -->
                <div class="filters">
                    <h4>Type verblijf</h4>
                    <input type="radio" id="filter-huis" name="filter" value="huis">
                    <label for="filter-huis"><i class="fas fa-home"></i> Huis</label>

                    <input type="radio" id="filter-hotel" name="filter" value="hotel">
                    <label for="filter-hotel"><i class="fas fa-hotel"></i> Hotel</label>

                    <input type="radio" id="filter-hostel" name="filter" value="hostel">
                    <label for="filter-residentieel"><i class="fas fa-city"></i> hostel</label>

                    <input type="radio" id="filter-camping" name="filter" value="camping">
                    <label for="filter-appartement"><i class="fas fa-building"></i> Camping</label>

                    <input type="radio" id="filter-anders" name="filter" value="anders">
                    <label for="filter-appartement"><i class="fas fa-building"></i> Anders</label>
                </div>

                <!-- Prijsbereik sliders -->
                <div class="price-range">
                    <label for="minPriceSlider">Minimale prijs: <span id="minPriceRangeValue">€0</span></label>
                    <input type="range" min="0" max="1000" step="50" id="minPriceSlider" name="min_price"
                           value="0">

                    <label for="maxPriceSlider">Maximale prijs: <span
                                id="maxPriceRangeValue">€1000</span></label>
                    <input type="range" min="0" max="1000" step="50" id="maxPriceSlider" name="max_price"
                           value="1000">
                </div>

                <!-- Zoekknop -->
                <div class="zoek-knop">
                    <button type="submit"><i class="fas fa-search"></i> Zoek Verblijven</button>
                    <button type="button" id="resetFiltersBtn" onclick="resetFilters()"
                            class="reset-filter-btn">Reset Filters
                    </button>
                </div>
            </form>

        </div>
    </div>
    <div id="vakantieResultaten"></div>


</main>
<footer>

</footer>
</body>
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
    <script src="jsK.js" defer></script>
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
            <?php  require_once("components/vakanties-blok.php")?>
    </div>
    <div class="vakanties-bestemming">
        <div class="titel-balk">
            <h2><strong>Popular</strong> destination</h2>
            <div class="navigatie-knoppen">
                <button onclick="vorigeSlide()">←</button>
                <button onclick="volgendeSlide()">→</button>
            </div>
        </div>
        <!-- javascript moet nog worden aangepast ivm database reizen als dier zijn-->
        <div class="bestemming-lijst" id="bestemming-lijst">
            <!-- JavaScript vult hier de kaarten in -->
        </div>
    </div>
    <div class="vakanties-explore-events">
        <div class="header-rij">
            <h2><strong>Explore</strong> events</h2>
            <div class="zoekfilter">
                <input type="text" placeholder="Search by location">
                <button>Filters</button>
            </div>
        </div>

        <div class="event-lijst">
            <div class="event-kaart">
                <img src="https://via.placeholder.com/300x180?text=Adventure" alt="Adventure">
                <div class="event-info">
                    <h3>Exploring the Hidden Wonders of the World Adventure</h3>
                    <div class="event-meta">
                        <span>Bangladesh</span>
                        <span>July 3 to 7</span>
                        <span>4.8 Rating</span>
                    </div>
                    <div class="event-prijs">$400 / Night <br><small>Including taxes and fees</small></div>
                    <button>View Details</button>
                </div>
            </div>

            <div class="event-kaart">
                <img src="https://via.placeholder.com/300x180?text=Snowboard" alt="Snowboard">
                <div class="event-info">
                    <h3>Embark on a Cultural Journey Across Stunning Landscapes</h3>
                    <div class="event-meta">
                        <span> Bangladesh</span>
                        <span>July 10 to 12</span>
                        <span>4.6 Rating</span>
                    </div>
                    <div class="event-prijs">$320 / Night <br><small>Including taxes and fees</small></div>
                    <button>View Details</button>
                </div>
            </div>

            <div class="event-kaart">
                <img src="https://via.placeholder.com/300x180?text=Mountains" alt="Mountains">
                <div class="event-info">
                    <h3>Discover Majestic Mountains and Breathtaking Views</h3>
                    <div class="event-meta">
                        <span>Bangladesh</span>
                        <span>July 5 to 7</span>
                        <span>4.9 Rating</span>
                    </div>
                    <div class="event-prijs">$450 / Night <br><small>Including taxes and fees</small></div>
                    <button>View Details</button>
                </div>
            </div>
        </div>
    </div>
    </main>
    <footer>

    </footer>
</body>
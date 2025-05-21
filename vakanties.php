<?php
global $conn, $results;
$current_page = basename($_SERVER['PHP_SELF']);
?>
<?php
session_start();
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

<body class="vakantie-body">
    <header>

    </header>
    <div class="explore-background">
        <div class="explore-container">
            <!-- Sidebar -->
            <div class="sidebar-main">
                <aside class="sidebar-nav">
                    <img src="img/CompassLogo.png" alt="logo">
                    <button class="nav-btn" data-target="homeContent"><i class="fa-solid fa-house-chimney"></i></button>
                    <button class="nav-btn" data-target="searchContent"><i
                            class="fa-solid fa-magnifying-glass"></i></button>
                    <button class="nav-btn" data-target="trendingContent"><i
                            class="fa-solid fa-arrow-trend-up"></i></button>
                    <button class="nav-btn" data-target="myVacationsContent"><i
                            class="fa-solid fa-user-shield"></i></button>

                </aside>
                <div class="vertical-line"></div>
                <aside class="sidebar">
                    <h1>navigatie inhoud</h1>
                    <div class="side-bar-main-navigatie">
                        <a href="index.php"><i class="fa-solid fa-compass"></i>Home</a>
                        <a href="overOns.php"><i class="fa-solid fa-person-hiking"></i>Over ons</a>
                        <a href="contact.php"><i class="fa-solid fa-square-envelope"></i>Contact</a>
                    </div>
                </aside>
            </div>
            <!-- Content -->
            <main class="content">
                <div class="content-header-navigatie">
                    <div class="content-header">
                        <h2><strong>Explore</strong> events</h2>
                    </div>
                    <div class="filter-balk">
                        <div class="filter-container">
                            <button class="filter-knop" id="typeFilterBtn">Type</button>

                            <div class="filter-dash" id="typeDashboard">
                                <h4>Selecteer Type</h4>
                                <label><input type="checkbox" name="type" value="hostel"> Hostel</label>
                                <label><input type="checkbox" name="type" value="hotel"> Hotel</label>
                                <label><input type="checkbox" name="type" value="bb"> B&B</label>
                                <label><input type="checkbox" name="type" value="camping"> Camping</label>
                                <button id="applyFilters">Toepassen</button>
                            </div>
                        </div>

                        <div class="filter-rechts">
                            <select>
                                <option>Sorteer op datum</option>
                                <option>Sorteer op prijs (laag-hoog)</option>
                                <option>Sorteer op prijs (hoog-laag)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Home - standaard zichtbaar -->
                <div id="homeContent" class="content-section">
                    <div class="event-grid">
                        <?php foreach ($results as $accommodation): ?>
                            <div class="vakantie-kaart">
                                <img src="<?= htmlspecialchars($accommodation['photo_url']) ?>"
                                    alt="Foto van <?= htmlspecialchars($accommodation['name']) ?>">
                                <h3><?= htmlspecialchars($accommodation['name']) ?></h3>
                                <p><span><?= htmlspecialchars($accommodation['type']) ?></span>
                                    <span><?= htmlspecialchars($accommodation['location']) ?></span>
                                </p>
                                <p><?= htmlspecialchars($accommodation['description']) ?></p>
                                <p><strong>€ <?= htmlspecialchars($accommodation['price']) ?></strong></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Zoeken -->
                <div id="searchContent" class="content-section" style="display: none;">
                    <p>Zoekformulier en resultaten hier</p>
                    <form id="zoekForm" method="get">
                        <div class="input-rij">
                            <div class="invoer-blok">
                                <label for="locatie">Location</label>
                                <input type="text" id="locatie" name="locatie" placeholder="Type the destination">
                            </div>

                            <div class="invoer-blok">
                                <label for="check-in">Check in</label>
                                <input type="date" id="check-in" name="check-in">
                            </div>

                            <div class="invoer-blok">
                                <label for="check-out">Check out</label>
                                <input type="date" id="check-out" name="check-out">
                            </div>
                        </div>
                        <div class="filters">
                            <input type="radio" id="filter-huis" name="filter" value="house">
                            <label for="filter-huis">House</label>

                            <input type="radio" id="filter-hotel" name="filter" value="hotel">
                            <label for="filter-hotel">Hotel</label>

                            <input type="radio" id="filter-residentieel" name="filter" value="residential">
                            <label for="filter-residentieel">Residential</label>

                            <input type="radio" id="filter-appartement  " name="filter" value="apartment">
                            <label for="filter-appartement">Apartment</label>
                        </div>

                        <div class="zoek-knop">
                            <button type="submit">Search Properties</button>
                        </div>
                    </form>
                    <div id="vakantieResultaten"></div>

                </div>

                <!-- Trending -->
                <div id="trendingContent" class="content-section" style="display: none;">
                    <div class="deals-grid">
                        <?php foreach ($deals as $deal): ?>
                            <div class="vakantie-kaart">
                                <img src="<?= htmlspecialchars($deal['photo_url']) ?>"
                                    alt="Foto van <?= htmlspecialchars($deal['destination']) ?>">
                                <h3><?= htmlspecialchars($deal['destination']) ?></h3>
                                <p><?= htmlspecialchars($deal['description']) ?></p>
                                <p><strong>€ <?= htmlspecialchars($deal['price']) ?></strong></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <!-- Mijn vakanties -->
                <div id="myVacationsContent" class="content-section" style="display: none;">
                    <p>Mijn opgeslagen vakanties hier</p>
                </div>

            </main>
        </div>
    </div>
    <footer>

    </footer>
</body>

</html>
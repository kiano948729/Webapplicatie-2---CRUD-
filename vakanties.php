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
include 'backend/fetch_reviews.php';
// Haal alle recensies per accommodatie op via INNER JOIN
$reviews_per_accommodatie = [];

$reviewQuery = "
    SELECT 
        r.comment,
        r.rating,
        r.review_date AS date,
        r.accommodation_id,   
        u.username
    FROM accommodatie_reviews r
    INNER JOIN users u ON r.user_id = u.user_id
    WHERE r.approved = 1
";

$stmt = $conn->prepare($reviewQuery);
$stmt->execute();
$all_reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Groepeer recensies per accommodatie_id
foreach ($all_reviews as $review) {
    $accommodationId = $review['accommodation_id'];
    if (!isset($reviews_per_accommodatie[$accommodationId])) {
        $reviews_per_accommodatie[$accommodationId] = [];
    }
    $reviews_per_accommodatie[$accommodationId][] = $review;
}

//hardcoded admin voor test gebruik
$current_user = null;
$is_admin = false;

if (isset($_SESSION['user_id'])) {
    // Haal gebruikersnaam op
    $query = "SELECT username FROM users WHERE user_id = :id";
    $statement = $conn->prepare($query);
    $statement->execute([':id' => $_SESSION['user_id']]);
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $current_user = $result['username'];

        // Controleer of deze gebruiker admin is
        $adminCheck = $conn->prepare("SELECT 1 FROM admins WHERE username = :username LIMIT 1");
        $adminCheck->execute([':username' => $current_user]);

        if ($adminCheck->fetch()) {
            $_SESSION['is_admin'] = true;
        }
    }
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
    <link rel="stylesheet" href="css/JoeStyle.css">
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
                        <a href="privacy.php"><i class="fa-solid fa-fingerprint"></i>privacy</a>
                    </div>
                </aside>
            </div>
            <!-- Content -->
            <main class="content">
                <div class="content-header-navigatie">
                    <div class="content-header">
                        <h2><strong>Explore</strong> events</h2>
                        <button type="button"
                            onclick="window.location.href='backend/admin/admin.php'">Beheerderspaneel</button>
                        <?php if (!empty($_SESSION['is_admin'])): ?>
                        <?php endif; ?>
                        <?php if ($current_user): ?>
                            <small class="logged-in-user">Ingelogd als: <?= htmlspecialchars($current_user) ?></small>
                        <?php else: ?>
                            <small class="logged-in-user" style="color: var(--text-light);">Niet ingelogd</small>
                        <?php endif; ?>
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
                                <option>Sorteer op prijs</option>
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
                                <button class="show-more-btn" data-id="<?= $accommodation['accommodation_id'] ?>">Meer
                                    info</button>
                                <!-- Boekingsformulier voor accommodatie -->
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <!-- In beide vakantie-kaart secties (homeContent & trendingContent) -->
                                    <div class="boeking-form">
                                        <h4>Boek nu</h4>
                                        <form action="backend/boek_accommodatie.php" method="POST">
                                            <input type="hidden" name="accommodation_id"
                                                value="<?= $accommodation['accommodation_id'] ?>">
                                            <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">

                                            <label
                                                for="start_date_<?= $accommodation['accommodation_id'] ?>">Startdatum:</label>
                                            <input type="date" id="start_date_<?= $accommodation['accommodation_id'] ?>"
                                                name="start_date" required>

                                            <label for="end_date_<?= $accommodation['accommodation_id'] ?>">Einddatum:</label>
                                            <input type="date" id="end_date_<?= $accommodation['accommodation_id'] ?>"
                                                name="end_date" required>

                                            <label for="aantal_personen_<?= $accommodation['accommodation_id'] ?>">Aantal
                                                personen:</label>
                                            <input type="number" id="aantal_personen_<?= $accommodation['accommodation_id'] ?>"
                                                name="aantal_personen" min="1" value="1" required>

                                            <button type="submit" class="book-btn">Boek deze reis</button>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <p><a href="login.php">Log in</a> om te boeken</p>
                                <?php endif; ?>
                            </div>

                            <div id="expanded-<?= $accommodation['accommodation_id'] ?>" class="expanded-info-overlay">
                                <div class="expanded-content">
                                    <button class="close-btn"
                                        onclick="closeExpandedInfo(<?= $accommodation['accommodation_id'] ?>)">&times;</button>
                                    <h2><?= htmlspecialchars($accommodation['name']) ?></h2>
                                    <p>Type: <?= htmlspecialchars($accommodation['type']) ?></p>
                                    <p>Locatie: <?= htmlspecialchars($accommodation['location']) ?></p>
                                    <p>Prijs: € <?= htmlspecialchars($accommodation['price']) ?></p>
                                    <p><?= htmlspecialchars($accommodation['description']) ?></p>
                                    <form method="POST" action="backend/submit_review.php">
                                        <input type="hidden" name="accommodation_id"
                                            value="<?= htmlspecialchars($accommodation['accommodation_id']); ?>">
                                        <label for="rating">Beoordeling (1-5):</label>
                                        <select name="rating" id="rating" required>
                                            <option value="">Kies een score</option>
                                            <option value="1">1 - Slecht</option>
                                            <option value="2">2 - Redelijk</option>
                                            <option value="3">3 - Goed</option>
                                            <option value="4">4 - Zeer goed</option>
                                            <option value="5">5 - Uitstekend</option>
                                        </select>

                                        <br><br>

                                        <label for="comment">Je recensie:</label><br>
                                        <textarea name="comment" id="comment" rows="5" cols="40"
                                            placeholder="Schrijf hier je ervaring..." required></textarea>

                                        <br><br>

                                        <button type="submit">Verstuur recensie</button>
                                    </form>
                                    <h3>Recensies</h3>
                                    <div class="reviews-container">
                                        <?php if (!empty($reviews_per_accommodatie[$accommodation['accommodation_id']])): ?>
                                            <?php foreach ($reviews_per_accommodatie[$accommodation['accommodation_id']] as $review): ?>
                                                <div class="review-item">
                                                    <p><strong><?= htmlspecialchars($review['username']) ?></strong> –
                                                        <?= date('d-m-Y', strtotime($review['date'])) ?>
                                                    </p>
                                                    <p>
                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                            <?php if ($i <= $review['rating']): ?>
                                                                ★
                                                            <?php else: ?>
                                                                ☆
                                                            <?php endif; ?>
                                                        <?php endfor; ?>
                                                    </p>
                                                    <p><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                                                    <hr>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <p>Geen recensies gevonden voor deze accommodatie.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Zoeken -->
                <div id="searchContent" class="content-section" style="display: none;">
                    <p><i class="fas fa-search-location"></i> Zoek jouw perfecte verblijf</p>
                    <form id="zoekForm" method="get">
                        <div class="input-rij">
                            <div class="invoer-blok">
                                <label for="locatie"><i class="fas fa-map-marker-alt"></i> Locatie</label>
                                <input type="text" id="locatie" name="locatie" placeholder="Type the destination">
                            </div>

                            <div class="invoer-blok">
                                <label for="check-in"><i class="fas fa-calendar-check"></i> Check-in</label>
                                <input type="date" id="check-in" name="check-in">
                            </div>

                            <div class="invoer-blok">
                                <label for="check-out"><i class="fas fa-calendar-times"></i> Check-out</label>
                                <input type="date" id="check-out" name="check-out">
                            </div>
                        </div>

                        <div class="filters">
                            <input type="radio" id="filter-huis" name="filter" value="house">
                            <label for="filter-huis"><i class="fas fa-home"></i> House</label>

                            <input type="radio" id="filter-hotel" name="filter" value="hotel">
                            <label for="filter-hotel"><i class="fas fa-hotel"></i> Hotel</label>

                            <input type="radio" id="filter-residentieel" name="filter" value="residential">
                            <label for="filter-residentieel"><i class="fas fa-city"></i> Residential</label>

                            <input type="radio" id="filter-appartement" name="filter" value="apartment">
                            <label for="filter-appartement"><i class="fas fa-building"></i> Apartment</label>
                        </div>

                        <div class="zoek-knop">
                            <button type="submit"><i class="fas fa-search"></i> Zoek Verblijven</button>
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

                                <!-- Boek-knop -->
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <form action="backend/boek_deal.php" method="POST">
                                        <input type="hidden" name="deal_id" value="<?= $deal['deal_id'] ?>">
                                        <input type="hidden" name="start_date" value="<?= date('Y-m-d') ?>">
                                        <input type="hidden" name="end_date" value="<?= date('Y-m-d', strtotime('+7 days')) ?>">

                                        <label for="aantal_personen_<?= $deal['deal_id'] ?>">Aantal personen:</label>
                                        <input type="number" id="aantal_personen_<?= $deal['deal_id'] ?>" name="aantal_personen"
                                            min="1" value="1" required>

                                        <button type="submit" class="book-btn">Boek deze deal</button>
                                    </form>

                                <?php else: ?>
                                    <p><a href="login.php">Log in</a> om te boeken</p>
                                <?php endif; ?>
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
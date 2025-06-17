<?php
global $conn, $results;
$current_page = basename($_SERVER['PHP_SELF']);
?>
<?php
session_start();

require_once __DIR__ . '/../../config/init.php';

// Gebruik nu direct $conn, $_SESSION, fetch_bestemmingen, enz.
include BACKEND_PATH . '/fetch/fetch_bestemmingen.php';
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
// Haal gebruikers-ID op als ingelogd
$user_id = $_SESSION['user_id'] ?? null;

// Als ingelogd, haal boekingen en reviews op
$user_bookings = [];
$user_reviews = [];

if ($user_id) {
    // Geboekte accommodaties via 'boeking_accommodaties'
    $bookingQuery = "
        SELECT 
            ba.boeking_accommodatie_id,
            ba.start_date,
            ba.end_date,
            ba.total_price,
            ba.aantal_personen,
            a.name AS accommodation_name,
            a.photo_url
        FROM boeking_accommodaties ba
        JOIN accommodaties a ON ba.accommodation_id = a.accommodation_id
        WHERE ba.user_id = :user_id
        ORDER BY ba.start_date DESC
    ";
    $stmt = $conn->prepare($bookingQuery);
    $stmt->execute([':user_id' => $user_id]);
    $user_bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Eigen recensies
    $reviewQuery = "
        SELECT 
            r.review_id,
            r.rating,
            r.comment,
            r.review_date,
            a.name AS accommodation_name
        FROM accommodatie_reviews r
        JOIN accommodaties a ON r.accommodation_id = a.accommodation_id
        WHERE r.user_id = :user_id
        ORDER BY r.review_date DESC
    ";
    $stmt = $conn->prepare($reviewQuery);
    $stmt->execute([':user_id' => $user_id]);
    $user_reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google" content="notranslate">
    <title>Menu</title>
    <link rel="stylesheet" href="<?= CSS_URL ?>/style.css">
    <link rel="stylesheet" href="<?= CSS_URL ?>/JoeStyle.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/5321476408.js" crossorigin="anonymous"></script>
    <script src="<?= JS_URL ?>/jsK.js" defer></script>

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
                <img src="../public/img/CompassLogo.png" alt="logo">
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
                    <a href="<?= ROOT_URL ?>/index.php"><i class="fa-solid fa-compass"></i> Home</a>
                    <a href="<?= TEMPLATES_URL ?>/overOns.php"><i class="fa-solid fa-person-hiking"></i> Over ons</a>
                    <a href="<?= TEMPLATES_URL ?>/contact.php"><i class="fa-solid fa-square-envelope"></i> Contact</a>
                    <a href="<?= TEMPLATES_URL ?>/privacy.php"><i class="fa-solid fa-fingerprint"></i> Privacy</a>
                </div>
                <hr class="side-line">
            </aside>
        </div>
        <!-- Content -->
        <main class="content">
            <div class="content-header-navigatie">
                <div class="content-header">
                    <h2><strong>Explore</strong> events</h2>
                    <button type="button"
                    <?php if (!empty($_SESSION['is_admin'])): ?>
                        <button onclick="window.location.href='<?= BACKEND_URL ?>/admin/admin.php'">Beheerderspaneel
                        </button>
                    <?php endif; ?>
                    <?php if (empty($current_user)): ?>
                        <small class="logged-in-user" style="color: var(--text-light);">Niet ingelogd</small>
                    <?php else: ?>
                        <a href="<?= TEMPLATES_URL ?>/Account.php">
                            <small class="logged-in-user">Ingelogd
                                als: <?= htmlspecialchars($current_user) ?></small></a>
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
                    <?php if (isset($_SESSION['review_submitted'])): ?>
                        <div class="success-message">
                            <?= htmlspecialchars($_SESSION['review_submitted']) ?>
                        </div>
                        <?php unset($_SESSION['review_submitted']); ?>
                    <?php endif; ?>
                    <div class="filter-rechts">
                        <select id="sortSelect">
                            <option value="">Standaard</option>
                            <option value="low_to_high">Sorteer op prijs (laag-hoog)</option>
                            <option value="high_to_low">Sorteer op prijs (hoog-laag)</option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- Home - standaard zichtbaar -->
            <div id="homeContent" class="content-section">
                <div class="event-grid">
                    <?php foreach ($results as $accommodation): ?>
                        <div class="vakantie-kaart">
                            <img src="/backend/img/<?= basename($accommodation['photo_url']) ?>"
                                 alt="<?= htmlspecialchars($accommodation['name']) ?>">
                            <h3><?= htmlspecialchars($accommodation['name']) ?></h3>
                            <p><span><?= htmlspecialchars($accommodation['type']) ?></span>
                                <span><?= htmlspecialchars($accommodation['location']) ?></span>
                            </p>
                            <p><?= htmlspecialchars($accommodation['description']) ?></p>
                            <p><strong>€ <?= htmlspecialchars($accommodation['price']) ?> per dag/pp</strong></p>
                            <button class="show-more-btn" data-id="<?= $accommodation['accommodation_id'] ?>">Meer
                                info
                            </button>
                            <!-- Boekingsformulier voor accommodatie -->
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <!-- In beide vakantie-kaart secties (homeContent & trendingContent) -->
                                <div class="boeking-form">
                                    <h4>Boek nu</h4>
                                    <form action="<?= CONTROLLERS_URL ?>/boek_accommodatie.php" method="POST">
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
                                        <input type="number"
                                               id="aantal_personen_<?= $accommodation['accommodation_id'] ?>"
                                               name="aantal_personen" min="1" value="1" required>

                                        <button type="submit" class="book-btn">Boek deze reis</button>
                                    </form>
                                </div>
                            <?php else: ?>
                                <p><a href="<?= TEMPLATES_URL ?>/login.php">Log in</a> om te boeken</p>
                            <?php endif; ?>
                        </div>

                        <div id="expanded-<?= $accommodation['accommodation_id'] ?>" class="expanded-info-overlay">
                            <div class="expanded-content">
                                <button class="close-btn"
                                        onclick="closeExpandedInfo(<?= $accommodation['accommodation_id'] ?>)">&times;
                                </button>
                                <h2><?= htmlspecialchars($accommodation['name']) ?></h2>
                                <p>Type: <?= htmlspecialchars($accommodation['type']) ?></p>
                                <p>Locatie: <?= htmlspecialchars($accommodation['location']) ?></p>
                                <p>Prijs: € <?= htmlspecialchars($accommodation['price']) ?></p>
                                <p><?= htmlspecialchars($accommodation['description']) ?></p>
                                <?php if (!empty($accommodation['large_description'])): ?>
                                    <p><strong>Informatie:</strong></p>
                                    <p><?= nl2br(htmlspecialchars($accommodation['large_description'])) ?></p>
                                <?php endif; ?>
                                <form method="POST" action="<?= CONTROLLERS_URL ?>/submit_review.php">
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

                <!-- Zoekresultaten worden hier weergegeven -->
                <div id="vakantieResultaten"></div>
            </div>


            <!-- Trending -->
            <div id="trendingContent" class="content-section" style="display: none;">

            </div>
            <!-- Mijn vakanties -->
            <div id="myVacationsContent" class="content-section" style="display: none;">
                <div class="my-vacations-container">
                    <h2>Mijn Vakanties</h2>

                    <!-- Geboekte accommodaties -->
                    <section class="my-bookings">
                        <h3>Geboekte Reizen</h3>
                        <?php if (!empty($user_bookings)): ?>
                            <div class="booking-grid">
                                <?php foreach ($user_bookings as $booking): ?>
                                    <div class="booking-card">
                                        <img src="/backend/img/<?= basename($accommodation['photo_url']) ?>"
                                             alt="<?= htmlspecialchars($accommodation['name']) ?>">
                                        <div class="booking-info">
                                            <h4><?= htmlspecialchars($booking['accommodation_name']) ?></h4>
                                            <p><strong>Datum:</strong>
                                                <?= date('d-m-Y', strtotime($booking['start_date'])) ?> -
                                                <?= date('d-m-Y', strtotime($booking['end_date'])) ?>
                                            </p>
                                            <p><strong>Aantal personen:</strong>
                                                <?= htmlspecialchars($booking['aantal_personen']) ?></p>
                                            <p><strong>Prijs:</strong>
                                                €<?= number_format($booking['total_price'], 2, ',', '.') ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p>Je hebt nog geen vakanties geboekt.</p>
                        <?php endif; ?>
                    </section>

                    <!-- Eigen recensies -->
                    <section class="my-reviews">
                        <h3>Mijn Recensies</h3>
                        <?php if (!empty($user_reviews)): ?>
                            <div class="reviews-container">
                                <?php foreach ($user_reviews as $review): ?>
                                    <div class="review-item">
                                        <h4><?= htmlspecialchars($review['accommodation_name']) ?></h4>
                                        <p><strong>Beoordeling:</strong>
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <?php if ($i <= $review['rating']): ?>
                                                    ★
                                                <?php else: ?>
                                                    ☆
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </p>
                                        <p>
                                            <strong>Commentaar:</strong> <?= nl2br(htmlspecialchars($review['comment'])) ?>
                                        </p>
                                        <small>Gepubliceerd op:
                                            <?= date('d-m-Y', strtotime($review['review_date'])) ?></small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p>Je hebt nog geen recensies geschreven.</p>
                        <?php endif; ?>
                    </section>
                </div>
            </div>
            <div id="myFavorite" class="content-section" style="display: none;">
                <h1>adsfasdfads</h1>
            </div>
        </main>
    </div>
</div>
<footer>

</footer>
<script>
    document.getElementById('sortSelect').addEventListener('change', function () {
        const selectedSort = this.value;
        // Ga naar dezelfde pagina met de sort-parameter
        window.location.href = window.location.pathname + '?sort=' + selectedSort;
    });
</script>
</body>

</html>
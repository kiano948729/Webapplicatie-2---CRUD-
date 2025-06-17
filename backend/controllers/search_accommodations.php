<?php
global $conn;
session_start();
require_once __DIR__ . '/../../config/init.php';

$locatie = $_GET['locatie'] ?? '';
$type = $_GET['filter'] ?? '';
$min_price = $_GET['min_price'] ?? 0;
$max_price = $_GET['max_price'] ?? 1000;

try {
    $sql = "SELECT * FROM accommodaties WHERE 1=1";
    $params = [];

    if (!empty($locatie)) {
        $sql .= " AND location LIKE ?";
        $params[] = "%$locatie%";
    }

    if (!empty($type)) {
        $sql .= " AND type = ?";
        $params[] = $type;
    }

    if (isset($_GET['min_price']) && is_numeric($_GET['min_price'])) {
        $sql .= " AND price >= ?";
        $params[] = intval($_GET['min_price']);
    }

    if ($max_price > 0) {
        $sql .= " AND price <= ?";
        $params[] = intval($max_price);
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Fout bij ophalen accommodaties: " . $e->getMessage();
}

// Start HTML-output
echo "<div class='event-grid1'>";
foreach ($results as $acc):
    echo "<div class='vakantie-kaart'>";
    echo "<img src='/backend/img/" . htmlspecialchars(basename($acc['photo_url'])) . "' alt='" . htmlspecialchars($acc['name']) . "'>";
    echo "<h3>" . htmlspecialchars($acc['name']) . "</h3>";
    echo "<p><span>" . htmlspecialchars($acc['type']) . "</span><span>" . htmlspecialchars($acc['location']) . "</span></p>";
    echo "<p>" . htmlspecialchars($acc['description']) . "</p>";
    echo "<p><strong>€ " . htmlspecialchars($acc['price']) . "</strong></p>";

    // Toon boekingsknoppen alleen als ingelogd
    if (isset($_SESSION['user_id'])):
        echo "<button class='show-more-btn' data-id='" . $acc['accommodation_id'] . "'>Meer info</button>";

        // Boekingsformulier
        echo "<div class='boeking-form'>
                <h4>Boek nu</h4>
                <form action='../../backend/controllers/boek_accommodatie.php' method='POST'>
                    <input type='hidden' name='accommodation_id' value='" . $acc['accommodation_id'] . "'>
                    <input type='hidden' name='user_id' value='" . $_SESSION['user_id'] . "'>

                    <label for='start_date_" . $acc['accommodation_id'] . "'>Startdatum:</label>
                    <input type='date' id='start_date_" . $acc['accommodation_id'] . "' name='start_date' required>

                    <label for='end_date_" . $acc['accommodation_id'] . "'>Einddatum:</label>
                    <input type='date' id='end_date_" . $acc['accommodation_id'] . "' name='end_date' required>

                    <label for='aantal_personen_" . $acc['accommodation_id'] . "'>Aantal personen:</label>
                    <input type='number' id='aantal_personen_" . $acc['accommodation_id'] . "' name='aantal_personen' min='1' value='1' required>

                    <button type='submit' class='book-btn'>Boek deze reis</button>
                </form>
              </div>";
    else:
        echo "<p><a href='<? TEMPLATES_URL ?>/login.php'>Log in</a> om te boeken</p>";
    endif;

    // Meer-info overlay
    echo "<div id='expanded-" . $acc['accommodation_id'] . "' class='expanded-info-overlay'>";
    echo "  <div class='expanded-content'>";
    echo "    <button class='close-btn' onclick='closeExpandedInfo(" . $acc['accommodation_id'] . ")'>&times;</button>";
    echo "    <h2>" . htmlspecialchars($acc['name']) . "</h2>";
    echo "    <p>Type: " . htmlspecialchars($acc['type']) . "</p>";
    echo "    <p>Locatie: " . htmlspecialchars($acc['location']) . "</p>";
    echo "    <p>Prijs: €" . htmlspecialchars($acc['price']) . "</p>";
    echo "    <p>" . htmlspecialchars($acc['description']) . "</p>";
    echo "<div class='large-description'>";
    echo nl2br(htmlspecialchars($acc['large_description']));
    echo "</div>";

    // Recensieformulier
    echo "    <form method='POST' action='backend/submit_review.php'>";
    echo "      <input type='hidden' name='accommodation_id' value='" . htmlspecialchars($acc['accommodation_id']) . "'>";
    echo "      <label for='rating'>Beoordeling (1-5):</label>";
    echo "      <select name='rating' required>";
    echo "        <option value=''>Kies een score</option>";
    echo "        <option value='1'>1 - Slecht</option>";
    echo "        <option value='2'>2 - Redelijk</option>";
    echo "        <option value='3'>3 - Goed</option>";
    echo "        <option value='4'>4 - Zeer goed</option>";
    echo "        <option value='5'>5 - Uitstekend</option>";
    echo "      </select><br><br>";
    echo "      <label for='comment'>Je recensie:</label><br>";
    echo "      <textarea name='comment' rows='5' cols='40' placeholder='Schrijf hier je ervaring...' required></textarea><br><br>";
    echo "      <button type='submit'>Verstuur recensie</button>";
    echo "    </form>";

    echo "    <h3>Recensies</h3>";
    echo "    <div class='reviews-container'>";
    echo "      <p>Geen recensies gevonden voor deze accommodatie.</p>";
    echo "    </div>";
    echo "  </div>";
    echo "</div>";

    echo "</div>"; // Sluit vakantie-kaart
endforeach;
echo "</div>";
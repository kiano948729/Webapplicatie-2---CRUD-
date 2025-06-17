<?php
global $conn;
require_once __DIR__ . '/../../config/init.php';

// Haal GET-parameters op
$locatie = $_GET['locatie'] ?? '';
$type = $_GET['filter'] ?? '';
$checkIn = $_GET['check-in'] ?? '';
$checkOut = $_GET['check-out'] ?? '';
$sort = $_GET['sort'] ?? '';

// Bouw query
$sql = "SELECT vd.*, a.type, a.location, a.photo_url
        FROM vakantie_deals vd
        JOIN accommodaties a ON vd.accommodation_id = a.accommodation_id
        WHERE 1=1";

$params = [];

if (!empty($locatie)) {
    $sql .= " AND vd.destination LIKE :locatie";
    $params[':locatie'] = "%$locatie%";
}

if (!empty($type)) {
    $sql .= " AND a.type = :type";
    $params[':type'] = $type;
}

// Sortering
switch ($sort) {
    case 'low_to_high':
        $sql .= " ORDER BY vd.price ASC";
        break;
    case 'high_to_low':
        $sql .= " ORDER BY vd.price DESC";
        break;
    default:
        $sql .= " ORDER BY vd.discount DESC"; // Standaard op hoogste korting
        break;
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// JSON retourneren als AJAX-verzoek
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    echo json_encode($results);
    exit;
}
?>

<?php if (count($results) === 0): ?>
    <p>Geen deals gevonden. Pas je zoekcriteria aan.</p>
<?php else: ?>
    <?php foreach ($results as $deal): ?>
        <div class="vakantie-kaart">
            <img src="<?= IMG_PATH . '/' . basename($deal['photo_url']) ?>" alt="<?= htmlspecialchars($deal['destination']) ?>">
            <h3><?= htmlspecialchars($deal['destination']) ?></h3>
            <p>Type: <?= htmlspecialchars($deal['type']) ?></p>
            <p>Locatie: <?= htmlspecialchars($deal['location']) ?></p>
            <p>Beschrijving: <?= nl2br(htmlspecialchars($deal['description'])) ?></p>
            <p><strong>Prijs:</strong> €<?= number_format($deal['price'], 2, ',', '.') ?></p>
            <p><strong>Korting:</strong> <?= htmlspecialchars($deal['discount']) ?>%</p>
            <button class="show-more-btn" data-id="<?= $deal['deal_id'] ?>">Meer info</button>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
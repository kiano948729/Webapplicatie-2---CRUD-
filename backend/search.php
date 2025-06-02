<?php
require 'conn.php';

$locatie = $_GET['locatie'] ?? '';
$checkIn = $_GET['check-in'] ?? '';
$checkOut = $_GET['check-out'] ?? '';
$filter = $_GET['filter'] ?? '';

$sql = "SELECT vd.*, a.type, a.location
        FROM vakantie_deals vd
        JOIN accommodaties a ON vd.accommodation_id = a.accommodation_id
        WHERE 1=1";

$params = [];

if (!empty($locatie)) {
    $sql .= " AND vd.destination LIKE :locatie";
    $params[':locatie'] = "%$locatie%";
}

if (!empty($filter)) {
    $sql .= " AND a.type = :type";
    $params[':type'] = $filter;
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($results) === 0): ?>
    <p>Geen vakantiedeals gevonden. Pas je zoekcriteria aan.</p>
<?php else:
    foreach ($results as $deal): ?>
        <div class="vakantie-kaart">
            <img src="<?= htmlspecialchars($deal['photo_url']) ?>" alt="Foto van <?= htmlspecialchars($deal['destination']) ?>">
            <h3><?= htmlspecialchars($deal['destination']) ?></h3>
            <p><?= htmlspecialchars($deal['description']) ?></p>
            <p><strong>€ <?= htmlspecialchars($deal['price']) ?></strong></p>
        </div>
<?php
    endforeach;
endif;
?>

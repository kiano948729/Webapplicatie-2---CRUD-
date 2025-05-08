<?php
require 'conn.php'; // Zorg ervoor dat de verbinding correct is ingesteld.

$locatie = $_GET['locatie'] ?? '';
$checkIn = $_GET['check-in'] ?? '';
$checkOut = $_GET['check-out'] ?? '';
$deelnemers = $_GET['deelnemers'] ?? '';
$filter = $_GET['filter'] ?? '';

// Bouw de basis SQL query voor vakantie deals
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

if (!empty($deelnemers)) {
    // Stel een filter in op basis van het aantal deelnemers.
    // Dit hangt af van hoe je de capaciteit van de accommodatie hebt gedefinieerd in je database.
    $sql .= " AND vd.deelnemers >= :deelnemers";
    $params[':deelnemers'] = $deelnemers;
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);

// Haal de zoekresultaten op
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="vakanties-main-blok">
    <h2>Search Results</h2>

    <div class="zoek-resultaten">
        <?php if (count($results) === 0): ?>
            <p>No vacation deals found. Please adjust your search criteria.</p>
        <?php else: ?>
            <?php foreach ($results as $deal): ?>
                <div class="vakantie-kaart">
                    <h3><?= htmlspecialchars($deal['destination']) ?></h3>
                    <p><?= htmlspecialchars($deal['description']) ?></p>
                    <img src="<?= htmlspecialchars($deal['photo_url']) ?>" alt="Foto">
                    <p>Price: €<?= htmlspecialchars($deal['price']) ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
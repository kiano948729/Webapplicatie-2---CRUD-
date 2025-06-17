<?php
require 'databaseConnect.php';

// Ontvang GET-parameters
$locatie = $_GET['locatie'] ?? '';
$type = $_GET['filter'] ?? '';
$min_price = $_GET['min_price'] ?? 0;
$max_price = $_GET['max_price'] ?? 1000;
$sort = $_GET['sort'] ?? '';

try {
    // Basis query
    $sql = "SELECT * FROM accommodaties WHERE 1=1";
    $params = [];

    // Filter op locatie
    if (!empty($locatie)) {
        $sql .= " AND location LIKE ?";
        $params[] = "%$locatie%";
    }

    // Filter op type
    if (!empty($type)) {
        $sql .= " AND type = ?";
        $params[] = $type;
    }

    // Filter op min prijs (ook als het 0 is)
    if (isset($_GET['min_price']) && is_numeric($_GET['min_price'])) {
        $sql .= " AND price >= ?";
        $params[] = intval($_GET['min_price']);
    }

    // Filter op max prijs
    if ($max_price > 0) {
        $sql .= " AND price <= ?";
        $params[] = intval($max_price);
    }

    // Sortering
    if ($sort === 'low_to_high') {
        $sql .= " ORDER BY price ASC";
    } elseif ($sort === 'high_to_low') {
        $sql .= " ORDER BY price DESC";
    }

    // Query uitvoeren
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Fout bij ophalen accommodaties: " . $e->getMessage();
}
?>
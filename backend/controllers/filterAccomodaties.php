<?php
// Laad centrale configuratie
global $conn;
require_once __DIR__ . '/../../config/init.php';

$where = [];
$params = [];

// Zoekfilters
if (!empty($_GET['name'])) {
    $where[] = "a.name LIKE :name";
    $params[':name'] = "%" . $_GET['name'] . "%";
}

if (!empty($_GET['type'])) {
    $where[] = "a.type = :type";
    $params[':type'] = $_GET['type'];
}

if (!empty($_GET['min_price'])) {
    $where[] = "a.price >= :min_price";
    $params[':min_price'] = floatval($_GET['min_price']);
}

if (!empty($_GET['max_price'])) {
    $where[] = "a.price <= :max_price";
    $params[':max_price'] = floatval($_GET['max_price']);
}

// Sortering
$sort = $_GET['sort'] ?? '';
switch ($sort) {
    case 'low_to_high':
        $order_by = "a.price ASC";
        break;
    case 'high_to_low':
        $order_by = "a.price DESC";
        break;
    default:
        $order_by = "a.accommodation_id DESC"; // Standaardvolgorde
}

// Bouw SQL-query
$sql = "
    SELECT a.*, 
           COALESCE(d.discount, 0) AS discount,
           COALESCE(d.deal_description, '') AS deal_description
    FROM accommodaties a
    LEFT JOIN vakantie_deals d ON a.accommodation_id = d.accommodation_id
";

if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY $order_by";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Optioneel: retourneer JSON bij AJAX-verzoek
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode($results);
    exit;
}
?>
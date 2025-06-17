    <?php

$where = [];
$params = [];

if (!empty($_GET['name'])) {
    $where[] = "name LIKE :name";
    $params[':name'] = "%" . $_GET['name'] . "%";
}

if (!empty($_GET['type'])) {
    $where[] = "type = :type";
    $params[':type'] = $_GET['type'];
}

if (!empty($_GET['price'])) {
    $where[] = "price <= :price";
    $params[':price'] = $_GET['price'];
}

$sql = "SELECT * FROM accommodaties";
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll();
?>

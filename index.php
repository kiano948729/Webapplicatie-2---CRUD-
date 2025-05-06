<?php
require 'backend/conn.php'; 

$stmt = $conn->prepare("SELECT * FROM users");
$stmt->execute();

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<pre>";
var_dump($users);
echo "</pre>";
?>
<h1>halllloooo mensen</h1>
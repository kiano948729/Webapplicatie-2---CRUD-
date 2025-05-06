<?php
$servername = "db";
$dbname = "reizen"; // Database naam
$username = "root"; // DB-gebruikersnaam
$password = "rootpassword"; // DB-wachtwoord

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Verbinding met database is mislukt: " . $e->getMessage());
}
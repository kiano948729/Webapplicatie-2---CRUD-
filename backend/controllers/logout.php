<?php
// Laad init.php om constante paden beschikbaar te maken
require_once __DIR__ . '/../../config/init.php';

// Start sessie (alleen als deze nog niet is gestart)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Leeg alle sessievariabelen
$_SESSION = [];

// Verwijder de sessie zelf
session_destroy();

// Redirect naar homepage
header("Location: " . ROOT_URL . "/index.php");
exit;
?>
<?php
session_start();
require '../../conn.php';

if (!isset($_GET['id'])) {
    header("Location: ../admin.php");
    exit;
}

$id = (int) $_GET['id'];
$stmt = $conn->prepare("DELETE FROM boekingen WHERE booking_id = :id");
$stmt->execute([':id' => $id]);

header("Location: ../admin.php");
exit;

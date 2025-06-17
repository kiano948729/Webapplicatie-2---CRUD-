<?php
session_start();
require_once __DIR__ . '../../../../config/init.php';

// if (empty($_SESSION['is_admin'])) {
//     header("Location: ../../vakanties.php");
//     exit;
// }

if (!isset($_GET['id'])) {
    header("Location: admin.php");
    exit;
}

$id = (int) $_GET['id'];
$stmt = $conn->prepare("DELETE FROM users WHERE user_id = :id");
$stmt->execute([':id' => $id]);

header("Location: ../admin.php");
exit;

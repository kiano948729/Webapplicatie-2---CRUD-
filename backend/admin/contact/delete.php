<?php
session_start();
require '../../conn.php';

// if (empty($_SESSION['is_admin'])) {
//     header("Location: ../vakanties.php");
//     exit;
// }

if (!isset($_GET['id'])) {
    header("Location: admin.php");
    exit;
}

$id = (int) $_GET['id'];
$stmt = $conn->prepare("DELETE FROM ContactBerichten WHERE Id = :Id");
$stmt->execute([':Id' => $id]);

header("Location: ../admin.php");
exit;

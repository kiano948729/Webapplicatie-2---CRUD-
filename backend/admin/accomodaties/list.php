<?php
require '../conn.php';

if (!isset($conn)) {
    die("Databaseverbinding mislukt!");
}

if (isset($_GET['delete_acco'])) {
    $id = (int) $_GET['delete_acco'];
    $stmt = $conn->prepare("DELETE FROM accommodaties WHERE accommodation_id = :id");
    $stmt->execute([':id' => $id]);
    header("Location: ../index.php");
    exit;
}

$stmt = $conn->query("SELECT * FROM accommodaties");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="section-card">
    <div class="section-header">
        <h2>Accommodaties</h2>
        <a href="accomodaties/create.php" class="btn-primary">Nieuwe accommodatie</a>
    </div>
    <div class="table-wrapper">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Naam</th>
                    <th>Type</th>
                    <th>Locatie</th>
                    <th>Rating</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= $item['accommodation_id'] ?></td>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= htmlspecialchars($item['type']) ?></td>
                        <td><?= htmlspecialchars($item['location']) ?></td>
                        <td class="action-links">
                            <a href="accomodaties/edit.php?id=<?= $item['accommodation_id'] ?>">Bewerken</a>
                            <a href="accomodaties/delete.php?id=<?= $item['accommodation_id'] ?>"
                                onclick="return confirm('Weet u zeker dat u deze accommodatie wilt verwijderen?')">Verwijderen</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
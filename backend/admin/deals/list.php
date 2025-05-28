<?php
$stmt = $conn->query("SELECT * FROM vakantie_deals");
$deals = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="section-card">
    <div class="section-header">
        <h2>Vakantie Deals</h2>
        <a href="deals/create.php" class="btn-primary">Nieuwe deal toevoegen</a>
    </div>
    <div class="table-wrapper">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Bestemming</th>
                    <th>Beschrijving</th>
                    <th>Prijs</th>
                    <th>Datum</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($deals as $deal): ?>
                    <tr>
                        <td><?= $deal['deal_id'] ?></td>
                        <td><?= htmlspecialchars($deal['destination']) ?></td>
                        <td><?= substr(htmlspecialchars($deal['description']), 0, 50) . (strlen($deal['description']) > 50 ? '...' : '') ?></td>
                        <td>&euro; <?= number_format($deal['price'], 2, ',', '.') ?></td>
                        <td><?= date('d-m-Y', strtotime($deal['created_at'])) ?></td>
                        <td class="action-links">
                            <a href="deals/edit.php?id=<?= $deal['deal_id'] ?>">Bewerken</a>
                            <a href="deals/delete.php?id=<?= $deal['deal_id'] ?>" onclick="return confirm('Weet u zeker dat u deze deal wilt verwijderen?')">Verwijderen</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
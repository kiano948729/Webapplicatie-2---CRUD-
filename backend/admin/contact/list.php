<?php
$stmt = $conn->query("SELECT * FROM ContactBerichten");
$deals = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="section-card">
    <div class="section-header">
        <h2>Contact Berichten</h2>
    </div>
    <div class="table-wrapper">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Naam</th>
                    <th>Achternaam</th>
                    <th>Email</th>
                    <th>Nummer</th>
                    <th>Bericht</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($deals as $deal): ?>
                    <tr>
                        <td><?= $deal['Id'] ?></td>
                        <td><?= htmlspecialchars($deal['Naam']) ?></td>
                        <td><?= htmlspecialchars($deal['Achternaam']) ?></td>
                        <td><?= htmlspecialchars($deal['Email']) ?></td>
                        <td><?= htmlspecialchars($deal['Nummer']) ?></td>
                        <td><?= htmlspecialchars($deal['Bericht']) ?></td>
                        <td class="action-links">
                            <a href="contact/edit.php?id=<?= $deal['Id'] ?>">Bewerken</a>
                            <a href="contact/delete.php?id=<?= $deal['Id'] ?>" onclick="return confirm('Weet u zeker dat u dit bericht wilt verwijderen?')">Verwijderen</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
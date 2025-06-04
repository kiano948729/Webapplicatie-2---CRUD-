<?php
// Ophalen van alle geboekte accommodaties
$stmt = $conn->query("
    SELECT 
    ba.boeking_accommodatie_id,
    ba.start_date,
    ba.end_date,
    ba.total_price,
    ba.aantal_personen,
    u.username,
    u.email,
    a.name AS accommodation_name
FROM boeking_accommodaties ba
INNER JOIN users u ON ba.user_id = u.user_id
INNER JOIN accommodaties a ON ba.accommodation_id = a.accommodation_id

");

$accommodatieBoekingen = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="section-card">
    <div class="section-header">
        <h2>Geboekte Accommodaties</h2>
    </div>
    <div class="table-wrapper">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Boeking ID</th>
                    <th>Gebruiker</th>
                    <th>Email</th>
                    <th>Accommodatie</th>
                    <th>Startdatum</th>
                    <th>Einddatum</th>
                    <th>Aantal personen</th>
                    <th>Totale Prijs</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($accommodatieBoekingen as $boeking): ?>
                    <tr>
                        <td><?= htmlspecialchars($boeking['boeking_accommodatie_id']) ?></td>
                        <td><?= htmlspecialchars($boeking['username']) ?></td>
                        <td><?= htmlspecialchars($boeking['email']) ?></td>
                        <td><?= htmlspecialchars($boeking['accommodation_name']) ?></td>
                        <td><?= date('d-m-Y', strtotime($boeking['start_date'])) ?></td>
                        <td><?= date('d-m-Y', strtotime($boeking['end_date'])) ?></td>
                        <td><?= htmlspecialchars($boeking['aantal_personen']) ?></td>
                        <td>&euro; <?= number_format($boeking['total_price'], 2, ',', '.') ?></td>
                        <td class="action-links">
                            <a
                                href="boekingen_accommodaties/edit.php?id=<?= $boeking['boeking_accommodatie_id'] ?>">Bewerken</a>
                            <a href="boekingen_accommodaties/delete.php?id=<?= $boeking['boeking_accommodatie_id'] ?>"
                                onclick="return confirm('Weet u zeker dat u deze boeking wilt verwijderen?')">Verwijderen</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</div>
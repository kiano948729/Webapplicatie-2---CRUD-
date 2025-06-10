<?php
// Verbind met de database
// $conn moet al gedefinieerd zijn
$stmt = $conn->query("
    SELECT 
        b.booking_id,
        b.booking_date,
        b.start_date,
        b.end_date,
        b.total_price,
        b.aantal_personen,
        u.username,
        u.email,
        d.destination
    FROM boekingen b
    INNER JOIN users u ON b.user_id = u.user_id
    INNER JOIN vakantie_deals d ON b.deal_id = d.deal_id
");
$boekingen = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>      

<div class="section-card">
    <div class="section-header">
        <h2>Boekingen</h2>
    </div>
    <div class="table-wrapper">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Gebruiker</th>
                    <th>Email</th>
                    <th>Bestemming</th>
                    <th>Boekingsdatum</th>
                    <th>Startdatum</th>
                    <th>Einddatum</th>
                    <th>Aantal personen</th>
                    <th>Totale Prijs</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($boekingen as $boeking): ?>
                    <tr>
                        <td><?= htmlspecialchars($boeking['booking_id']) ?></td>
                        <td><?= htmlspecialchars($boeking['username']) ?></td>
                        <td><?= htmlspecialchars($boeking['email']) ?></td>
                        <td><?= htmlspecialchars($boeking['destination']) ?></td>
                        <td><?= date('d-m-Y H:i', strtotime($boeking['booking_date'])) ?></td>
                        <td><?= date('d-m-Y', strtotime($boeking['start_date'])) ?></td>
                        <td><?= date('d-m-Y', strtotime($boeking['end_date'])) ?></td>
                        <td><?= htmlspecialchars($boeking['aantal_personen']) ?></td>
                        <td>&euro; <?= number_format($boeking['total_price'], 2, ',', '.') ?></td>
                        <td class="action-links">
                            <a href="boekingen_deals/edit.php?id=<?= $boeking['booking_id'] ?>">Bewerken</a>
                            <a href="boekingen_deals/delete.php?id=<?= $boeking['booking_id'] ?>"
                                onclick="return confirm('Weet u zeker dat u deze boeking wilt verwijderen?')">Verwijderen</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</div>
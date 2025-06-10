<?php
// Verbind met de database
// $conn moet al gedefinieerd zijn

$stmt = $conn->query("
    SELECT 
        r.review_id,
        r.comment,
        r.rating,
        r.approved,
        r.review_date,
        u.username,
        u.email,
        a.name AS accommodation_name
    FROM accommodatie_reviews r
    INNER JOIN users u ON r.user_id = u.user_id
    INNER JOIN accommodaties a ON r.accommodation_id = a.accommodation_id
");
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="section-card">
    <div class="section-header">
        <h2>Recensies</h2>
    </div>
    <div class="table-wrapper">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Review ID</th>
                    <th>Gebruiker</th>
                    <th>Email</th>
                    <th>Accommodatie</th>
                    <th>Datum</th>
                    <th>Beoordeling</th>
                    <th>Status</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reviews as $review): ?>
                    <tr>
                        <td><?= htmlspecialchars($review['review_id']) ?></td>
                        <td><?= htmlspecialchars($review['username']) ?></td>
                        <td><?= htmlspecialchars($review['email']) ?></td>
                        <td><?= htmlspecialchars($review['accommodation_name']) ?></td>
                        <td><?= date('d-m-Y H:i', strtotime($review['review_date'])) ?></td>
                        <td><?= htmlspecialchars($review['rating']) ?>/5</td>
                        <td>
                            <?php if ($review['approved']): ?>
                                <span class="status approved">Goedgekeurd</span>
                            <?php else: ?>
                                <span class="status pending">In afwachting</span>
                            <?php endif; ?>
                        </td>
                        <td class="action-links">
                            <a href="reviews/edit.php?id=<?= $review['review_id'] ?>">Bewerken</a>
                            <a href="reviews/delete.php?id=<?= $review['review_id'] ?>"
                                onclick="return confirm('Weet u zeker dat u deze review wilt verwijderen?')">Verwijderen</a>
                            <?php if (!$review['approved']): ?>
                                <a href="reviews/approve.php?id=<?= $review['review_id'] ?>"
                                    onclick="return confirm('Wilt u deze review goedkeuren?')">Goedkeuren</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
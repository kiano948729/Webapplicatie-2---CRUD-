<?php
require '../conn.php';
if (isset($_GET['delete_user'])) {
    $id = (int)$_GET['delete_user'];
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = :id");
    $stmt->execute([':id' => $id]);
    header("Location: ../index.php");
    exit;
}

$stmt = $conn->query("SELECT user_id, username, email FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="section-card">
    <div class="section-header">
        <h2>Gebruikers</h2>
        <a href="users/create.php" class="btn-primary">Nieuwe gebruiker</a>
    </div>
    <div class="table-wrapper">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Gebruikersnaam</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['user_id'] ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><span class="badge badge-success">Actief</span></td>
                        <td class="action-links">
                            <a href="users/delete.php?id=<?= $user['user_id'] ?>" onclick="return confirm('Weet u zeker dat u deze gebruiker wilt verwijderen?')">Verwijderen</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
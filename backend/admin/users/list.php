<?php
require_once __DIR__ . '../../../../config/init.php';

// Alleen toegankelijk voor admins
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: " . FRONTEND_URL . "/templates/vakanties.php");
    exit;
}

// Verwijder gebruiker (via GET)
if (isset($_GET['delete_user'])) {
    $user_id = (int)$_GET['delete_user'];
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);

    // Redirect naar admin-dashboard
    header("Location: " . BACKEND_URL . "/admin/admin.php");
    exit;
}

// Haal gebruikers op
$stmt = $conn->query("SELECT user_id, username, email FROM users ORDER BY user_id DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="section-card">
    <div class="section-header">
        <h2>Gebruikers</h2>
        <a href="<?= BACKEND_URL ?>/admin/users/create.php" class="btn-primary">Nieuwe gebruiker</a>
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
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['user_id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><span class="badge badge-success">Actief</span></td>
                        <td class="action-links">
                            <a href="<?= BACKEND_URL ?>/admin/users/delete.php?id=<?= $user['user_id'] ?>" onclick="return confirm('Weet u zeker dat u deze gebruiker wilt verwijderen?')">Verwijderen</a>
                            <a href="<?= BACKEND_URL ?>/admin/users/edit.php?id=<?= $user['user_id'] ?>" >Bewerken</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align:center;">Geen gebruikers gevonden</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
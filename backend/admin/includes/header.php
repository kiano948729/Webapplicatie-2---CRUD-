<?php
// Alleen tonen als gebruiker is ingelogd als admin
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: " . TEMPLATES_URL . "/vakanties.php");
    exit;
}
?>
<header class="admin-header">
    <div class="container">
        <h1>Reisbureau Admin Paneel</h1>
        <nav class="admin-nav">
            <!-- Dynamische active class -->
            <a href="<?= BACKEND_URL ?>/admin/admin.php" class="<?= ($_SERVER['REQUEST_URI'] === '/backend/admin/admin.php') ? 'active' : '' ?>">Dashboard</a>
            <a href="<?= TEMPLATES_URL ?>/vakanties.php" target="_blank">Website Bekijken</a>
            <a href="<?= BACKEND_URL ?>/admin/create.php" class="<?= ($_SERVER['REQUEST_URI'] === '/backend/admin/create.php') ? 'active' : '' ?>">Nieuwe Admin</a>
            <a href="<?= BACKEND_URL ?>/admin/logout.php" class="logout-btn">Uitloggen</a>
        </nav>
    </div>
</header>
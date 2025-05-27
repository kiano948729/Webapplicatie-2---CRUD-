<?php
// if (empty($_SESSION['is_admin'])) {
//     header("Location: ../../vakanties.php");
//     exit;
// }
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Database verbinding
require '../conn.php';

if (!isset($conn)) {
    die("Databaseverbinding niet geladen.");
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Adminpaneel - Reisbureau</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin_css/admin.css">
</head>
<body class="admin-body">
    <?php include 'includes/header.php'; ?>
    
    <main class="dashboard-container">
        <div class="tabs">
            <button class="tab-button active" data-tab="users">Gebruikers</button>
            <button class="tab-button" data-tab="deals">Vakantie Deals</button>
            <button class="tab-button" data-tab="accommodations">Accommodaties</button>
        </div>

        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Zoeken in alle tabellen...">
        </div>

        <div class="content">
            <section id="users-section" class="tab-content active">
                <?php include 'users/list.php'; ?>
            </section>

            <section id="deals-section" class="tab-content">
                <?php include 'deals/list.php'; ?>
            </section>

            <section id="accommodations-section" class="tab-content">
                <?php include 'accomodaties/list.php'; ?>
            </section>
        </div>
    </main>

    <footer class="admin-footer">
        <p>&copy; <?php echo date('Y'); ?> Reisbureau Admin Systeem</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
<?php
// if (empty($_SESSION['is_admin'])) {
//      header("Location: ../../vakanties.php");
//      exit;
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
    <script src="script.js"></script>
</head>

<body class="admin-body">

    <?php include 'includes/header.php'; ?>

    <main class="dashboard-container">
        <div class="tabs">
            <button class="tab-button active" data-tab="users">Gebruikers</button>
            <button class="tab-button" data-tab="deals">Vakantie Deals</button>
            <button class="tab-button" data-tab="accommodations">Accommodaties</button>
            <button class="tab-button" data-tab="boeking_deal">Boekingen deals</button>
            <button class="tab-button" data-tab="boeking_accomodatie">Boekingen accommodaties</button>
            <button class="tab-button" data-tab="addomodatie_review">review accommodaties</button>
            <button class="tab-button" data-tab="Contact_bericht">Contact berichten</button>
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

            <section id="boeking_deal-section" class="tab-content">
                <?php include 'boekingen_deals/list.php'; ?>
            </section>

            <section id="boeking_accomodatie-section" class="tab-content">
                <?php include 'boekingen_accomodaties/list.php'; ?>
            </section>

            <section id="addomodatie_review-section" class="tab-content">
                <?php include 'reviews/list.php'; ?>
            </section>

            <section id="Contact_bericht-section" class="tab-content">
                <?php include 'contact/list.php'; ?>
            </section>

        </div>
    </main>

    <footer class="admin-footer">
        <p>&copy; <?php echo date('Y'); ?> Reisbureau Admin Systeem</p>
    </footer>

</body>

</html>
<?php
// Zorg dat init.php slechts 1x wordt geladen
global $current_user;
if (!defined('ROOT_PATH')) {
    // We gaan uit van maximaal 3 niveaus naar boven naar 'config/init.php'
    require_once __DIR__ . '/../../../../config/init.php';
}
?>

<!-- Navigatie -->
<nav>
    <div class="index-nav">
        <!-- Logo -->
        <div class="index-Frame-Logo">
            <a href="<?= ROOT_URL ?>/index.php">
                <img class="logoCompass" src="<?= IMG_URL ?>/CompassLogo.png" alt="Logo">
            </a>
        </div>

        <!-- Hoofdnavigatie -->
        <div class="index-main-nav">
            <a href="<?= TEMPLATES_URL ?>/vakanties.php">Vakanties</a>
            <a>●</a>
            <a href="<?= TEMPLATES_URL ?>/overOns.php">Over ons</a>
            <a>●</a>
            <a href="<?= TEMPLATES_URL ?>/contact.php">Contact</a>
        </div>

        <!-- Login / Account -->
        <div class="index-Frame-Login">
            <?php if ($current_user): ?>
                <span>Welkom, <?= htmlspecialchars($current_user) ?></span>
                <a href="<?= CONTROLLERS_URL ?>/logout.php">Uitloggen</a>
                <a href="<?= TEMPLATES_URL ?>/Account.php">Account gegevens</a>
            <?php else: ?>
                <a href="<?= TEMPLATES_URL ?>/login.php">Inloggen</a>
                <a href="<?= TEMPLATES_URL ?>/registreren.php">Registreren</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
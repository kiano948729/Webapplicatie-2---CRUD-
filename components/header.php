<?php
global $current_user;
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav>
    <div class="index-nav">
        <div class="index-Frame-Logo">
            <a href="../index.php">logo</a>
        </div>
        <div class="index-main-nav">
            <a href="../vakanties.php">Vakanties</a>
            <a>●</a>
            <a href="../overOns.php">Over ons</a>
             <a>●</a>
            <a href="../contact.php">Contact</a>
        </div>
        <div class="index-Frame-Login">
            <?php if ($current_user): ?>
                <span>Welkom, <?php echo htmlspecialchars($current_user); ?></span>
                <a href="../backend/logout.php">Uitloggen</a>
            <?php else: ?>
                <a href="../backend/login.php">Inloggen</a>
                <a href="../backend/registreren.php">Registreren</a>
                <a href="../backend/guest.php">Gast</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

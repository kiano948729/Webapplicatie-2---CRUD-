<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']);

$current_user = null;

// Get username from session
if (isset($_SESSION['username'])) {
    $current_user = $_SESSION['username'];
}
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
                <a href="../login.php">Inloggen</a>
                <a href="../registreren.php">Registreren</a>
                <a href="../backend/guest.php">Gast</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<?php
// Laad centrale configuratie
require_once __DIR__ . '/../../config/init.php';

// Controleer of gebruiker is ingelogd als admin
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: " . TEMPLATES_URL . "/login.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $email = trim($_POST["email"]) ?: null;

    // Basisvalidatie
    if (empty($username) || empty($password)) {
        $error = "Gebruikersnaam en wachtwoord zijn verplicht.";
    } else {
        // Wachtwoord hashen
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Admin toevoegen
            $stmt = $conn->prepare("INSERT INTO admins (username, password, email) VALUES (?, ?, ?)");
            $stmt->execute([$username, $hashedPassword, $email]);

            $success = "Nieuwe admin succesvol toegevoegd!";
        } catch (PDOException $e) {
            error_log("Admin fout: " . $e->getMessage());
            $error = "Er ging iets mis. Probeer opnieuw.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Nieuwe Admin Toevoegen</title>
    <link rel="stylesheet" href="admin_css/admin.css">
</head>
<body class="admin-body">

<header>
    <?php include 'includes/header.php'; ?>
</header>

<main class="admin-main">


    <section class="admin-content">
        <h1>Voeg nieuwe admin toe</h1>

        <!-- Foutmelding -->
        <?php if (!empty($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Succesmelding -->
        <?php if (!empty($success)): ?>
            <div class="success-message"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <!-- Formulier -->
        <form method="POST">
            <label for="username">Gebruikersnaam:</label><br>
            <input type="text" id="username" name="username" required><br><br>

            <label for="password">Wachtwoord:</label><br>
            <input type="password" id="password" name="password" required><br><br>

            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br><br>

            <button type="submit">Toevoegen</button>
        </form>
    </section>
</main>
</body>
</html>
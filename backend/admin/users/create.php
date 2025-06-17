<?php
session_start();
require_once __DIR__ . '../../../../config/init.php';

// Als het formulier is ingediend
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $lastName = trim($_POST['lastName'] ?? '');
    $dateOfBirth = $_POST['dateOfBirth'] ?? null;
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);

    // Validatie (simpel)
    if (empty($username) || empty($password) || empty($email)) {
        $error = "Alle verplichte velden moeten worden ingevuld.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Ongeldig e-mailadres.";
    } else {
        // Wachtwoord hashen
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Gebruiker toevoegen
            $stmt = $conn->prepare("INSERT INTO users (username, lastName, dateOfBirth, password, email) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$username, $lastName, $dateOfBirth, $hashedPassword, $email]);

            // Redirect naar lijst na succes
            header("Location: ../admin.php");
            exit;
        } catch (PDOException $e) {
            $error = "Fout: " . $e->getMessage(); 
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Gebruiker Toevoegen</title>
    <link rel="stylesheet" href="../../style.css">
</head>

<body class="admin-body">
    <?php include '../includes/header.php'; ?>

    <div class="dashboard-container">
        <h2>Nieuwe gebruiker aanmaken</h2>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" class="form-container">
            <div class="form-group">
                <label for="username">Gebruikersnaam *</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="lastName">Achternaam</label>
                <input type="text" id="lastName" name="lastName">
            </div>

            <div class="form-group">
                <label for="dateOfBirth">Geboortedatum</label>
                <input type="date" id="dateOfBirth" name="dateOfBirth">
            </div>

            <div class="form-group">
                <label for="email">E-mailadres *</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Wachtwoord *</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn-primary">Opslaan</button>
        </form>
    </div>

    <footer class="admin-footer">
        <p>&copy; <?= date('Y'); ?> Reisbureau Admin Systeem</p>
    </footer>
</body>

</html>
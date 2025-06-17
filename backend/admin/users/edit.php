<?php
require_once __DIR__ . '../../../../config/init.php';

// Controleer of gebruiker admin is
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    die("Je hebt geen toestemming om deze pagina te bekijken.");
}

// Controleer of er een user_id is meegegeven
if (!isset($_GET['id'])) {
    die("Geen gebruiker ID opgegeven.");
}

$user_id = (int)$_GET['id'];

// Haal de gebruiker op uit de database
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Gebruiker niet gevonden.");
}

// Verwerk POST-verzoek (formulier-opslag)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update':
                // Bewerk gebruikersnaam en email
                $username = trim($_POST['username']);
                $email = trim($_POST['email']);

                if (empty($username) || empty($email)) {
                    die("Alle velden zijn verplicht.");
                }

                $stmt = $conn->prepare("UPDATE users SET username = :username, email = :email WHERE user_id = :id");
                $stmt->execute([
                    ':username' => $username,
                    ':email' => $email,
                    ':id' => $user_id
                ]);
                break;

            case 'delete':
                // Verwijder de gebruiker
                $stmt = $conn->prepare("DELETE FROM users WHERE user_id = :id");
                $stmt->execute([':id' => $user_id]);

                header("Location: ../admin.php");
                exit;

            case 'reset_password':
                // Optioneel: Stel nieuw wachtwoord in
                $new_password = $_POST['new_password'] ?? '';
                if (empty($new_password)) {
                    die("Voer een nieuw wachtwoord in.");
                }

                $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET password = :password WHERE user_id = :id");
                $stmt->execute([
                    ':password' => $hashedPassword,
                    ':id' => $user_id
                ]);
                break;
        }
    }

    // Herlaad pagina om updates te tonen
    header("Location: edit.php?id=$user_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Gebruiker Bewerken</title>
    <link rel="stylesheet" href="../admin_css/admin.css">
</head>
<body class="admin-body">

<header>

</header>

<main class="admin-main">

    <section class="admin-content">
        <h1>Gebruiker Bewerken</h1>

        <p><strong>ID:</strong> <?= htmlspecialchars($user['user_id']) ?></p>
        <p><strong>Status:</strong>
            <span class="badge badge-success">Actief</span>
        </p>

        <!-- Formulier: Bewerk Gebruiker -->
        <form method="post">
            <label for="username">Gebruikersnaam:</label><br>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required><br><br>

            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br><br>

            <button type="submit">Wijzigen</button>
            <input type="hidden" name="action" value="update">
        </form>

        <!-- Formulier: Reset Wachtwoord -->
        <form method="post" style="margin-top: 20px;">
            <label for="new_password">Nieuw wachtwoord:</label><br>
            <input type="password" id="new_password" name="new_password"><br><br>

            <button type="submit">Reset Wachtwoord</button>
            <input type="hidden" name="action" value="reset_password">
        </form>

        <!-- Formulier: Verwijder Gebruiker -->
        <form method="post" style="margin-top: 20px;" onsubmit="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?');">
            <input type="hidden" name="action" value="delete">
            <button type="submit" class="btn-danger">Verwijderen</button>
        </form>

        <br>
        <a href="../admin.php">« Terug naar gebruikerslijst</a>
    </section>
</main>
</body>
</html>
<?php
// Laad centrale configuratie
require_once __DIR__ . '../../../config/init.php';

// Controleer login
if (!isset($_SESSION["user_id"])) {
    header("Location: ../../../index.php");
    exit();
}

// Haal gebruikersgegevens op
$current_user = $lastName = $email = $registration_date = $user_id = null;

if (isset($_SESSION['user_id'])) {
    $query = "SELECT username, registration_date, user_id, email, lastName FROM users WHERE user_id = :id";
    $statement = $conn->prepare($query);
    $statement->execute([':id' => $_SESSION['user_id']]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $current_user = $user['username'];
        $registration_date = $user['registration_date'];
        $user_id = $user['user_id'];
        $lastName = $user['lastName'];
        $email = $user['email'];
    }
}

// Update logica
if (isset($_POST['Verstuur'])) {
    $query = "UPDATE users SET username = :username, email = :email, lastName = :lastName";
    $params = [
        ':username' => $_POST['username'],
        ':email' => $_POST['email'] ?? '',
        ':lastName' => $_POST['lastName'] ?? '',
        ':id' => $user_id
    ];

    if (!empty($_POST['password'])) {
        $query .= ", password = :password";
        $params[':password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }

    $query .= " WHERE user_id = :id";
    $stmt = $conn->prepare($query);
    $stmt->execute($params);

    // Redirect om geüpdatete data te tonen
    header("Location: VeranderGegevens.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Account | <?= htmlspecialchars($current_user ?? 'Naam') ?></title>
    <link rel="icon" href="<?= IMG_URL ?>/CompassLogo.png" type="image/png" />
    <link rel="stylesheet" href="<?= CSS_URL ?>/JoeStyle.css" />
    <link rel="stylesheet" href="<?= CSS_URL ?>/style.css" />
    <script src="https://kit.fontawesome.com/5321476408.js"  crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>
<header>
    <?php include PUBLIC_PATH . '/components/header.php'; ?>
</header>

<main>
    <div class="persoonlijkeInformatieRijGegevens">
        <form method="post">
            <div class="topRijInfo">
                <h1 class="informatieTitel">Persoonlijke Informatie</h1>
                <a href="Account.php">
                    <button class="filter-knop-account" type="button">
                        <h2 class="Witte-Text">Terug</h2>
                        <i id="Icon" class="fa-solid fa-left-long"></i>
                    </button>
                </a>
                <a>
                    <button class="filter-knop-account" name="Verstuur" type="submit">
                        <h2 class="Witte-Text">Bewerk</h2>
                        <i id="Icon" class="fa-solid fa-check"></i>
                    </button>
                </a>
            </div>

            <div class="tweedeRijInfo">
                <div class="informatieFrame">
                    <h2 class="grijsText">Gebruikersnaam</h2>
                    <input class="Toevoegen-Rij" value="<?= htmlspecialchars($current_user ?? '') ?>" type="text" name="username" required>
                </div>
                <div class="informatieFrame">
                    <h2 class="grijsText">Achternaam</h2>
                    <input class="Toevoegen-Rij" value="<?= htmlspecialchars($lastName ?? '') ?>" type="text" name="lastName">
                </div>
                <div class="informatieFrame">
                    <h2 class="grijsText">Nieuw wachtwoord</h2>
                    <input class="Toevoegen-Rij" value="" type="password" name="password" placeholder="Laat leeg om niet te wijzigen">
                </div>
            </div>

            <div class="tweedeRijInfo">
                <div class="informatieFrame">
                    <h2 class="grijsText">Email</h2>
                    <input class="Toevoegen-Rij" value="<?= htmlspecialchars($email ?? '') ?>" type="email" name="email" required>
                </div>
                <div class="informatieFrame">
                    <h2 class="grijsText">Registratiedatum</h2>
                    <h2><?= htmlspecialchars($registration_date ?? '') ?></h2>
                </div>
                <div class="informatieFrame">
                    <h2 class="grijsText">GebruikerID</h2>
                    <h2><?= htmlspecialchars($user_id ?? '') ?></h2>
                </div>
            </div>
        </form>
    </div>
</main>

<footer></footer>
</body>
</html>
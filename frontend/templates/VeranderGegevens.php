<?php
session_start();
require_once 'backend/databaseConnect.php';
global $conn;

if (!isset($_SESSION["Gebruiker"])) {
    header("Location: Index.php");
    exit();
}

$current_user = null;
$registration_date = null;
$user_id = null;
$lastName = null;
$email = null;

// Fetch user info from session
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

// Update logic
if (isset($_POST['Verstuur'])) {
    $query = "UPDATE users SET username = :username, email = :email, lastName = :lastName";
    $params = [
        ':username' => $_POST['username'],
        ':email' => $_POST['email'],
        ':lastName' => $_POST['lastName'],
        ':id' => $user_id
    ];

    if (!empty($_POST['password'])) {
        $query .= ", password = :password";
        $params[':password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }

    $query .= " WHERE user_id = :id";
    $stmt = $conn->prepare($query);
    $stmt->execute($params);

    // Optional: refresh displayed user data
    header("Location: VeranderGegevens.php"); // Prevent form resubmission
    exit();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="google" content="notranslate" />
    <title>Account | <?php echo htmlspecialchars($current_user ?? 'Naam'); ?></title>
    <link rel="stylesheet" href="../public/css/JoeStyle.css" />
    <link rel="stylesheet" href="../public/css/style.css" />
    <link rel="icon" href="../img/CompassLogo.png" type="image/png" />
    <script src="https://kit.fontawesome.com/5321476408.js" crossorigin="anonymous"></script>
    <script src="script.js" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet" />
</head>

<body>
<header>
    <?php require_once("components/header.php") ?>
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
                    <input class="Toevoegen-Rij" value="<?php echo htmlspecialchars($current_user); ?>" type="text" name="username" required>
                </div>
                <div class="informatieFrame">
                    <h2 class="grijsText">Achternaam</h2>
                    <input class="Toevoegen-Rij" value="<?php echo htmlspecialchars($lastName); ?>" type="text" name="lastName" required>
                </div>
                <div class="informatieFrame">
                    <h2 class="grijsText">Nieuw wachtwoord</h2>
                    <input class="Toevoegen-Rij" value="" type="password" name="password" placeholder="Laat leeg om niet te wijzigen">
                </div>
            </div>

            <div class="tweedeRijInfo">
                <div class="informatieFrame">
                    <h2 class="grijsText">Email</h2>
                    <input class="Toevoegen-Rij" value="<?php echo htmlspecialchars($email); ?>" type="email" name="email" required>
                </div>
                <div class="informatieFrame">
                    <h2 class="grijsText">Registratiedatum</h2>
                    <h2><?php echo htmlspecialchars($registration_date); ?></h2>
                </div>
                <div class="informatieFrame">
                    <h2 class="grijsText">GebruikerID</h2>
                    <h2><?php echo htmlspecialchars($user_id); ?></h2>
                </div>
            </div>
        </form>
    </div>
</main>

<footer></footer>
</body>
</html>

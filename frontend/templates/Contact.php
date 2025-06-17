<?php
// Laad init.php
require_once __DIR__ . '/../../config/init.php';

$error = '';
$success = '';

if (isset($_POST["Registreren-Knop"])) {
    $naam = trim($_POST['Naam']);
    $achternaam = trim($_POST['Achternaam']);
    $email = trim($_POST['Email']);
    $telefoonnummer = trim($_POST['Nummer']);
    $bericht = trim($_POST['Bericht']);

    // Valideer invoer
    if (empty($naam) || empty($achternaam) || empty($email) || empty($bericht)) {
        $error = "Alle * verplichte velden moeten worden ingevuld.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Ongeldig e-mailadres.";
    } elseif (!empty($telefoonnummer) && !preg_match("/^[0-9\s\-\+]+$/", $telefoonnummer)) {
        $error = "Alleen cijfers, +, - of spaties zijn toegestaan in het telefoonnummer.";
    } else {
        try {
            // Voeg contactbericht toe
            $sql = "INSERT INTO ContactBerichten (Naam, Achternaam, Email, Nummer, Bericht)
                    VALUES (:Naam, :Achternaam, :Email, :Nummer, :Bericht)";
            $statement = $conn->prepare($sql);
            $statement->execute([
                ':Naam' => $naam,
                ':Achternaam' => $achternaam,
                ':Email' => $email,
                ':Nummer' => $telefoonnummer,
                ':Bericht' => $bericht
            ]);

            $success = "Bedankt voor je bericht!";
        } catch (PDOException $e) {
            error_log("Fout bij contactformulier: " . $e->getMessage());
            $error = "Er ging iets mis. Probeer het later opnieuw.";
        }
    }

    // Sla fout/succes op in sessie
    if (!empty($error)) {
        $_SESSION['error'] = $error;
    }

    if (!empty($success)) {
        $_SESSION['success'] = $success;
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Contact | Backpack & Go</title>
    <link rel="stylesheet" href="<?= CSS_URL ?>/style.css">
    <link rel="stylesheet" href="<?= CSS_URL ?>/JoeStyle.css">
    <link rel="icon" href="<?= IMG_URL ?>/CompassLogo.png" type="image/png">
    <script src="https://kit.fontawesome.com/5321476408.js"  crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
</head>
<body>

<header>
    <?php include PUBLIC_PATH . '/components/header.php'; ?>
</header>

<main>
    <div class="Afbeelding-Achtergrond-Login">
        <div class="loginFrameRij">
            <div class="contactFrame">
                <div class="handLogoFrame">
                    <i id="TelefoonLogo" class="fa-solid fa-phone"></i>
                </div>
                <div class="textFrameLogin">
                    <h1 class="grijsText">Contact ons</h1>
                </div>

                <!-- Foutmelding -->
                <?php if (!empty($error)): ?>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <!-- Succesmelding -->
                <?php if (!empty($success)): ?>
                    <div class="success-message"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <!-- Formulier -->
                <form action="<?= TEMPLATES_URL ?>/Contact.php" method="post">
                    <div class="contactRijNaam">
                        <input class="contactInputNaam" name="Naam" placeholder="Naam" type="text"
                               value="<?= htmlspecialchars($_POST['Naam'] ?? '') ?>" required>
                        <input class="contactInputNaam" name="Achternaam" placeholder="Achternaam" type="text"
                               value="<?= htmlspecialchars($_POST['Achternaam'] ?? '') ?>" required>
                    </div>
                    <div class="contactRijNaam">
                        <input class="contactInputNaam" name="Email" placeholder="Email" type="email"
                               value="<?= htmlspecialchars($_POST['Email'] ?? '') ?>" required>
                        <input class="contactInputNaam" name="Nummer" placeholder="Telefoonnummer" type="tel"
                               pattern="[0-9\s\-+]+"
                               value="<?= htmlspecialchars($_POST['Nummer'] ?? '') ?>">
                    </div>
                    <div class="contactRijNaam">
                        <textarea class="contactInputBericht" name="Bericht" placeholder="Typ je bericht..."
                                  rows="5" required><?= htmlspecialchars($_POST['Bericht'] ?? '') ?></textarea>
                    </div>
                    <button class="filter-knop" name="Registreren-Knop" type="submit">
                        <h2 class="Witte-Text">Verstuur</h2>
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>
</body>
</html>
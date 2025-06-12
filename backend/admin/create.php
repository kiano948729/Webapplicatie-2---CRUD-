<?php
session_start();
require '../conn.php';
// Formulierverwerking
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';
    $email = $_POST["email"] ?? '';

    // Basisvalidatie
    if (empty($username) || empty($password)) {
        echo "Gebruikersnaam en wachtwoord zijn verplicht.";
        exit;
    }

    // Wachtwoord hashen
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Admin toevoegen via prepared statement
    $stmt = $conn->prepare("INSERT INTO admins (username, password, email) VALUES (?, ?, ?)");
    try {
        $stmt->execute([$username, $hashedPassword, $email]);
        echo "Nieuwe admin succesvol toegevoegd!";
    } catch (PDOException $e) {
        echo "Fout bij toevoegen: " . $e->getMessage();
    }
}
?>

<!-- HTML Formulier -->
<!DOCTYPE html>
<html>

<head>
    <title>Nieuwe Admin Toevoegen</title>
</head>

<body>
    <header>
        <a href="admin.php">Dashboard</a>
    </header>
    <h2>Admin Toevoegen</h2>
    <form method="POST">
        <label>Gebruikersnaam:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Wachtwoord:</label><br>
        <input type="password" name="password" required><br><br>

        <label>Email (optioneel):</label><br>
        <input type="email" name="email"><br><br>

        <input type="submit" value="Toevoegen">
    </form>
</body>

</html>
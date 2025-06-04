<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];
    $email = $_POST['email'];
    $haslo = $_POST['haslo'];

    // Sprawdź, czy email już istnieje
    $stmt = $conn->prepare("SELECT klient_id FROM klient WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "Użytkownik z tym adresem e-mail już istnieje.";
    } else {
        // Wstawienie nowego klienta
        $haslo_hash = hash('sha256', $haslo);
        $stmt = $conn->prepare("INSERT INTO klient (imie, nazwisko, email, haslo) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $imie, $nazwisko, $email, $haslo_hash);

        if ($stmt->execute()) {
            $success = "Rejestracja zakończona sukcesem! Możesz się teraz zalogować.";
        } else {
            $error = "Błąd podczas rejestracji.";
        }
    }
}
include 'header.php';
?>

<div class="container mt-4">
    <h2>Rejestracja klienta</h2>

    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Imię:</label>
            <input type="text" name="imie" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Nazwisko:</label>
            <input type="text" name="nazwisko" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Hasło:</label>
            <input type="password" name="haslo" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Zarejestruj się</button>
    </form>
</div>

<?php include 'footer.php'; ?>

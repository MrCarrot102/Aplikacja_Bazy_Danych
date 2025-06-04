<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $haslo = $_POST['haslo'];

    // ✅ 1. Najpierw sprawdzamy PRACOWNIKA
    $stmt = $conn->prepare("SELECT pracownik_id, haslo, rola FROM pracownik WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($pracownik_id, $haslo_hash, $rola);

    if ($stmt->fetch() && hash('sha256', $haslo) === $haslo_hash) {
        $_SESSION['pracownik_id'] = $pracownik_id;
        $_SESSION['rola'] = $rola;
        header("Location: dashboard.php");
        exit();
    }
    $stmt->close();  // zamykamy zapytanie

    // ✅ 2. Jeśli nie pracownik – sprawdzamy KLIENTA
    $stmt = $conn->prepare("SELECT klient_id, haslo FROM klient WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($klient_id, $haslo_hash);

    if ($stmt->fetch() && hash('sha256', $haslo) === $haslo_hash) {
        $_SESSION['pracownik_id'] = $klient_id;   // 👈 bo dashboard wymaga tej zmiennej
        $_SESSION['rola'] = 'klient';
        header("Location: dashboard.php");
        exit();
    }

    $error = "Błędne dane logowania.";
}

include 'header.php';
?>

<h2>Logowanie</h2>
<?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
<form method="POST">
    <input class="form-control" type="email" name="email" placeholder="Email" required><br>
    <input class="form-control" type="password" name="haslo" placeholder="Hasło" required><br>
    <button class="btn btn-primary" type="submit">Zaloguj</button>
</form>

<?php include 'footer.php'; ?>

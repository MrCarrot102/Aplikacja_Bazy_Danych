<?php
session_start();
require 'db.php';

if (!isset($_SESSION['pracownik_id']) || $_SESSION['rola'] !== 'klient') {
    header("Location: login.php");
    exit();
}

$samochod_id = $_GET['id'] ?? null;
$klient_id = $_SESSION['pracownik_id'];
$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data_od = $_POST['data_od'];
    $data_do = $_POST['data_do'];

    if ($data_od && $data_do && $samochod_id) {
        // Sprawdzenie kolizji rezerwacji (czy auto już zajęte)
        $stmt_check = $conn->prepare("
            SELECT 1 FROM rezerwacja 
            WHERE samochod_id = ?
              AND NOT (
                  data_do < ? OR data_od > ?
              )
        ");
        $stmt_check->bind_param("iss", $samochod_id, $data_od, $data_do);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $error = "Wybrany samochód jest już zarezerwowany w tym terminie.";
        } else {
            // Dodanie rezerwacji
            $stmt = $conn->prepare("
                INSERT INTO rezerwacja (klient_id, samochod_id, data_od, data_do)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->bind_param("iiss", $klient_id, $samochod_id, $data_od, $data_do);
            if ($stmt->execute()) {
                $success = "Rezerwacja została złożona.";
            } else {
                $error = "Błąd zapisu rezerwacji.";
            }
            $stmt->close();
        }

        $stmt_check->close();
    } else {
        $error = "Wszystkie pola są wymagane.";
    }
}

include 'header.php';
?>

<div class="container mt-4">
    <h2>Rezerwacja samochodu</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="data_od" class="form-label">Data od:</label>
            <input type="date" name="data_od" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="data_do" class="form-label">Data do:</label>
            <input type="date" name="data_do" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Zarezerwuj</button>
    </form>
</div>

<?php include 'footer.php'; ?>

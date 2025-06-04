<?php
session_start();
if (!isset($_SESSION['pracownik_id'])) {
    header("Location: login.php");
    exit();
}
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $marka = $_POST['marka'];
    $model = $_POST['model'];
    $rok = $_POST['rok'];
    $rejestracja = $_POST['numer_rejestracyjny'];
    $typ = $_POST['typ'];
    $miejsca = $_POST['liczba_miejsc'];
    $stmt = $conn->prepare("INSERT INTO samochod (marka, model, rok, numer_rejestracyjny, typ, liczba_miejsc) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssissi", $marka, $model, $rok, $rejestracja, $typ, $miejsca);
    $stmt->execute();
    echo "<div class='alert alert-success'>Dodano samochód!</div>";
}
include 'header.php';
?>
<h2>Dodaj samochód</h2>
<form method="POST">
    <input class="form-control" name="marka" placeholder="Marka" required><br>
    <input class="form-control" name="model" placeholder="Model" required><br>
    <input class="form-control" name="rok" type="number" placeholder="Rok" required><br>
    <input class="form-control" name="numer_rejestracyjny" placeholder="Numer rejestracyjny" required><br>
    <input class="form-control" name="typ" placeholder="Typ (np. sedan)" required><br>
    <input class="form-control" name="liczba_miejsc" type="number" placeholder="Liczba miejsc" required><br>
    <button class="btn btn-success" type="submit">Dodaj samochód</button>
</form>
<?php include 'footer.php'; ?>
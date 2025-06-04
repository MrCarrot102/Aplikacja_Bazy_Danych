<?php
session_start();
if (!isset($_SESSION['pracownik_id'])) {
    header("Location: login.php");
    exit();
}
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $klient_id = $_POST['klient_id'];
    $samochod_id = $_POST['samochod_id'];
    $data_od = $_POST['data_od'];
    $data_do = $_POST['data_do'];
    $stmt = $conn->prepare("INSERT INTO rezerwacja (klient_id, samochod_id, data_od, data_do, status) VALUES (?, ?, ?, ?, 'ZAREZERWOWANA')");
    $stmt->bind_param("iiss", $klient_id, $samochod_id, $data_od, $data_do);
    $stmt->execute();
    $conn->query("UPDATE samochod SET dostepnosc = 0 WHERE samochod_id = $samochod_id");
    echo "Rezerwacja dodana!";
}
include 'header.php';
?>
<h2>Dodaj rezerwację</h2>
<form method="POST">
    <input type="number" name="klient_id" placeholder="ID klienta" required><br>
    <input type="number" name="samochod_id" placeholder="ID samochodu" required><br>
    <input type="date" name="data_od" required><br>
    <input type="date" name="data_do" required><br>
    <button type="submit">Zarezerwuj</button>
</form>
<?php include 'footer.php'; ?>
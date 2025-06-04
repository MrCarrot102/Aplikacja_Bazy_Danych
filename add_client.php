<?php
session_start();
if (!isset($_SESSION['pracownik_id'])) {
    header("Location: login.php");
    exit();
}
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];
    $email = $_POST['email'];
    $telefon = $_POST['telefon'];
    $adres = $_POST['adres'];
    $stmt = $conn->prepare("INSERT INTO klient (imie, nazwisko, email, telefon, adres) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $imie, $nazwisko, $email, $telefon, $adres);
    $stmt->execute();
    echo "Klient dodany!";
}
include 'header.php';
?>
<h2>Dodaj klienta</h2>
<form method="POST">
    <input type="text" name="imie" placeholder="Imię" required><br>
    <input type="text" name="nazwisko" placeholder="Nazwisko" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="text" name="telefon" placeholder="Telefon"><br>
    <input type="text" name="adres" placeholder="Adres"><br>
    <button type="submit">Dodaj klienta</button>
</form>
<?php include 'footer.php'; ?>
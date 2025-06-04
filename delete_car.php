<?php
session_start();
require 'db.php';

if (!isset($_SESSION['pracownik_id'])) {
    header("Location: login.php");
    exit();
}

$pracownik_id = $_SESSION['pracownik_id'];
$rola = null;

$result = $conn->query("SELECT rola FROM pracownik WHERE pracownik_id = $pracownik_id");
if ($row = $result->fetch_assoc()) {
    $rola = $row['rola'];
}

if ($rola !== 'admin') {
    echo "Brak uprawnień do tej operacji.";
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn->query("DELETE FROM samochod WHERE samochod_id = $id");
    header("Location: list_cars.php");
    exit();
}
?>

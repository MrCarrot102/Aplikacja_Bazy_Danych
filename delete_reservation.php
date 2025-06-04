<?php
session_start();
require 'db.php';

if (!isset($_SESSION['pracownik_id']) || $_SESSION['rola'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Przywracamy dostępność samochodu
    $q = $conn->query("SELECT samochod_id FROM rezerwacja WHERE rezerwacja_id = $id");
    if ($row = $q->fetch_assoc()) {
        $conn->query("UPDATE samochod SET dostepny = 1 WHERE samochod_id = " . $row['samochod_id']);
    }

    $conn->query("DELETE FROM rezerwacja WHERE rezerwacja_id = $id");
}

header("Location: list_reservations.php");
exit();

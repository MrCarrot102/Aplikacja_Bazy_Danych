<?php
session_start();
require 'db.php';

if (!isset($_SESSION['pracownik_id']) || $_SESSION['rola'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Najpierw usuń rezerwacje powiązane z tym klientem
    $conn->query("DELETE FROM rezerwacja WHERE klient_id = $id");

    // Następnie usuń klienta
    if ($conn->query("DELETE FROM klient WHERE klient_id = $id") === TRUE) {
        header("Location: list_clients.php");
        exit();
    } else {
        echo "Błąd przy usuwaniu klienta.";
    }
} else {
    echo "Niepoprawne żądanie.";
}
?>

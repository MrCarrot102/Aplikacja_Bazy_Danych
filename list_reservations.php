<?php
session_start();
require 'db.php';

if (!isset($_SESSION['pracownik_id'])) {
    header("Location: login.php");
    exit();
}

include 'header.php';

$rola = $_SESSION['rola'] ?? null;

echo "<div class='container mt-4'>";
echo "<div class='d-flex justify-content-between align-items-center mb-3'>";
echo "<h2 class='mb-0'>Lista rezerwacji</h2>";
echo "</div>";

$sql = "SELECT r.rezerwacja_id, k.imie, k.nazwisko, s.samochod_id, s.marka, s.model, r.data_od, r.data_do, r.status
        FROM rezerwacja r
        JOIN klient k ON r.klient_id = k.klient_id
        JOIN samochod s ON r.samochod_id = s.samochod_id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table class='table table-striped table-bordered'>
            <thead class='table-dark'>
                <tr>
                    <th>ID</th>
                    <th>Klient</th>
                    <th>Samochód</th>
                    <th>Od</th>
                    <th>Do</th>
                    <th>Status</th>";
    if ($rola === 'admin') {
        echo "<th>Akcje</th>";
    }
    echo "</tr></thead><tbody>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['rezerwacja_id']}</td>
                <td>{$row['imie']} {$row['nazwisko']}</td>
                <td>{$row['marka']} {$row['model']}</td>
                <td>{$row['data_od']}</td>
                <td>{$row['data_do']}</td>
                <td>{$row['status']}</td>";

        if ($rola === 'admin') {
            echo "<td>
                    <a href='delete_reservation.php?id={$row['rezerwacja_id']}' 
                       class='btn btn-danger btn-sm'
                       onclick=\"return confirm('Na pewno chcesz usunąć tę rezerwację?');\">
                       Usuń
                    </a>
                  </td>";
        }

        echo "</tr>";
    }

    echo "</tbody></table>";
} else {
    echo "<div class='alert alert-warning'>Brak rezerwacji w systemie.</div>";
}

echo "</div>";

include 'footer.php';
?>

<?php
session_start();
require 'db.php';

if (!isset($_SESSION['pracownik_id']) || $_SESSION['rola'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'header.php';

echo "<h2>Lista klientów</h2>";

$result = $conn->query("SELECT klient_id, imie, nazwisko, email FROM klient");

if ($result->num_rows > 0) {
    echo "<table class='table table-bordered'>
            <thead><tr>
                <th>ID</th><th>Imię</th><th>Nazwisko</th><th>Email</th><th>Akcje</th>
            </tr></thead><tbody>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['klient_id']}</td>
                <td>{$row['imie']}</td>
                <td>{$row['nazwisko']}</td>
                <td>{$row['email']}</td>
                <td>
                    <a href='delete_client.php?id={$row['klient_id']}'
                       class='btn btn-danger btn-sm'
                       onclick=\"return confirm('Czy na pewno chcesz usunąć tego klienta?');\">Usuń</a>
                </td>
              </tr>";
    }

    echo "</tbody></table>";
} else {
    echo "<div class='alert alert-info'>Brak klientów w bazie.</div>";
}

include 'footer.php';
?>

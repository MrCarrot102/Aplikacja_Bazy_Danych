<?php
session_start();
require 'db.php';

if (!isset($_SESSION['pracownik_id'])) {
    header("Location: login.php");
    exit();
}

include 'header.php';

$pracownik_id = $_SESSION['pracownik_id'];
$rola = $_SESSION['rola'] ?? null;

// PANEL KLIENTA
if ($rola === 'klient') {
    echo "<h2>Witamy w panelu klienta</h2>";
    echo "<p>Możesz przeglądać dostępne samochody i je rezerwować.</p>";
    include 'list_cars.php';
}

// PANEL ADMINA
elseif ($rola === 'admin') {
    echo "<h2>Panel administratora</h2>";
    echo "<ul>
        <li><a href='list_cars.php'>Lista samochodów</a></li>
        <li><a href='add_car.php'>Dodaj samochód</a></li>
        <li><a href='list_clients.php'>Lista klientów</a></li>
        <li><a href='add_client.php'>Dodaj klienta</a></li>
        <li><a href='list_reservations.php'>Lista rezerwacji</a></li>
        <li><a href='add_reservation.php'>Dodaj rezerwację</a></li>
    </ul>";
}


// PANEL PRACOWNIKA
elseif ($rola === 'pracownik') {
    echo "<h2>Panel pracownika</h2>";
    echo "<ul>
            <li><a href='list_reservations.php'>Lista rezerwacji</a></li>
            <li><a href='list_clients.php'>Lista klientów</a></li>
          </ul>";
}

include 'footer.php';
?>

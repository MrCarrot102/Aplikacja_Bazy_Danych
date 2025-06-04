<?php include 'header.php'; ?>
<h2>Witaj w wypożyczalni samochodów</h2>
<ul>
    <li><a href="list_cars.php">Lista samochodów</a></li>
    <?php if (isset($_SESSION['pracownik_id'])): ?>
    <li><a href="add_client.php">Dodaj klienta</a></li>
    <li><a href="add_reservation.php">Dodaj rezerwację</a></li>
    <?php endif; ?>
</ul>
<?php include 'footer.php'; ?>
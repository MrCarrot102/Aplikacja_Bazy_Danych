<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'db.php';

if (!isset($_SESSION['pracownik_id']) && !isset($_SESSION['rola'])) {
    header("Location: login.php");
    exit();
}

$rola = $_SESSION['rola'] ?? null;

$sql = "SELECT * FROM samochod";
$result = $conn->query($sql);
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Lista samochodów</h2>
        <?php if ($rola === 'admin' || $rola === 'pracownik'): ?>
            <a href="add_car.php" class="btn btn-success">Dodaj samochód</a>
        <?php endif; ?>
    </div>

    <?php if ($result && $result->num_rows > 0): ?>
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Marka</th>
                    <th>Model</th>
                    <th>Rok</th>
                    <th>Cena za dzień</th>
                    <?php if ($rola === 'klient'): ?>
                        <th>Akcja</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['samochod_id'] ?></td>
                        <td><?= $row['marka'] ?></td>
                        <td><?= $row['model'] ?></td>
                        <td><?= $row['rok'] ?></td>
                        <td><?= isset($row['cena_za_dzien']) ? $row['cena_za_dzien'] . " zł" : "-" ?></td>
                        <?php if ($rola === 'klient'): ?>
                            <td>
                                <a href="reserve_car.php?id=<?= $row['samochod_id'] ?>" class="btn btn-primary btn-sm">
                                    Zarezerwuj
                                </a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">Brak samochodów w bazie.</div>
    <?php endif; ?>
</div>

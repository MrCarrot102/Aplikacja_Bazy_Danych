<?php
session_start();
require 'db.php';

if (!isset($_SESSION['pracownik_id'])) {
    header("Location: login.php");
    exit();
}

$rola = $_SESSION['rola'] ?? null;
include 'header.php';
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Lista rezerwacji</h2>
        <?php if ($rola === 'admin'): ?>
            <a href="list_reservations.php?action=add" class="btn btn-success">Dodaj rezerwację</a>
        <?php endif; ?>
    </div>

    <?php
    // Obsługa dodawania rezerwacji
    if (isset($_GET['action']) && $_GET['action'] === 'add' && $rola === 'admin') {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $klient_id = $_POST['klient_id'];
            $samochod_id = $_POST['samochod_id'];
            $data_od = $_POST['data_od'];
            $data_do = $_POST['data_do'];

            // Sprawdzenie kolizji terminów bez statusu
            $check = $conn->prepare("
                SELECT 1 FROM rezerwacja 
                WHERE samochod_id = ?
                  AND NOT (
                      data_do < ? OR data_od > ?
                  )
            ");
            $check->bind_param("iss", $samochod_id, $data_od, $data_do);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                echo "<div class='alert alert-danger'>Wybrane auto jest już zarezerwowane w tym terminie. Wybierz inny termin lub pojazd.</div>";
            } else {
                $stmt = $conn->prepare("INSERT INTO rezerwacja (klient_id, samochod_id, data_od, data_do) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("iiss", $klient_id, $samochod_id, $data_od, $data_do);

                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Rezerwacja została dodana.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Błąd: " . $conn->error . "</div>";
                }

                $stmt->close();
            }

            $check->close();
        }

        // Formularz dodawania rezerwacji
        ?>
        <form method="post" class="mb-4">
            <div class="mb-3">
                <label for="klient_id" class="form-label">Wybierz klienta:</label>
                <select name="klient_id" id="klient_id" class="form-select" required>
                    <option value="">-- Wybierz klienta --</option>
                    <?php
                    $klienci = $conn->query("SELECT klient_id, imie, nazwisko FROM klient");
                    while ($k = $klienci->fetch_assoc()) {
                        echo "<option value='{$k['klient_id']}'>{$k['imie']} {$k['nazwisko']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="samochod_id" class="form-label">Wybierz samochód:</label>
                <select name="samochod_id" id="samochod_id" class="form-select" required>
                    <option value="">-- Wybierz samochód --</option>
                    <?php
                    $auta = $conn->query("SELECT samochod_id, marka, model FROM samochod");
                    while ($s = $auta->fetch_assoc()) {
                        echo "<option value='{$s['samochod_id']}'>{$s['marka']} {$s['model']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="data_od" class="form-label">Data od:</label>
                <input type="date" name="data_od" id="data_od" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="data_do" class="form-label">Data do:</label>
                <input type="date" name="data_do" id="data_do" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Zarezerwuj</button>
            <a href="list_reservations.php" class="btn btn-secondary">Anuluj</a>
        </form>
        <?php
    }

    // Lista rezerwacji (zawsze widoczna)
    $sql = "SELECT r.rezerwacja_id, k.imie, k.nazwisko, s.marka, s.model, r.data_od, r.data_do
            FROM rezerwacja r
            JOIN klient k ON r.klient_id = k.klient_id
            JOIN samochod s ON r.samochod_id = s.samochod_id";

    $result = $conn->query($sql);

    if ($result->num_rows > 0): ?>
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Klient</th>
                    <th>Samochód</th>
                    <th>Od</th>
                    <th>Do</th>
                    <?php if ($rola === 'admin'): ?>
                        <th>Akcje</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['rezerwacja_id'] ?></td>
                        <td><?= $row['imie'] . ' ' . $row['nazwisko'] ?></td>
                        <td><?= $row['marka'] . ' ' . $row['model'] ?></td>
                        <td><?= $row['data_od'] ?></td>
                        <td><?= $row['data_do'] ?></td>
                        <?php if ($rola === 'admin'): ?>
                            <td>
                                <a href="delete_reservation.php?id=<?= $row['rezerwacja_id'] ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Na pewno chcesz usunąć tę rezerwację?');">
                                   Usuń
                                </a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">Brak rezerwacji w systemie.</div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

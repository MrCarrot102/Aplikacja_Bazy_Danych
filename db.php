<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'wypozyczalnia';
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Błąd połączenia z bazą: " . $conn->connect_error);
}
?>
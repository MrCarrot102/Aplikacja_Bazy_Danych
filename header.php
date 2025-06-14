<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} ?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Wypożyczalnia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">Panel</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <?php if (isset($_SESSION['pracownik_id'])): ?>
          <li class="nav-item"><a class="nav-link" href="logout.php">Wyloguj</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="login.php">Zaloguj</a></li>
<li class="nav-item"><a class="nav-link" href="register.php">Zarejestruj</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container">
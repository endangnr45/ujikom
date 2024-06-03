<?php 
    session_start();
    require 'functions.php';

    if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
        header("Location: login.php");
        exit();
    }

    // Tentukan halaman yang sedang aktif
    $current_page = basename($_SERVER['PHP_SELF']);
    $books = query("SELECT * FROM buku");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="dashboard_admin.php">
            <img src="img/logo.jfif" width="30" height="30" class="d-inline-block align-top" alt="">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item <?php echo $current_page == 'dashboard_admin.php' ? 'active' : ''; ?>">
                    <a class="nav-link" href="dashboard_admin.php">Dashboard<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item <?php echo $current_page == 'books.php' ? 'active' : ''; ?>">
                    <a class="nav-link" href="books.php">Buku</a>
                </li>
            </ul>
            <span class="navbar-text">
                <a class="nav-link" href="logout.php">Logout</a>
            </span>
        </div>
    </nav>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div style="margin: 20px;">
        <h1>Daftar Buku</h1>
        <div class="container mt-3">
            <div class="row">
                <?php foreach ($books as $book) : ?>
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?= $book['judul_buku']; ?></h5>
                                <p class="card-text">Karya : <?= $book['nama_pengarang']; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="container text-center">
            <br>
            <button name="tambah_buku" class="btn btn-primary"><a href="create.php" style="color: white;">Tambah Buku</a></button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>

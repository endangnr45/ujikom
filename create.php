<?php 
session_start();
require 'functions.php';

if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

// cek apakah tombol submit pernah dipencet
if (isset($_POST["submit"])) {
    // var_dump($_POST);
    $result = tambah($_POST);
    if ($result > 0) {
        echo "
        <script>
        alert('Data berhasil ditambahkan');
        document.location.href = 'books.php';
        </script>
        ";
    } else if ($result == -1) {
        $error = "Buku sudah ada!";
    } else {
        echo "
        <script>
        alert('Buku gagal ditambahkan');
        document.location.href = 'books.php';
        </script>";
    }
}

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
    <br>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Data Buku</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container">
    <h2>Tambah Buku</h2>
    <?php if(isset($error)) :?>
    <p style="color: red;"><?= $error; ?></p>
    <?php  endif; ?>
    <form action="" method="post">
        <div class="form-group">
            <label for="judul_buku">Judul :</label>
            <input type="text" name="judul_buku" id="judul_buku" class="form-control col-md-6" required>
        </div>
        <div class="form-group">
            <label for="tgl_terbit">Tanggal Terbit :</label>
            <input type="date" name="tgl_terbit" id="tgl_terbit" class="form-control col-md-6" required>
        </div>
        <div class="form-group">
            <label for="nama_pengarang">Nama Pengarang :</label>
            <input type="text" name="nama_pengarang" id="nama_pengarang" class="form-control col-md-6" required>
        </div>
        <div class="form-group">
            <label for="nama_penerbit">Nama Penerbit:</label>
            <input type="text" name="nama_penerbit" id="nama_penerbit" class="form-control col-md-6" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Create Book</button>

    </form>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
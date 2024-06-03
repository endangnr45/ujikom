<?php 
session_start();

// Cek apakah ada sesi yang aktif
if (isset($_SESSION["username"])) {
    // Jika ada, periksa peran pengguna
    if ($_SESSION["role"] === "admin") {
        // Jika pengguna adalah admin, alihkan ke halaman login
        header("Location: dashboard_admin.php");
        exit();
    } else {
        // Jika pengguna adalah peminjam, izinkan akses ke halaman pinjam
    }
} else {
    // Jika tidak ada sesi yang aktif, izinkan akses ke halaman pinjam
}

// Kode untuk menambahkan buku ke dalam keranjang
if(isset($_POST['tambah']) && isset($_POST['id_buku'])) {
    // Jika pengguna belum login, arahkan ke halaman login
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    } else { // Jika pengguna sudah login, tambahkan buku ke dalam keranjang
        $id_buku = $_POST['id_buku'];
        if(!isset($_SESSION['keranjang'])) {
            $_SESSION['keranjang'] = [];
        }
        if(!in_array($id_buku, $_SESSION['keranjang'])) {
            $_SESSION['keranjang'][] = $id_buku;
        }
    }
}

require 'functions.php';
$books = query("SELECT * FROM buku");

// Jika tombol "Next" ditekan, lakukan pengecekan login
if(isset($_POST['next'])) {
    // Jika pengguna belum login, arahkan ke halaman login
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    } else { // Jika pengguna sudah login, arahkan ke halaman keranjang
        header("Location: keranjang.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'navbar.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinjam Buku</title>
    <!-- Tambahkan Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-tambah.clicked {
            background-color: lightblue; 
        }
    </style>
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
                                <p class="card-text">Karya <?= $book['nama_pengarang']; ?></p>
                                <form action="" method="post">
                                    <input type="hidden" name="id_buku" value="<?= $book['id_buku']; ?>">
                                    <button type="submit" name="tambah" class="btn btn-primary btn-tambah">Tambah</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="container text-center">
            <br>
            <form action="" method="post">
                <button type="submit" name="next" class="btn btn-primary">Next</button>
            </form>
        </div>
    </div>
    <!-- Tambahkan Bootstrap JS dan dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tambahButtons = document.querySelectorAll('.btn-tambah');

            tambahButtons.forEach(function(button) {
                button.addEventListener('click', function(event) {
                    button.classList.add('clicked');
                });
            });
        });
    </script>
</body>
</html>



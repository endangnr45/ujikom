<?php 
    // Mulai session jika belum dimulai
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Tentukan halaman yang sedang aktif
    $current_page = basename($_SERVER['PHP_SELF']);

    // Tentukan judul halaman berdasarkan halaman yang sedang diakses
    $page_titles = [
        'index.php' => 'Home',
        'pinjam.php' => 'Peminjaman Buku',
        'keranjang.php' => 'Keranjang',
        'login.php' => 'Login',
        'register.php' => 'Registrasi'
    ];

    $page_title = isset($page_titles[$current_page]) ? $page_titles[$current_page] : 'Selamat Datang';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <!-- Tambahkan Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.php">
        <img src="img/logo.jfif" width="30" height="30" class="d-inline-block align-top" alt="">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item <?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
                <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item <?php echo $current_page == 'pinjam.php' ? 'active' : ''; ?>">
                <a class="nav-link" href="pinjam.php">Peminjaman Buku</a>
            </li>
            <li class="nav-item <?php echo $current_page == 'keranjang.php' ? 'active' : ''; ?>">
                <a class="nav-link" href="keranjang.php">Keranjang</a>
            </li>
        </ul>
        <span class="navbar-text my-2 my-sm-0">
        <?php
        if(isset($_SESSION["username"])) {
            echo '<a class="nav-link" href="#">'.$_SESSION["username"].'</a>'; 
            echo '<a class="nav-link" href="logout.php">Logout</a>';
        } else {
            echo '<a class="nav-link" href="login.php">Login</a>'; 
            echo '<a class="nav-link" href="register.php">Register</a>';
        }
        ?>
        </span>
    </div>
</nav>

    <!-- Konten halaman -->
    
    <!-- Tambahkan Bootstrap JS dan dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>

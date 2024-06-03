<?php 
session_start();
if (isset($_SESSION["username"])) {
    // Pengguna sudah login
    if ($_SESSION["role"] === "admin") {
        // Jika pengguna adalah admin, alihkan ke dashboard_admin.php
        header("Location: dashboard_admin.php");
        exit();
    } else {
        // Jika pengguna adalah peminjam
    }
} else {
    // Pengguna belum login, biarkan akses halaman index
}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include 'navbar.php'; ?>
<br>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang</title>
    <!-- Tambahkan Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
   
</head>
<body>
<div class="container text-center">
    <h1>Selamat Datang di Perpustakaan Kota</h1>
    <p>This is the home page.</p>
    <img src="img/logo.jfif" class="img-fluid" alt="">
    <div class="mt-4">
        <a href="pinjam.php" class="btn btn-primary">Pinjam</a>
    </div>
</div>
    
<!-- Tambahkan Bootstrap JS dan dependencies -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>

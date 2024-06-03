<?php 
session_start();
require 'functions.php';

if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "peminjam") {
    header("Location: login.php");
    exit();
}

    // Tentukan halaman yang sedang aktif
    $current_page = basename($_SERVER['PHP_SELF']);

// Pastikan pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil ID peminjam berdasarkan username yang login
$username = $_SESSION['username'];
$id_peminjam = getPeminjamIdByUsername($username);

// Ambil data peminjaman dari database
$query = "SELECT p.kode_pinjam, p.tgl_pesan, p.tgl_ambil, p.tgl_wajibkembali, p.tgl_kembali, p.status_pinjam,
                 GROUP_CONCAT(b.judul_buku SEPARATOR ', ') AS judul_buku
          FROM peminjaman p
          LEFT JOIN detail_peminjaman dp ON p.kode_pinjam = dp.kode_pinjam
          LEFT JOIN buku b ON dp.id_buku = b.id_buku
          WHERE p.id_peminjam = ?
          GROUP BY p.kode_pinjam";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id_peminjam);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$peminjaman = [];
while ($row = mysqli_fetch_assoc($result)) {
    $peminjaman[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.php">
        <img src="img/logo.jfif" width="30" height="30" class="d-inline-block align-top" alt="">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item <?php echo $current_page == 'dashboard_peminjam.php' ? 'active' : ''; ?>">
                <a class="nav-link" href="dashboard_peminjam.php"><h4>Selamat Datang, <?= $_SESSION["username"]; ?></h4><span class="sr-only">(current)</span></a>
            </li>
        </ul>
        <span class="navbar-text">
        <a class="nav-link" href="logout.php">Logout</a>
        </span>
    </div>
</nav>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Peminjaman Buku</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Dashboard Peminjaman Buku</h1>
        <?php if (empty($peminjaman)): ?>
            <p>Belum ada peminjaman.</p>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Buku</th>
                        <th>Tanggal Pesan</th>
                        <th>Tanggal Ambil</th>
                        <th>Tanggal Wajib Kembali</th>
                        <th>Tanggal Kembali</th>
                        <th>Status Pinjam</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($peminjaman as $pinjam): ?>
                        <tr>
                            <td><?= $i; ?></td>
                            <td><?= $pinjam['judul_buku']; ?></td>
                            <td><?= $pinjam['tgl_pesan']; ?></td>
                            <td><?= $pinjam['tgl_ambil']; ?></td>
                            <td><?= $pinjam['tgl_wajibkembali']; ?></td>
                            <td><?= $pinjam['tgl_kembali'] ? $pinjam['tgl_kembali'] : 'Belum Kembali'; ?></td>
                            <td><?= $pinjam['status_pinjam']; ?></td>
                        </tr>
                        <?php $i++; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>

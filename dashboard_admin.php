<?php 
    session_start();
    require 'functions.php';

    if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
        header("Location: login.php");
        exit();
    }

    // Tentukan halaman yang sedang aktif
    $current_page = basename($_SERVER['PHP_SELF']);

    // Mengambil semua peminjaman
    $query = "SELECT p.kode_pinjam, p.id_admin, p.tgl_pesan, p.tgl_ambil, p.tgl_wajibkembali, p.tgl_kembali, p.status_pinjam,
                 pm.nama_peminjam, GROUP_CONCAT(b.judul_buku SEPARATOR ', ') AS judul_buku
          FROM peminjaman p
          LEFT JOIN detail_peminjaman dp ON p.kode_pinjam = dp.kode_pinjam
          LEFT JOIN buku b ON dp.id_buku = b.id_buku
          LEFT JOIN peminjam pm ON p.id_peminjam = pm.id_peminjam
          GROUP BY p.kode_pinjam";
    $result = mysqli_query($conn, $query);
    $peminjaman = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $peminjaman[] = $row;
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

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-3">
        <h1>Dashboard Admin</h1>
        <?php if (empty($peminjaman)): ?>
            <p>Tidak ada peminjaman yang tersedia.</p>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Pinjam</th>
                        <th>Peminjam</th>
                        <th>Judul Buku</th>
                        <th>Tgl Pesan</th>
                        <th>Tgl Ambil</th>
                        <th>Tgl Wajib Kembali</th>
                        <th>Tgl Kembali</th>
                        <th>Status Pinjam</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($peminjaman as $pinjam): ?>
                        <tr>
                            <td><?= $i; ?></td>
                            <td><?= $pinjam['kode_pinjam']; ?></td>
                            <td><?= $pinjam['nama_peminjam']; ?></td>
                            <td><?= $pinjam['judul_buku']; ?></td>
                            <td><?= $pinjam['tgl_pesan']; ?></td>
                            <td><?= $pinjam['tgl_ambil']; ?></td>
                            <td><?= $pinjam['tgl_wajibkembali']; ?></td>
                            <td><?= $pinjam['tgl_kembali']; ?></td>
                            <td><?= $pinjam['status_pinjam']; ?></td>
                            <td>
                                <a href="konfirmasi_pinjam.php?kode_pinjam=<?= $pinjam['kode_pinjam']; ?>" class="btn btn-primary btn-sm">Konfirmasi</a>
                            </td>
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

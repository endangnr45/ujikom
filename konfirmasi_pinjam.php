<?php 
session_start();
require 'functions.php';

if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

// Mendapatkan kode pinjam dari URL
if (isset($_GET['kode_pinjam'])) {
    $kode_pinjam = $_GET['kode_pinjam'];
} else {
    header("Location: dashboard_admin.php");
    exit();
}

// Mengambil data peminjaman berdasarkan kode pinjam
$query = "SELECT p.kode_pinjam,p.id_admin, p.tgl_pesan, p.tgl_ambil, p.tgl_wajibkembali, p.tgl_kembali, p.status_pinjam,
                 pm.nama_peminjam, GROUP_CONCAT(b.judul_buku SEPARATOR ', ') AS judul_buku
          FROM peminjaman p
          LEFT JOIN detail_peminjaman dp ON p.kode_pinjam = dp.kode_pinjam
          LEFT JOIN buku b ON dp.id_buku = b.id_buku
          LEFT JOIN peminjam pm ON p.id_peminjam = pm.id_peminjam
          WHERE p.kode_pinjam = ?
          GROUP BY p.kode_pinjam";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $kode_pinjam);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pinjam = mysqli_fetch_assoc($result);

// Mengonfirmasi peminjaman
if (isset($_POST['update_status'])) {
    $status_pinjam = $_POST['status_pinjam'];
    $tgl_kembali = NULL;

    if ($status_pinjam == 'SELESAI') {
        $tgl_kembali = date('Y-m-d');
    }

    if ($_SESSION["role"] == "admin") {
        $id_admin = getAdminIdByUsername($_SESSION['username']);
    } else {
        $id_peminjam = getPeminjamIdByUsername($_SESSION['username']);
    }

    if ($_SESSION["role"] == "admin") {
        $query = "UPDATE peminjaman SET status_pinjam = ?, tgl_kembali = ?, id_admin = ? WHERE kode_pinjam = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssis", $status_pinjam, $tgl_kembali, $id_admin, $kode_pinjam);
        mysqli_stmt_execute($stmt);
    } else {
        // Handle jika pengguna bukan admin
        // Misalnya, mungkin Anda ingin memberikan tanggapan bahwa hanya admin yang dapat mengonfirmasi peminjaman
    }

    header("Location: dashboard_admin.php");
    exit();
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
    <title>Konfirmasi Peminjaman</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Konfirmasi Peminjaman</h1>
        <form action="" method="post">
            <div class="form-group">
                <label for="nama_peminjam">Nama Peminjam</label>
                <input type="text" class="form-control" id="nama_peminjam" value="<?= $pinjam['nama_peminjam']; ?>" disabled>
            </div>
            <div class="form-group">
                <label for="judul_buku">Buku</label>
                <input type="text" class="form-control" id="judul_buku" value="<?= $pinjam['judul_buku']; ?>" disabled>
            </div>
            <div class="form-group">
                <label for="status_pinjam">Status Pinjam</label>
                <select class="form-control" id="status_pinjam" name="status_pinjam">
                    <option value="DIPROSES" <?= $pinjam['status_pinjam'] == 'DIPROSES' ? 'selected' : ''; ?>>DIPROSES</option>
                    <option value="DITOLAK" <?= $pinjam['status_pinjam'] == 'DITOLAK' ? 'selected' : ''; ?>>DITOLAK</option>
                    <option value="DISETUJUI" <?= $pinjam['status_pinjam'] == 'DISETUJUI' ? 'selected' : ''; ?>>DISETUJUI</option>
                    <option value="SELESAI" <?= $pinjam['status_pinjam'] == 'SELESAI' ? 'selected' : ''; ?>>SELESAI</option>
                </select>
            </div>
            <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
</body>
</html>

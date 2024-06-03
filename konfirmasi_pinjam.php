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
$query = "SELECT p.kode_pinjam, p.id_admin, p.tgl_pesan, p.tgl_ambil, p.tgl_wajibkembali, p.tgl_kembali, p.status_pinjam,
                 pm.nama_peminjam, b.id_buku, b.judul_buku
          FROM peminjaman p
          LEFT JOIN detail_peminjaman dp ON p.kode_pinjam = dp.kode_pinjam
          LEFT JOIN buku b ON dp.id_buku = b.id_buku
          LEFT JOIN peminjam pm ON p.id_peminjam = pm.id_peminjam
          WHERE p.kode_pinjam = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $kode_pinjam);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$pinjam = [];
$pinjam['judul_buku'] = []; // Initialize as an array to hold book titles
while ($row = mysqli_fetch_assoc($result)) {
    if (empty($pinjam['kode_pinjam'])) {
        $pinjam['kode_pinjam'] = $row['kode_pinjam'];
        $pinjam['id_admin'] = $row['id_admin'];
        $pinjam['tgl_pesan'] = $row['tgl_pesan'];
        $pinjam['tgl_ambil'] = $row['tgl_ambil'];
        $pinjam['tgl_wajibkembali'] = $row['tgl_wajibkembali'];
        $pinjam['tgl_kembali'] = $row['tgl_kembali'];
        $pinjam['status_pinjam'] = $row['status_pinjam'];
        $pinjam['nama_peminjam'] = $row['nama_peminjam'];
    }
    $pinjam['judul_buku'][] = ['id_buku' => $row['id_buku'], 'judul_buku' => $row['judul_buku']];
}

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

// Menghapus buku dari peminjaman
if (isset($_GET['hapus_buku'])) {
    $id_buku = $_GET['hapus_buku'];

    $query = "DELETE FROM detail_peminjaman WHERE kode_pinjam = ? AND id_buku = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $kode_pinjam, $id_buku);
    mysqli_stmt_execute($stmt);

    header("Location: konfirmasi_pinjam.php?kode_pinjam=" . $kode_pinjam);
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

    <style>
        .form-container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        .form-group input {
            width: 100%;
        }
    </style>
    
</head>
<body>
    <div class="container mt-5">
        <h1>Konfirmasi Peminjaman</h1>
        <form action="" method="post">
            <div class="form-group">
                <label for="kode_pinjam">Kode Pinjam</label>
                <input type="text" class="form-control" id="kode_pinjam" value="<?= $pinjam['kode_pinjam']; ?>" disabled>
            </div>
            <div class="form-group">
                <label for="nama_peminjam">Nama Peminjam</label>
                <input type="text" class="form-control" id="nama_peminjam" value="<?= $pinjam['nama_peminjam']; ?>" disabled>
            </div>
            <div class="form-group">
                <label for="judul_buku">Buku</label>
                <?php foreach ($pinjam['judul_buku'] as $buku) : ?>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" value="<?= $buku['judul_buku']; ?>" disabled>
                        <div class="input-group-append">
                            <a href="konfirmasi_pinjam.php?kode_pinjam=<?= $kode_pinjam; ?>&hapus_buku=<?= $buku['id_buku']; ?>" class="btn btn-danger">X</a>
                        </div>
                    </div>
                <?php endforeach; ?>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>

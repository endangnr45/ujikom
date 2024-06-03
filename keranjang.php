<?php 
session_start();
require 'functions.php';

if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "peminjam") {
    header("Location: login.php");
    exit();
}

// Pastikan pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Menghapus buku dari keranjang
if(isset($_POST['hapus']) && isset($_POST['id_buku'])) {
    $id_buku = $_POST['id_buku'];
    if(isset($_SESSION['keranjang'])) {
        $_SESSION['keranjang'] = array_diff($_SESSION['keranjang'], [$id_buku]);
    }
}

// Mengambil data buku dari database
$keranjang = isset($_SESSION['keranjang']) ? $_SESSION['keranjang'] : [];
$books_in_keranjang = [];
if ($keranjang) {
    $books_in_keranjang = query("SELECT * FROM buku WHERE id_buku IN (" . implode(',', $keranjang) . ")");
}

// Proses pinjam buku
if (isset($_POST['submit'])) {
    
    $tgl_ambil = $_POST['tgl_ambil'];

    // Validasi tanggal ambil
    $tgl_ambil_timestamp = strtotime($tgl_ambil);
    $tgl_sekarang_timestamp = strtotime(date("Y-m-d"));

    if ($tgl_ambil_timestamp < $tgl_sekarang_timestamp) {
        echo "<script>alert('Tanggal ambil harus setidaknya hari ini atau setelahnya.');</script>";
    } else {
        // Validasi keranjang kosong
        if (empty($keranjang)) {
            echo "<script>alert('Keranjang buku kosong. Tidak ada buku yang bisa dipinjam.');</script>";
        } else {
            // Proses peminjaman dengan memanggil fungsi pinjam()
            $result = pinjam($_POST, $keranjang);
            if ($result) {
                echo "<script>alert('Peminjaman berhasil dilakukan.'); window.location.href='dashboard_peminjam.php';</script>";
            } else {
                echo "<script>alert('Peminjaman gagal dilakukan.');</script>";
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'navbar.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Buku</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div style="margin: 20px;">
        <h1>Keranjang Buku</h1>
        <div class="container mt-3">
            <div class="row">
                <?php if(empty($books_in_keranjang)): ?>
                    <p>Keranjang kosong.</p>
                <?php else: ?>
                    <?php foreach ($books_in_keranjang as $book) : ?>
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $book['judul_buku']; ?></h5>
                                    <p class="card-text">Karya <?= $book['nama_pengarang']; ?></p>
                                    <form action="" method="post" style="display:inline;">
                                        <input type="hidden" name="id_buku" value="<?= $book['id_buku']; ?>">
                                        <button type="submit" name="hapus" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <hr>
        <div>
            <form action="" method="post">
                <div class="form-group">
                    <label for="tgl_ambil">Tanggal Ambil :</label>
                    <input type="date" name="tgl_ambil" id="tgl_ambil" class="form-control col-md-6" required>
                </div>
                <div class="form-group">
                    <label for="tgl_wajibkembali">Tanggal Wajib Kembali :</label>
                    <input type="text" name="tgl_wajibkembali" id="tgl_wajibkembali" class="form-control col-md-6" readonly>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Pinjam Buku</button>
            </form>
        </div>
        <div class="container text-center">
            <a href="pinjam.php" class="btn btn-primary">Kembali</a>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('tgl_ambil').addEventListener('change', function() {
            var tglAmbil = new Date(this.value);
            var tglWajibKembali = new Date(tglAmbil);
            tglWajibKembali.setDate(tglWajibKembali.getDate() + 7);
            document.getElementById('tgl_wajibkembali').value = tglWajibKembali.toISOString().slice(0,10);
        });
    </script>
</body>
</html>

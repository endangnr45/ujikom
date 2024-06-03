<?php 
$conn = mysqli_connect("localhost", "root", "", "perpustakaan");
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function registrasi($data) {
    global $conn;
    $nama_peminjam = strtolower(stripslashes($data["nama_peminjam"]));
    $tgl_daftar = date("Y-m-d H:i:s");
    $user_peminjam = strtolower(stripslashes($data["user_peminjam"]));
    $pass_peminjam = mysqli_real_escape_string($conn, $data["pass_peminjam"]);
    $foto_peminjam = 'user.jfif'; 
    $status_peminjam = 'active'; 
    $result = mysqli_query($conn, "SELECT user_peminjam FROM peminjam WHERE user_peminjam = '$user_peminjam'");
    if (mysqli_fetch_assoc($result)) {
        echo "<script>
                alert('Username sudah terdaftar');
              </script>";
        return false;
    }
    $password = password_hash($pass_peminjam, PASSWORD_DEFAULT);
    $query = "INSERT INTO peminjam (nama_peminjam, user_peminjam, pass_peminjam, foto_peminjam, status_peminjam, tgl_daftar)
              VALUES ('$nama_peminjam', '$user_peminjam', '$password', '$foto_peminjam', '$status_peminjam', '$tgl_daftar')";
    mysqli_query($conn, $query) or die(mysqli_error($conn));
    return mysqli_affected_rows($conn);
}

function tambah($data) {
    global $conn;
    $judul_buku = htmlspecialchars($data["judul_buku"]);
    $tgl_terbit = htmlspecialchars($data["tgl_terbit"]);
    $nama_pengarang = htmlspecialchars($data["nama_pengarang"]);
    $nama_penerbit = htmlspecialchars($data["nama_penerbit"]);

    // Cek apakah judul buku sudah ada
    $result = mysqli_query($conn, "SELECT judul_buku FROM buku WHERE judul_buku = '$judul_buku'");
    if (mysqli_fetch_assoc($result)) {
        return -1; // Kode error untuk buku sudah ada
    }

    $query = "INSERT INTO buku (judul_buku, tgl_terbit, nama_pengarang, nama_penerbit) 
              VALUES ('$judul_buku', '$tgl_terbit', '$nama_pengarang', '$nama_penerbit')";
    mysqli_query($conn, $query) or die(mysqli_error($conn));

    return mysqli_affected_rows($conn);
}

function getPeminjamIdByUsername($username) {
    global $conn;
    $query = "SELECT id_peminjam FROM peminjam WHERE user_peminjam = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row['id_peminjam'];
}
function getAdminIdByUsername($username) {
    global $conn;
    $query = "SELECT id_admin FROM admin WHERE user_admin = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row['id_admin'];
}

function pinjam($data, $keranjang) {
    global $conn;

    $tgl_pesan = date("Y-m-d H:i:s");
    $tgl_ambil = $data['tgl_ambil'];
    $tgl_wajibkembali = date('Y-m-d', strtotime($tgl_ambil . ' +7 days'));
    $status_pinjam = 'DIPROSES';
    $username = $_SESSION['username'];
    $id_peminjam = getPeminjamIdByUsername($username);
    $id_admin = 1;

    $query = "INSERT INTO peminjaman (id_peminjam, id_admin, tgl_pesan, tgl_ambil, tgl_wajibkembali, status_pinjam) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iissss", $id_peminjam, $id_admin, $tgl_pesan, $tgl_ambil, $tgl_wajibkembali, $status_pinjam);
    $executed = mysqli_stmt_execute($stmt);

    if (!$executed) {
        echo "Error: " . mysqli_error($conn);
    }

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $kode_pinjam = mysqli_insert_id($conn);

        foreach ($keranjang as $id_buku) {
            $query_detail = "INSERT INTO detail_peminjaman (id_buku, kode_pinjam) VALUES (?, ?)";
            $stmt_detail = mysqli_prepare($conn, $query_detail);
            mysqli_stmt_bind_param($stmt_detail, "ii", $id_buku, $kode_pinjam);
            mysqli_stmt_execute($stmt_detail);
        }

        unset($_SESSION['keranjang']);

        return true;
    } else {
        return false;
    }
}
?>

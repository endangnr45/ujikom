<?php 
session_start();
require 'functions.php';

if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Periksa di tabel peminjam
    $result_peminjam = mysqli_query($conn, "SELECT * FROM peminjam WHERE user_peminjam = '$username'");

    if (mysqli_num_rows($result_peminjam) == 1){
        $row_peminjam = mysqli_fetch_assoc($result_peminjam);
        if (password_verify($password, $row_peminjam["pass_peminjam"])){
            // Simpan nama pengguna dan peran ke dalam sesi
            $_SESSION["username"] = $username;
            $_SESSION["role"] = "peminjam";

            header("Location: index.php");
            exit;
        }
    }

    // Periksa di tabel admin
    $result_admin = mysqli_query($conn, "SELECT * FROM admin WHERE user_admin = '$username'");
    
    if (mysqli_num_rows($result_admin) == 1){
        $row_admin = mysqli_fetch_assoc($result_admin);
        if (password_verify($password, $row_admin["pass_admin"])){
            // Simpan nama pengguna dan peran ke dalam sesi
            $_SESSION["username"] = $username;
            $_SESSION["role"] = "admin";

            header("Location: dashboard_admin.php");
            exit;
        }
    }

    $error = true;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<?php include 'navbar.php'; ?>
<br>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login</title>
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
    <div class="container">
        <div class="form-container">
            <h2>Login</h2>
            <?php if(isset($error)) :?>
                <p style="color: red;">username / password salah</p>
            <?php  endif; ?>
            <form action="" method="post">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary">Login</button>
            </form>
            <p>Belum punya akun? <a href="register.php">Register</a></p>
        </div>
    </div>
</body>
</html>

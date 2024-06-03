<?php
require 'functions.php';

if (isset($_POST['register'])) {
    if (registrasi($_POST) > 0) {
        echo "<script>
                alert('User baru berhasil ditambahkan');
              </script>";
    } else {
        echo "<script>
                alert('User gagal ditambahkan');
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php include 'navbar.php'; ?>
<br>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Register</title>
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

<div class="form-container">
    <h2>Registrasi</h2>
    <form action="" method="post">
        <div class="form-group">
            <label for="nama_peminjam">Nama:</label>
            <input type="text" name="nama_peminjam" id="nama_peminjam" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="user_peminjam">Username:</label>
            <input type="text" name="user_peminjam" id="user_peminjam" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="pass_peminjam">Password:</label>
            <input type="password" name="pass_peminjam" id="pass_peminjam" class="form-control" required>
        </div>
        <button type="submit" name="register" class="btn btn-primary">Register</button>
    </form>
    <p>Sudah punya akun? <a href="login.php">Login</a></p>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>

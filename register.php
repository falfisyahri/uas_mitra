<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/UAS/includes/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Hash password menggunakan MD5
    $hashed_password = md5($password);

    // Menyiapkan dan mengeksekusi query
    $stmt = $koneksi->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $hashed_password, $email, $role);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Pendaftaran berhasil!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

$koneksi->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/uas/favicon.ico" type="image/x-icon"> <!-- Favicon -->
    <title>Pendaftaran Pengguna</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .footer {
            margin-top: auto;
            text-align: center;
            padding: 10px 0;
            background-color: #f8f9fa;
            border-top: 1px solid #e9ecef;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center flex-grow-1">
        <div class="card" style="width: 400px;">
            <div class="card-body">
                <h2 class="card-title text-center">Pendaftaran Pengguna</h2>
                <!-- Logo -->
                <img src="/uas/assets/images/favicon.png" alt="Logo" class="img-fluid mx-auto d-block" style="max-width: 120px; margin-bottom: 20px;"> 
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="role">Role:</label>
                        <select name="role" class="form-control" required>
                            <option value="pengajar">Pengajar</option>
                            <option value="user">User </option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Daftar</button>
                </form>
                <div class="register-link text-center mt-3">
                    <p>Sudah punya akun? <a href="login.php">Kembali ke Login</a></p> <!-- Text link for back -->
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <span class="text-muted">© 2023 Your Company Name. All rights reserved.</span>
        </div>
    </footer>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper .js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
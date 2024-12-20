<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: /UAS/dashboard/{$_SESSION['role']}.php");
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/UAS/includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = md5(trim($_POST['password']));

    $query = $koneksi->prepare("
        SELECT user_id, username, role 
        FROM users 
        WHERE username = ? AND password = ? AND role IN ('operator', 'pengajar', 'user')
    ");
    $query->bind_param("ss", $username, $password);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Set data sesi
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_id'] = $user['user_id'];

        header("Location: /UAS/dashboard/{$user['role']}.php");
        exit;
    } else {
        $error = "Username atau password salah, atau akun Anda tidak memiliki izin.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="/uas/favicon.ico" type="image/x-icon"> <!-- Favicon -->
    <title>Login</title>
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
                <h2 class="card-title text-center">Login</h2>
                <img src="/uas/assets/images/favicon.png" alt="Logo" class="img-fluid mx-auto d-block" style="max-width: 150px; margin-bottom: 20px;"> <!-- Logo -->
                <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                <form action="/UAS/login.php" method="post">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>
                <div class="register-link text-center mt-3">
                    <p>Belum punya akun? <a href="/UAS/register.php">Daftar di sini</a></p>
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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/UAS/includes/koneksi.php'; 

session_start();

if ($_SESSION['role'] != 'operator' && $_SESSION['role'] != 'pengajar') {
    header("Location: /UAS/login.php");
    exit;
}

if (isset($_POST['tambah_jadwal'])) {
    $user_id = $_SESSION['id'];
    $tanggal = $_POST['tanggal'];
    $waktu = $_POST['waktu'];
    $lokasi = $_POST['lokasi'];
    $topik = $_POST['topik'];

    $query = "INSERT INTO jadwal_pengajian (user_id, tanggal, waktu, lokasi, topik) 
              VALUES ('$user_id', '$tanggal', '$waktu', '$lokasi', '$topik')";

    if ($koneksi->query($query)) {
        echo "<script>alert('Jadwal berhasil ditambahkan'); window.location.href = 'jadwal_pengajian.php';</script>";
    } else {
        echo "Gagal menambahkan jadwal: " . $koneksi->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jadwal Pengajian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        main {
            flex: 1;
        }

        .navbar {
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 1rem 0;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
        <a class="navbar-brand" href="/uas/dashboard/index.php">
            <img src="/uas/assets/images/logo.png" alt="Logo" style="height: 40px;">
        </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="/UAS/index.php">Home</a>
                    </li>
                    <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'user.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="/UAS/dashboard/user.php">Jadwal</a>
                    </li>
                    <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'absensi.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="/UAS/dashboard/absensi.php">Absen</a>
                    </li>
                    <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'pengajuan_pengajar.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="/UAS/dashboard/pengajuan_pengajar.php">Pengajuan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/UAS/includes/logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <h2>Tambah Jadwal Pengajian</h2>

        <form action="tambah_jadwal.php" method="POST">
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" required>
            </div>
            <div class="mb-3">
                <label for="waktu" class="form-label">Waktu</label>
                <input type="time" class="form-control" id="waktu" name="waktu" required>
            </div>
            <div class="mb-3">
                <label for="lokasi" class="form-label">Tempat</label>
                <input type="text" class="form-control" id="lokasi" name="lokasi" required>
            </div>
            <div class="mb-3">
                <label for="topik" class="form-label">Topik</label>
                <input type="text" class="form-control" id="topik" name="topik" required>
            </div>
            <button type="submit" class="btn btn-primary" name="tambah_jadwal">Tambah Jadwal</button>
        </form>
    </div>

    <footer class="bg-dark text-white py-3">
        <div class="container text-center">
            <p class="mb-0">&copy; 2024 Jadwal Pengajian. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

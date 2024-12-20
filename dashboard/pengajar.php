<?php
    session_start();
    // Pastikan hanya pengajar yang dapat mengakses halaman ini
    if ($_SESSION['role'] != 'pengajar') {
        header("Location: /UAS/login.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pengajar</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/UAS/assets/css/styles.css" rel="stylesheet">
    <style>
        /* Ensure the body takes up the full height of the viewport */
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .container {
            flex: 1; /* Take up the remaining space */
        }

        .dashboard-header {
            margin-bottom: 2rem;
        }

        .dashboard-card {
            margin-bottom: 1.5rem;
        }

        .dashboard-card .card-body {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-dashboard {
            width: 100%;
        }

        .navbar-nav .nav-item.active .nav-link {
            background-color: #007bff;
            border-radius: 5px;
        }

        /* Footer style */
        footer {
            margin-top: auto; /* Push footer to the bottom */
            background-color: #343a40;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
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

    <!-- Main Content -->
    <div class="container py-5">
        <div class="dashboard-header">
            <h2>Dashboard Pengajar</h2>
            <p>Selamat datang di dashboard pengajar. Anda dapat mengelola jadwal pengajian dan melihat absensi pengajian.</p>
        </div>

        <!-- Dashboard Cards -->
        <div class="row">
            <!-- Jadwal Pengajian -->
            <div class="col-md-6">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title">Jadwal Pengajian</h5>
                        <a href="/UAS/dashboard/jadwal_pengajian.php" class="btn btn-primary btn-dashboard">Lihat Jadwal</a>
                    </div>
                </div>
            </div>
            <!-- Absensi Pengajian -->
            <div class="col-md-6">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title">Absensi Pengajian</h5>
                        <a href="/UAS/dashboard/absensi.php" class="btn btn-primary btn-dashboard">Lihat Absensi</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-3">
        <div class="container text-center">
            <p class="mb-0">&copy; 2024 Jadwal Pengajian. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

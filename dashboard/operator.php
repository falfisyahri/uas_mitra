<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/UAS/includes/auth.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/UAS/includes/koneksi.php';

// Inisialisasi variabel role dan username
if (isset($_SESSION['role'])) {
    $role = $_SESSION['role'];
} else {
    $role = ''; // Default jika tidak ada sesi
}

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    $username = 'Guest'; // Default untuk username
}

// Validasi akses hanya untuk operator
if ($role !== 'operator') {
    header("Location: /UAS/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Operator</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/UAS/assets/css/styles.css" rel="stylesheet">
    <style>
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
        footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 1rem 0;
            margin-top: 35px;
        }

    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="/UAS/index.php">
                <img src="/uas/assets/images/logo.png" alt="Logo" style="height: 40px;">
            </a>
            <!-- Toggle button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- Dashboard -->
                    <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == basename($dashboardUrl)) ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?php echo $dashboardUrl; ?>">Dashboard</a>
                    </li>
                    <!-- Jadwal -->
                    <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'jadwal_pengajian.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="/UAS/dashboard/jadwal_pengajian.php">Jadwal</a>
                    </li>
                    <!-- Pengajuan (hanya untuk operator) -->
                    <?php if ($role == 'operator') : ?>
                        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'pengajuan_pengajar.php') ? 'active' : ''; ?>">
                            <a class="nav-link" href="/UAS/dashboard/pengajuan_pengajar.php">Pengajuan</a>
                        </li>
                    <?php endif; ?>
                    <!-- Dropdown Menu Logout -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo htmlspecialchars($username); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="/UAS/includes/logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-5">
        <div class="dashboard-header">
            <h2>Dashboard Operator</h2>
            <p>Selamat datang di dashboard operator. Kelola jadwal pengajian, absensi, dan pengajuan pengajar.</p>
        </div>

        <!-- Dashboard Cards -->
        <div class="row">
            <!-- Jadwal Pengajian -->
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title">Jadwal Pengajian</h5>
                        <a href="/UAS/dashboard/jadwal_pengajian.php" class="btn btn-primary btn-dashboard">Kelola Jadwal</a>
                    </div>
                </div>
            </div>
            <!-- Absensi Pengajian -->
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title">Absensi Pengajian</h5>
                        <a href="/UAS/dashboard/absensi.php" class="btn btn-primary btn-dashboard">Kelola Absensi</a>
                    </div>
                </div>
            </div>
            <!-- Pengajuan Pengajar -->
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title">Pengajuan Pengajar</h5>
                        <a href="/UAS/dashboard/pengajuan_pengajar.php" class="btn btn-primary btn-dashboard">Kelola Pengajuan</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Links or Info -->
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Informasi Lainnya</h5>
                        <p>Gunakan panel di atas untuk mengelola pengajian, absensi, dan pengajuan pengajar. Pastikan untuk selalu memperbarui data dengan benar.</p>
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

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
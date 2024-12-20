<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/UAS/includes/koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['loggedin'])) {
    header("Location: /UAS/login.php");
    exit;
}

// Ambil nama dan role user
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Tentukan URL dashboard berdasarkan role
$dashboardUrl = '/UAS/dashboard/' . $role . '.php';
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Pengajian</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/uas/assets/css/styles.css" rel="stylesheet">
    <link rel="icon" href="/uas/favicon.ico" type="image/png">
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

        .header-image {
            position: relative;
            width: 100%;
            height: 300px; /* Sesuaikan tinggi header */
            background: url('/uas/assets/images/header.jpg') no-repeat center center;
            background-size: cover; /* Gambar memenuhi area */
            display: flex;
            align-items: center; /* Vertikal tengah */
            justify-content: center; /* Horizontal tengah */
            color: white;
            text-align: center;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
        }

        .header-overlay {
            background: rgba(0, 0, 0, 0.5); /* Overlay gelap untuk teks terlihat jelas */
            padding: 20px;
            border-radius: 10px;
        }

        .header-image h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .header-image p {
            font-size: 1.2rem;
        }

        h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .carousel-inner img {
            width: 100%;
            height: 400px; 
            object-fit: cover; 
        }

        footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 1rem 0;
        }

        .calendar-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
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
            </a <!-- Toggle button -->
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
                    <!-- Shop (hanya untuk operator) -->
                    <?php if ($role == 'operator') : ?>
                        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'shop.php') ? 'active' : ''; ?>">
                            <a class="nav-link" href="/UAS/dashboard/shop.php">Shop</a>
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
    <!-- Header -->
    <header class="header-image">
        <div class="header-overlay">
            <h1>Selamat Datang di Jadwal Pengajian</h1>
            <p>Temukan informasi pengajian terkini di sini</p>
        </div>
    </header>
    <!-- Main Content -->
    <main>
        <div class="container mt-4">
            <div class="row">
                <!-- Slider Gambar -->
                <div class="col-md-6">
                    <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="/uas/assets/images/image1.jpg" class="d-block w-100" alt="Product 1">
                            </div>
                            <div class="carousel-item">
                                <img src="/uas/assets/images/image2.jpg" class="d-block w-100" alt="Product 2">
                            </div>
                            <div class="carousel-item">
                                <img src="/uas/assets/images/image3.jpg" class="d-block w-100" alt="Product 3">
                            </div>
                        </div>
                        <!-- Tombol Navigasi -->
                        <a class="carousel-control-prev" href="#productCarousel" role="button" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#productCarousel" role="button" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </a>
                    </div>
                </div>

                <!-- Daftar Barang -->
                <div class="col-md-6">
                    <div class="list-group">
                        <!-- Query untuk barang -->
                        <?php
                        // Query untuk mengambil data barang
                        $query = "SELECT name, description, price FROM item_identity ORDER BY name ASC";
                        $result = $koneksi->query($query);

                        // Periksa apakah query berhasil
                        if ($result) {
                            if ($result->num_rows > 0): ?>
                                <div class="list-group">
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <a href="/uas/detail_barang.php?name=<?php echo urlencode($row['name']); ?>" class="list-group-item list-group-item-action">
                                            <h5 class="mb-1"><?php echo htmlspecialchars($row['name']); ?></h5>
                                            <p class="mb-1"><? 
                                            echo htmlspecialchars($row['description']); ?></p>
                                            <small>Rp<?php echo number_format($row['price'], 0, ',', '.'); ?></small>
                                        </a>
                                    <?php endwhile; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">Belum ada barang yang tersedia.</p>
                            <?php endif; ?>
                        <?php 
                        } else {
                            echo "<p class='text-danger'>Terjadi kesalahan pada query database: " . $koneksi->error . "</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
            <!-- Tombol ke halaman jualan -->
            <div class="text-center py-4">
                <a href="/uas/dashboard/shop.php" class="btn btn-primary btn-lg">Lihat Semua Barang</a>
            </div>
        </div>
        <div class="container mt-4">
            <div class="row">
                <!-- Slider Gambar -->
                <div class="col-md-8">
                    <div id="imageCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="/uas/assets/images/image1.jpg" class="d-block w-100" alt="Pengajian Image 1">
                            </div>
                            <div class="carousel-item">
                                <img src="/uas/assets/images/image2.jpg" class="d-block w-100" alt="Pengajian Image 2">
                            </div>
                            <div class="carousel-item">
                                <img src="/uas/assets/images/image3.jpg" class="d-block w-100" alt="Pengajian Image 3">
                            </div>
                        </div>
                        <!-- Tombol Navigasi -->
                        <a class="carousel-control-prev" href="#imageCarousel" role="button" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#imageCarousel" role="button" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </a>
                    </div>
                </div>

                <!-- Kalender Dinamis -->
                <div class="col-md-4">
                    <div class="calendar-container">
                        <h3 class="text-center" id="calendar-title"></h3>
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>Sun</th><th>Mon</th><th>Tue</th>
                                    <th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th>
                                </tr>
                            </thead>
                            <tbody id="calendar-body"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center py-5">
            <a href="/uas/dashboard/jadwal_pengajian.php" class="btn btn-primary btn-lg">Lihat Jadwal Sekarang</a>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-3">
        <div class="container text-center">
            <p class="mb-0">&copy; 2024 Jadwal Pengajian. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" defer></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const calendarBody = document.getElementById("calendar-body");
        const calendarTitle = document.getElementById("calendar-title");

        const now = new Date();
        let currentMonth = now.getMonth();
        let currentYear = now.getFullYear();

        const months = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];

        function generateCalendar(month, year) {
            calendarTitle.textContent = `${months[month]} ${year}`;
            calendarBody.innerHTML = "";

            const firstDay = new Date(year, month, 1).getDay();
            const totalDays = new Date(year, month + 1, 0).getDate();
            let date = 1;

            for (let i = 0; i < 6; i++) { // Fixed the opening bracket
                const row = document.createElement("tr"); // Declare the row variable here
                for (let j = 0; j < 7; j++) {
                    const cell = document.createElement("td");
                    if (i === 0 && j < firstDay) {
                        cell.textContent = "";
                    } else if (date > totalDays) {
                        break;
                    } else {
                        cell.textContent = date;
                        date++;
                    }
                    row.appendChild(cell);
                }
                calendarBody.appendChild(row);
                if (date > totalDays) break;
            }
        }

        generateCalendar(currentMonth, currentYear);
    });
</script>
</body>
</html>
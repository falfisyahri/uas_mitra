<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/UAS/includes/koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['loggedin'])) {
    header("Location: /UAS/login.php");
    exit;
}

// Ambil data produk dari database
$query = "SELECT * FROM item_identity";
$result = $koneksi->query($query);
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Jadwal Pengajian</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/uas/assets/css/styles.css" rel="stylesheet">
    <link rel="icon" href="/uas/favicon.ico" type="image/png">
    <style>
        .product-card {
            margin-bottom: 1.5rem;
        }
        .product-card img {
            max-height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/UAS/index.php">
                <img src="/uas/assets/images/logo.png" alt="Logo" style="height: 40px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/UAS/dashboard/index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/UAS/dashboard/jadwal_pengajian.php">Jadwal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/UAS/shop.php">Shop</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo htmlspecialchars($_SESSION['username'] ?? 'Guest'); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="/UAS/includes/logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

<main class="container py-5">
    <h2 class="mb-4">Shop</h2>
    <div class="row">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($item = $result->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card product-card">
                        <img src="<?php echo !empty($item['image_url']) ? htmlspecialchars($item['image_url']) : '/uas/assets/images/default-image.png'; ?>" 
                             class="card-img-top" alt="Product Image">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php echo htmlspecialchars($item['name'] ?? 'Unknown Product'); ?>
                            </h5>
                            <p class="card-text">
                                Price: Rp<?php echo isset($item['price']) ? number_format($item['price'], 0, ',', '.') : 'N/A'; ?>
                            </p>
                            <form method="POST" action="/UAS/cart/add_to_cart.php">
                                <input type="hidden" name="item_id" value="<?php echo $item['id'] ?? ''; ?>">
                                <button type="submit" class="btn btn-primary" <?php echo empty($item['id']) ? 'enable' : ''; ?>>Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">No items available in the shop.</p>
        <?php endif; ?>
    </div>
</main>

<!-- Footer -->
<footer class="bg-dark text-white py-3">
    <div class="container text-center">
        <p class="mb-0">&copy; 2024 Jadwal Pengajian. All Rights Reserved.</p>
    </div>
</footer>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

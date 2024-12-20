<?php 
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/UAS/includes/auth.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/UAS/includes/koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['loggedin'])) {
    header("Location: /UAS/login.php");
    exit;
}

// Mendapatkan user_id dari session
$user_id = $_SESSION['user_id'];
$query = "SELECT ci.cart_item_id, ci.quantity, i.name, i.price 
          FROM cart c
          JOIN cart_items ci ON c.cart_id = ci.cart_id
          JOIN item_identity i ON ci.item_id = i.item_id
          WHERE c.user_id = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
$total_amount = 0;

while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $total_amount += $row['price'] * $row['quantity'];
}

$stmt->close();

// Jika keranjang kosong, redirect ke shop
if (empty($cart_items)) {
    $_SESSION['error_message'] = "Keranjang belanja Anda kosong.";
    header("Location: /UAS/dashboard/shop.php");
    exit;
}

// Proses Checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Insert order ke database
    $order_query = "INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'pending')";
    $order_stmt = $koneksi->prepare($order_query);
    $order_stmt->bind_param('id', $user_id, $total_amount);

    if ($order_stmt->execute()) {
        $order_id = $order_stmt->insert_id;

        // Insert detail order
        $order_detail_query = "INSERT INTO order_details (order_id, item_id, quantity, price) VALUES (?, ?, ?, ?)";
        $order_detail_stmt = $koneksi->prepare($order_detail_query);

        foreach ($cart_items as $item) {
            $order_detail_stmt->bind_param('iiid', $order_id, $item['item_id'], $item['quantity'], $item['price']);
            $order_detail_stmt->execute();
        }

        $order_detail_stmt->close();

        // Kosongkan keranjang
        $clear_cart_query = "DELETE FROM cart_items WHERE cart_id = (SELECT cart_id FROM cart WHERE user_id = ?)";
        $clear_cart_stmt = $koneksi->prepare($clear_cart_query);
        $clear_cart_stmt->bind_param('i', $user_id);
        $clear_cart_stmt->execute();
        $clear_cart_stmt->close();

        // Redirect ke payment page
        $_SESSION['order_id'] = $order_id;
        header("Location: /UAS/dashboard/payment.php?order_id=" . $order_id);
        exit;
    } else {
        $_SESSION['error_message'] = "Terjadi kesalahan saat memproses checkout.";
    }

    $order_stmt->close();
}
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/uas/assets/css/styles.css" rel="stylesheet">
    <link rel="icon" href="/uas/favicon.ico" type="image/png">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/UAS/dashboard/index.php">
                <img src="/uas/assets/images/logo.png" alt="Logo" style="height: 40px;">
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-5">
        <h1>Checkout</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td><?php echo number_format($item['price'], 2); ?></td>
                        <td><?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                    <td><strong><?php echo number_format($total_amount, 2); ?></strong></td>
                </tr>
            </tfoot>
        </table>
        <form method="POST" action="">
            <button type="submit" class="btn btn-primary">Proceed to Payment</button>
        </form>
    </div>

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

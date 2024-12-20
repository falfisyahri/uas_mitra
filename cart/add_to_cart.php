<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/UAS/includes/koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['loggedin'])) {
    header("Location: /UAS/login.php");
    exit;
}

// Periksa apakah request POST diterima
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Periksa apakah item_id dikirimkan
    if (isset($_POST['item_id']) && is_numeric($_POST['item_id'])) {
        $item_id = (int) $_POST['item_id'];
        $user_id = $_SESSION['user_id']; // Pastikan `user_id` ada di session setelah login
        
        // Query untuk mendapatkan informasi produk
        $query = "SELECT * FROM item_identity WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $item_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $item = $result->fetch_assoc();
            
            // Tambahkan item ke keranjang (tabel `cart`)
            $insert_query = "INSERT INTO cart (user_id, item_id, quantity) VALUES (?, ?, 1) 
                             ON DUPLICATE KEY UPDATE quantity = quantity + 1";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param('ii', $user_id, $item_id);

            if ($insert_stmt->execute()) {
                $_SESSION['success_message'] = "Item '{$item['name']}' berhasil ditambahkan ke keranjang.";
                header("Location: /UAS/dashboard/shop.php");
                exit;
            } else {
                $_SESSION['error_message'] = "Terjadi kesalahan saat menambahkan item ke keranjang.";
            }
        } else {
            $_SESSION['error_message'] = "Item tidak ditemukan.";
        }
        $stmt->close();
    } else {
        $_SESSION['error_message'] = "Item ID tidak valid.";
    }
} else {
    $_SESSION['error_message'] = "Permintaan tidak valid.";
}

// Redirect kembali ke halaman shop jika ada kesalahan
header("Location: /UAS/dashboard/shop.php");
exit;

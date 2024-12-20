<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/UAS/includes/koneksi.php';
session_start();

// Validasi peran user
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['operator', 'pengajar', 'user'])) {
    header("Location: /UAS/login.php");
    exit;
}

// Ambil data role dan username dari session
$role = $_SESSION['role'];
$username = $_SESSION['username'];

// Hak akses berdasarkan peran
$canEdit = in_array($role, ['operator', 'pengajar']);
$canDelete = ($role == 'operator');

// Query untuk mengambil data jadwal pengajian
$query = "SELECT j.jadwal_id AS id, 
                 j.tanggal, 
                 j.waktu, 
                 j.lokasi, 
                 ii.name, 
                 u.username AS pengajar 
          FROM jadwal j
          LEFT JOIN item_identity ii ON j.item_id = ii.item_id
          LEFT JOIN users u ON j.pengajar_id = u.user_id";
$result = $koneksi->query($query);

if (!$result) {
    die("Query gagal: " . $koneksi->error);
}

// Menangani penghapusan jadwal
if (isset($_GET['delete']) && $canDelete) {
    $jadwal_id = intval($_GET['delete']);
    $deleteQuery = "DELETE FROM jadwal WHERE jadwal_id = ?";
    $stmt = $koneksi->prepare($deleteQuery);
    $stmt->bind_param("i", $jadwal_id);
    if ($stmt->execute()) {
        header("Location: jadwal_pengajian.php");
        exit;
    } else {
        echo "Gagal menghapus jadwal: " . $stmt->error;
    }
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Pengajian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color: #343a40;
        }
        .navbar a {
            color: white !important;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .table {
            background-color: #343a40;
            color: white;
            border: 2px solid black;
            border-radius: 5px;
        }
        .table th, .table td {
            border-color: black;
        }
        .table th {
            background-color: #23272b;
        }
        .table tbody tr:hover {
            background-color: #495057;
        }
        footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 1rem 0;
            margin-top: 239px;
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

    <!-- Konten Utama -->
    <div class="container py-5">
        <h2 class="mb-4">Jadwal Pengajian</h2>

        <!-- Tombol Tambah Jadwal -->
        <?php if ($canEdit) { ?>
            <a href="tambah_jadwal.php" class="btn btn-success mb-3">Tambah Jadwal</a>
        <?php } ?>

        <!-- Tabel Jadwal Pengajian -->
        <table class="table table-bordered table-hover text-center">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Tempat</th>
                    <th>Bahan</th>
                    <th>Pengajar</th>
                    <?php if ($canEdit) { ?>
                        <th>Aksi</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['tanggal']); ?></td>
                        <td><?php echo htmlspecialchars($row['waktu']); ?></td>
                        <td><?php echo htmlspecialchars($row['lokasi']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_bahan']); ?></td>
                        <td><?php echo htmlspecialchars($row['pengajar']); ?></td>
                        <?php if ($canEdit) { ?>
                            <td>
                                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal"
                                        data-id="<?php echo $row['id']; ?>" 
                                        data-tanggal="<?php echo $row['tanggal']; ?>" 
                                        data-waktu="<?php echo $row['waktu']; ?>" 
                                        data-lokasi="<?php echo $row['lokasi']; ?>">Edit</button>
                                <?php if ($canDelete) { ?>
                                    <a href="jadwal_pengajian.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');">Hapus</a>
                                <?php } ?>
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Jadwal Pengajian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="edit_jadwal.php" method="POST">
                        <input type="hidden" name="jadwal_id" id="jadwal_id">
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
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        &copy; 2024 Jadwal Pengajian. All Rights Reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mengisi data ke modal edit
        var editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var tanggal = button.getAttribute('data-tanggal');
            var waktu = button.getAttribute('data-waktu');
            var lokasi = button.getAttribute('data-lokasi');

            document.getElementById('jadwal_id').value = id;
            document.getElementById('tanggal').value = tanggal;
            document.getElementById('waktu').value = waktu;
            document.getElementById('lokasi').value = lokasi;
        });
    </script>
</body>
</html>

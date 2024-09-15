<?php
session_start();
include '../koneksi.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

// Menangani aksi hapus banyak data
if (isset($_POST['delete_selected'])) {
    // Periksa apakah ada ID yang dipilih
    if (isset($_POST['ids']) && !empty($_POST['ids'])) {
        $ids = $_POST['ids'];
        $idsToDelete = implode(',', array_map('intval', $ids)); // Amankan ID untuk query SQL

        // Debugging: Periksa apakah ID dikirim
        echo "<pre>";
        print_r($ids); // Ini akan mencetak ID yang dipilih untuk memeriksa apakah mereka benar
        echo "</pre>";

        $deleteQuery = "DELETE FROM pendaftaran WHERE id IN ($idsToDelete)";

        // Debugging: Periksa query hapus
        echo $deleteQuery;

        if ($conn->query($deleteQuery) === TRUE) {
            echo "<div class='alert alert-success'>Data yang dipilih berhasil dihapus</div>";
        } else {
            echo "<div class='alert alert-danger'>Kesalahan saat menghapus data: " . $conn->error . "</div>";
        }

        header("Location: dashboard.php");
        exit();
    } else {
        // Tidak ada ID yang dipilih
        echo "<div class='alert alert-warning'>Tidak ada data yang dipilih untuk dihapus.</div>";
    }
}

// Menangani aksi hapus individu
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $deleteQuery = "DELETE FROM pendaftaran WHERE id = $id";
    if ($conn->query($deleteQuery) === TRUE) {
        echo "<div class='alert alert-success'>Data berhasil dihapus</div>";
    } else {
        echo "<div class='alert alert-danger'>Kesalahan saat menghapus data: " . $conn->error . "</div>";
    }
    header("Location: dashboard.php");
    exit();
}

// Menangani logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Query data
$pendaftaranQuery = "SELECT * FROM pendaftaran";
$pendaftaranResult = $conn->query($pendaftaranQuery);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 20px;
        }

        .table-rounded {
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        table {
            background: #fff;
            margin-top: 1rem;
        }

        footer {
            background: #f8f9fa;
            padding: 1rem;
            text-align: center;
            width: 100%;
        }

        .table th,
        .table td {
            vertical-align: middle;
            text-align: center;
        }

        .capitalize {
            text-transform: capitalize;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="mb-4">Dashboard Admin</h1>

        <!-- Tambah Data Pendaftaran Baru -->
        <a href="add.php" class="btn btn-primary mb-3">Tambah Data Baru</a>

        <!-- Tombol Logout -->
        <a href="dashboard.php?logout=true" class="btn btn-secondary mb-3">Logout</a>

        <!-- Formulir untuk Hapus Banyak Data -->
        <form method="POST" action="dashboard.php">
            <h2 class="mb-4">Data Pendaftaran Beasiswa</h2>
            <table class="table table-bordered table-rounded">
                <thead>
                    <tr>
                        <th>Pilih</th>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No HP</th>
                        <th>Semester</th>
                        <th>IPK</th>
                        <th>Beasiswa</th>
                        <th>Berkas</th>
                        <th>Status Pengajuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Inisialisasi penghitung
                    $counter = 1;
                    while ($row = $pendaftaranResult->fetch_assoc()):
                    ?>
                        <tr>
                            <td><input type="checkbox" name="ids[]" value="<?= $row['id'] ?>"></td>
                            <td><?= $counter++ ?></td>
                            <td class="capitalize"><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td class="capitalize"><?= htmlspecialchars($row['no_hp']) ?></td>
                            <td class="capitalize"><?= htmlspecialchars($row['semester']) ?></td>
                            <td class="capitalize"><?= htmlspecialchars($row['ipk']) ?></td>
                            <td class="capitalize"><?= htmlspecialchars($row['beasiswa']) ?></td>
                            <td>
                                <?php if ($row['berkas']): ?>
                                    <a href="../assets/berkas/<?= htmlspecialchars($row['berkas']) ?>" download>Unduh</a>
                                <?php else: ?>
                                    Tidak ada berkas
                                <?php endif; ?>
                            </td>
                            <td class="capitalize"><?= htmlspecialchars($row['status_ajuan']) ?></td>
                            <td>
                                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="dashboard.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!-- Tombol Hapus Banyak Data -->
            <button type="submit" name="delete_selected" class="btn btn-danger">Hapus yang Dipilih</button>
        </form>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
$conn->close();
?>
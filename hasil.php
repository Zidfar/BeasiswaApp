<?php
// Koneksi ke database
include 'koneksi.php';

// Query untuk mengambil semua data dari tabel pendaftaran
$query = "SELECT * FROM pendaftaran";
$result = $conn->query($query);

if ($result->num_rows > 0) {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hasil Pendaftaran</title>
        <link href="assets/css/bootstrap.min.css" rel="stylesheet">
        <style>
            html,
            body {
                height: 100%;
                margin: 0;
            }

            body {
                display: flex;
                flex-direction: column;
                min-height: 100vh;
                background-color: #f5f5f5;
            }

            .container {
                flex: 1;
                padding: 1.5rem;
                /* Menambah padding */
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
        <?php include 'navbar.php'; ?>

        <div class="container mt-5">
            <h2 class="mb-4">Hasil Pendaftaran</h2>
            <table class="table table-bordered table-rounded">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Nomor HP</th>
                        <th>Semester</th>
                        <th>IPK</th>
                        <th>Beasiswa</th>
                        <th>Berkas</th>
                        <th>Status Ajuan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="capitalize"><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td class="capitalize"><?= htmlspecialchars($row['no_hp']) ?></td>
                            <td class="capitalize"><?= htmlspecialchars($row['semester']) ?></td>
                            <td class="capitalize"><?= htmlspecialchars($row['ipk']) ?></td>
                            <td class="capitalize"><?= htmlspecialchars($row['beasiswa']) ?></td>
                            <td>
                                <?php if ($row['berkas']): ?>
                                    <?= htmlspecialchars(basename($row['berkas'])) ?>
                                <?php else: ?>
                                    Tidak ada berkas
                                <?php endif; ?>
                            </td>

                            <td class="capitalize"><?= htmlspecialchars($row['status_ajuan']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <?php include 'footer.php'; ?>

        <script src="assets/js/bootstrap.bundle.min.js"></script>
    </body>

    </html>
<?php
} else {
    echo "<div class='container mt-5'><p>Data tidak ditemukan!</p></div>";
}
$conn->close();
?>
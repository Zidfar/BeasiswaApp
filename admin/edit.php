<?php
include '../koneksi.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Menangani pengiriman formulir
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nama = $_POST['nama'];
        $email = $_POST['email'];
        $no_hp = $_POST['no_hp'];
        $semester = $_POST['semester'];
        $ipk = $_POST['ipk'];
        $beasiswa = $_POST['beasiswa'];
        $status_ajuan = $_POST['status_ajuan'];

        // Menangani unggahan file
        $berkas = $_FILES['berkas']['name'];
        $berkas_temp = $_FILES['berkas']['tmp_name'];
        $upload_dir = '../assets/berkas/';
        $allowed_types = ['application/zip', 'application/x-rar-compressed', 'application/pdf'];
        $file_type = $_FILES['berkas']['type'];

        if ($berkas) {
            if (in_array($file_type, $allowed_types)) {
                $file_path = $upload_dir . basename($berkas);
                if (move_uploaded_file($berkas_temp, $file_path)) {
                    $berkas = basename($berkas); // Perbarui nama file jika diunggah dengan sukses
                } else {
                    echo "<div class='alert alert-danger'>Kesalahan saat mengunggah file.</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Jenis file tidak valid. Hanya zip, rar, dan pdf yang diperbolehkan.</div>";
            }
        } else {
            // Pertahankan file yang ada jika tidak ada file baru yang diunggah
            $existing_result = $conn->query("SELECT berkas FROM pendaftaran WHERE id=$id");
            $existing_data = $existing_result->fetch_assoc();
            $berkas = $existing_data['berkas'];
        }

        $updateQuery = "UPDATE pendaftaran SET nama='$nama', email='$email', no_hp='$no_hp', semester='$semester', ipk='$ipk', beasiswa='$beasiswa', berkas='$berkas', status_ajuan='$status_ajuan' WHERE id=$id";

        if ($conn->query($updateQuery) === TRUE) {
            echo "<div class='alert alert-success'>Data berhasil diperbarui</div>";
            header("Location: dashboard.php"); // Arahkan setelah pembaruan
            exit();
        } else {
            echo "<div class='alert alert-danger'>Kesalahan saat memperbarui data: " . $conn->error . "</div>";
        }
    }

    // Ambil data yang ada
    $result = $conn->query("SELECT * FROM pendaftaran WHERE id=$id");
    $data = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pendaftaran</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .capitalize {
            text-transform: capitalize;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="mb-4">Edit Pendaftaran</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" class="form-control capitalize" id="nama" name="nama" value="<?php echo htmlspecialchars($data['nama']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control " id="email" name="email" value="<?php echo htmlspecialchars($data['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="no_hp" class="form-label">No HP</label>
                <input type="text" class="form-control capitalize" id="no_hp" name="no_hp" value="<?php echo htmlspecialchars($data['no_hp']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="semester" class="form-label">Semester</label>
                <select class="form-select capitalize" id="semester" name="semester" required>
                    <?php for ($i = 1; $i <= 8; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo ($data['semester'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="ipk" class="form-label">IPK</label>
                <input type="number" step="0.01" class="form-control capitalize" id="ipk" name="ipk" value="<?php echo htmlspecialchars($data['ipk']); ?>" required readonly>
            </div>
            <div class="mb-3">
                <label for="beasiswa" class="form-label">Beasiswa</label>
                <select class="form-select capitalize" id="beasiswa" name="beasiswa" required>
                    <option value="non-akademik" <?php echo ($data['beasiswa'] == 'non-akademik') ? 'selected' : ''; ?>>Non-Akademik</option>
                    <option value="akademik" <?php echo ($data['beasiswa'] == 'akademik') ? 'selected' : ''; ?>>Akademik</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="berkas" class="form-label">Berkas</label>
                <input type="file" class="form-control" id="berkas" name="berkas">
                <?php if ($data['berkas']): ?>
                    <small>File saat ini: <a href="../assets/berkas/<?php echo htmlspecialchars($data['berkas']); ?>" target="_blank"><?php echo htmlspecialchars($data['berkas']); ?></a></small>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="status_ajuan" class="form-label">Status Pengajuan</label>
                <select class="form-select capitalize" id="status_ajuan" name="status_ajuan" required>
                    <option value="belum diverifikasi" <?php echo ($data['status_ajuan'] == 'belum diverifikasi') ? 'selected' : ''; ?>>Belum Diverifikasi</option>
                    <option value="ditolak" <?php echo ($data['status_ajuan'] == 'ditolak') ? 'selected' : ''; ?>>Ditolak</option>
                    <option value="diterima" <?php echo ($data['status_ajuan'] == 'diterima') ? 'selected' : ''; ?>>Diterima</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Perbarui</button>
        </form>

    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
$conn->close();
?>
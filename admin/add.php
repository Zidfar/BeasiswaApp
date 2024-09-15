<?php
// Memasukkan koneksi
include '../koneksi.php';

// Query untuk mendapatkan nama, semester, dan IPK dari tabel mahasiswa
$mahasiswa_query = "SELECT nama, semester, ipk FROM mahasiswa";
$mahasiswa_result = $conn->query($mahasiswa_query);

// Array untuk menyimpan data mahasiswa
$mahasiswa_data = [];
while ($row = $mahasiswa_result->fetch_assoc()) {
    $mahasiswa_data[$row['nama']][$row['semester']] = $row['ipk'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Menggunakan htmlspecialchars untuk mengamankan input
    $nama = htmlspecialchars($_POST['nama']);
    $email = htmlspecialchars($_POST['email']);
    $no_hp = htmlspecialchars($_POST['no_hp']);
    $semester = htmlspecialchars($_POST['semester']);
    $ipk = isset($mahasiswa_data[$nama][$semester]) ? $mahasiswa_data[$nama][$semester] : '';
    $beasiswa = isset($_POST['beasiswa']) ? htmlspecialchars($_POST['beasiswa']) : '';
    $status_ajuan = htmlspecialchars($_POST['status_ajuan']); // Menambahkan input status_ajuan
    $berkas = '';

    // Validasi IPK dan atur status beasiswa
    if ($ipk < 3.00) {
        echo "<script>
                document.getElementById('beasiswa').disabled = true;
                document.getElementById('berkas').disabled = true;
                document.getElementById('submit').disabled = true;
              </script>";
        $beasiswa = '';
    }

    // Validasi dan upload berkas tanpa alert, hanya warning
    if ($_FILES['berkas']['name']) {
        $allowed_types = ['application/zip', 'application/x-rar-compressed', 'application/pdf'];

        // Mengecek apakah tipe file yang di-upload diperbolehkan
        if (in_array($_FILES['berkas']['type'], $allowed_types)) {
            // Menentukan lokasi penyimpanan file di direktori assets/berkas/
            $target_dir = 'assets/berkas/';
            $berkas = $target_dir . basename($_FILES['berkas']['name']);

            // Pindahkan file yang di-upload ke direktori yang ditentukan
            if (!move_uploaded_file($_FILES['berkas']['tmp_name'], $berkas)) {
                echo "<script>document.getElementById('warning-message').innerText = 'Terjadi kesalahan saat mengunggah berkas!';</script>";
            }
        } else {
            echo "<script>document.getElementById('warning-message').innerText = 'Berkas harus berformat .rar, .zip, atau .pdf!';</script>";
        }
    }

    // Simpan data ke database setelah validasi
    $insert_query = "INSERT INTO pendaftaran (nama, email, no_hp, semester, ipk, beasiswa, berkas, status_ajuan)
                     VALUES ('$nama', '$email', '$no_hp', '$semester', '$ipk', '$beasiswa', '$berkas', '$status_ajuan')";

    if ($conn->query($insert_query) === TRUE) {
        echo "<script>alert('Pendaftaran berhasil!'); window.location.href = 'dashboard.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "'); window.location.href = 'dashboard.php.php';</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pendaftaran Beasiswa</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Form Pendaftaran Beasiswa</h2>
        <form action="add.php" method="POST" enctype="multipart/form-data" class="p-4 border rounded">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama:</label>
                <select name="nama" id="nama" class="form-select" required>
                    <option value="">Pilih Nama</option>
                    <?php
                    foreach ($mahasiswa_data as $nama => $semester_data) {
                        echo "<option value='$nama'>$nama</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="no_hp" class="form-label">No HP:</label>
                <input type="number" name="no_hp" id="no_hp" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="semester" class="form-label">Semester:</label>
                <select name="semester" id="semester" class="form-select" required>
                    <option value="">Pilih Semester</option>
                    <?php
                    foreach (range(1, 8) as $semester) {
                        echo "<option value='$semester'>$semester</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="ipk" class="form-label">IPK:</label>
                <input type="text" name="ipk" id="ipk" class="form-control" readonly>
                <small id="warning-message" class="text-danger"></small> <!-- Elemen untuk pesan peringatan -->
            </div>


            <div class="mb-3">
                <label for="beasiswa" class="form-label">Pilihan Beasiswa:</label>
                <select name="beasiswa" id="beasiswa" class="form-select">
                    <option value="">Pilih Beasiswa</option>
                    <option value="Akademik">Akademik</option>
                    <option value="Non-Akademik">Non-Akademik</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="berkas" class="form-label">Upload Berkas (rar/zip/pdf):</label>
                <input type="file" name="berkas" id="berkas" class="form-control" accept=".rar,.zip,.pdf">
            </div>

            <div class="mb-3">
                <label for="status_ajuan" class="form-label">Status Pengajuan</label>
                <select class="form-select" id="status_ajuan" name="status_ajuan" required>
                    <option value="belum diverifikasi">Belum Diverifikasi</option>
                    <option value="ditolak">Ditolak</option>
                    <option value="diterima">Diterima</option>
                </select>
            </div>


            <div class="text-center">
                <input type="submit" id="submit" class="btn btn-primary" value="Add">
            </div>
        </form>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('nama').addEventListener('change', function() {
            var nama = this.value;
            var semester = document.getElementById('semester').value;
            var ipkField = document.getElementById('ipk');
            var warningField = document.getElementById('warning-message');

            // Cek jika nama dan semester belum dipilih
            if (nama && semester) {
                var ipk = <?php echo json_encode($mahasiswa_data); ?>[nama][semester];

                if (ipk !== undefined) {
                    ipkField.value = ipk;
                    warningField.innerText = ''; // Hapus pesan peringatan jika data sesuai
                    if (ipk < 3.00) {
                        document.getElementById('beasiswa').disabled = true;
                        document.getElementById('berkas').disabled = true;
                        document.getElementById('submit').disabled = true;
                    } else {
                        document.getElementById('beasiswa').disabled = false;
                        document.getElementById('berkas').disabled = false;
                        document.getElementById('submit').disabled = false;
                    }
                } else {
                    ipkField.value = '';
                    warningField.innerText = 'Nama atau Semester tidak sesuai'; // Tampilkan pesan peringatan
                    document.getElementById('beasiswa').disabled = true;
                    document.getElementById('berkas').disabled = true;
                    document.getElementById('submit').disabled = true;
                }
            } else {
                ipkField.value = '';
                warningField.innerText = ''; // Jangan tampilkan pesan peringatan jika salah satu belum diisi
                document.getElementById('beasiswa').disabled = true;
                document.getElementById('berkas').disabled = true;
                document.getElementById('submit').disabled = true;
            }
        });

        document.getElementById('semester').addEventListener('change', function() {
            var nama = document.getElementById('nama').value;
            var semester = this.value;
            var ipkField = document.getElementById('ipk');
            var warningField = document.getElementById('warning-message');

            // Cek jika nama dan semester belum dipilih
            if (nama && semester) {
                var ipk = <?php echo json_encode($mahasiswa_data); ?>[nama][semester];

                if (ipk !== undefined) {
                    ipkField.value = ipk;
                    warningField.innerText = ''; // Hapus pesan peringatan jika data sesuai
                    if (ipk < 3.00) {
                        document.getElementById('beasiswa').disabled = true;
                        document.getElementById('berkas').disabled = true;
                        document.getElementById('submit').disabled = true;
                    } else {
                        document.getElementById('beasiswa').disabled = false;
                        document.getElementById('berkas').disabled = false;
                        document.getElementById('submit').disabled = false;
                    }
                } else {
                    ipkField.value = '';
                    warningField.innerText = 'Nama atau Semester tidak sesuai'; // Tampilkan pesan peringatan
                    document.getElementById('beasiswa').disabled = true;
                    document.getElementById('berkas').disabled = true;
                    document.getElementById('submit').disabled = true;
                }
            } else {
                ipkField.value = '';
                warningField.innerText = ''; // Jangan tampilkan pesan peringatan jika salah satu belum diisi
                document.getElementById('beasiswa').disabled = true;
                document.getElementById('berkas').disabled = true;
                document.getElementById('submit').disabled = true;
            }
        });
    </script>


</body>

</html>
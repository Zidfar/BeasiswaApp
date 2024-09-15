<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-brand span {
            color: blue;
        }

        .navbar-brand {
            cursor: pointer;
            /* Menambahkan pointer cursor untuk menunjukkan bahwa logo dapat diklik */
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" id="logo" href="#">
                <img src="assets/img/logo.png" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
                Beasiswa <span style="color: blue;">Kamu</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?#jenis-beasiswa">Jenis Beasiswa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pendaftaran.php">Pendaftaran</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="hasil.php">Hasil</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script>
        // JavaScript untuk menangani double click pada logo
        let clickCount = 0;
        document.getElementById('logo').addEventListener('click', function() {
            clickCount++;
            if (clickCount === 2) {
                window.location.href = 'admin/login.php'; // Arahkan ke halaman login admin
                clickCount = 0; // Reset counter
            }
            setTimeout(() => clickCount = 0, 500); // Reset counter jika tidak ada klik kedua dalam 500ms
        });
    </script>
</body>

</html>
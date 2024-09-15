<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beasiswa Kamu</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <?php include 'navbar.php'; ?>

    <div class="container">
        <!-- Hero Section -->
        <section id="hero" class="py-5 d-flex align-items-center" style="min-height: 100vh;">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1>Beasiswa Terbaik untuk Masa Depanmu</h1>
                    <p>Dapatkan beasiswa dari berbagai kategori dan raih impianmu bersama Beasiswa Kampus.</p>
                </div>
                <div class="col-md-6">
                    <img src="assets/img/hero.png" alt="Hero Image" class="img-fluid">
                </div>
            </div>
        </section>

        <!-- Jenis Beasiswa Section -->
        <section id="jenis-beasiswa" class="py-5">
            <div class="container">
                <h2 class="text-center mb-4">Jenis Beasiswa</h2>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <img src="assets/img/akademik.png" class="card-img-top" alt="Beasiswa Akademik">
                            <div class="card-body">
                                <h5 class="card-title">Beasiswa Akademik</h5>
                                <p class="card-text">Beasiswa ini diberikan untuk mahasiswa yang memiliki prestasi akademik luar biasa. Diperuntukkan bagi mereka yang aktif dalam kegiatan riset, lomba akademik, atau memiliki IPK tinggi.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <img src="assets/img/non_akademik.png" class="card-img-top" alt="Beasiswa Non-Akademik">
                            <div class="card-body">
                                <h5 class="card-title">Beasiswa Non-Akademik</h5>
                                <p class="card-text">Beasiswa ini ditujukan bagi mahasiswa yang berprestasi dalam bidang non-akademik seperti olahraga, seni, hingga pencapaian sebagai content creator atau kegiatan sosial.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <?php include 'footer.php'; ?>


    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>
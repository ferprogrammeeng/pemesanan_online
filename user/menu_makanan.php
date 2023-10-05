<?php
// Mulai atau lanjutkan session
session_start();

// Periksa apakah pengguna telah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect ke halaman login jika belum login
    exit();
}

// Jika pengguna sudah login, Anda dapat melanjutkan dengan menampilkan halaman seperti biasa

// Tambahkan kode yang Anda inginkan untuk menampilkan menu makanan di sini
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/index.css">
    <link rel="stylesheet" type="text/css" href="../css/fontawesome/css/all.min.css">

    <title>Bakso Mataram</title>
</head>

<body>
    <!-- Jumbotron -->
    <div class="jumbotron jumbotron-fluid text-center">
        <div class="container">
            <h1 class="display-4"><span class="font-weight-bold">BAKSO MATARAM</span></h1>
            <hr>
            <p class="lead font-weight-bold">_Pesan Menu Sesuai Keinginanmu_<br>
                "Enjoy Your Meal"</p>
        </div>
    </div>
    <!-- Akhir Jumbotron -->

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg  bg-dark">
        <div class="container">
            <a class="navbar-brand text-white" href="user.php"><strong>Bakso Mataram<sup>2001</sup></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link mr-4" href="index.php">HOME</a>
                    </li>
                    <li class="nav-item">
                    <li class="nav-item">
                        <a class="nav-link mr-4" href="menu_makanan.php">DAFTAR MAKANAN</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mr-4" href="menu_minuman.php">DAFTAR MINUMAN</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mr-4" href="pesanan_saya.php">PESANAN ANDA</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mr-4" href="logout.php">LOGOUT</a> <!-- Tambahkan link ke logout.php -->
                    </li>

                </ul>
            </div>
        </div>
    </nav>
    <!-- Akhir Navbar -->

    <div class="container">
        <section id="menu-makanan" class="mt-5">
            <h1>Menu Makanan</h1>
            <div class="row">
                <?php
                // Sambungkan ke database (ganti dengan konfigurasi database Anda)
                $koneksi = new mysqli("localhost", "root", "ilkom123", "project_online");

                // Periksa koneksi
                if ($koneksi->connect_error) {
                    die("Koneksi gagal: " . $koneksi->connect_error);
                }

                // Query untuk mengambil data menu makanan dari database
                $query = "SELECT * FROM menu_makanan";
                $result = $koneksi->query($query);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                ?>

                        <div class="col-md-4 mt-4">
                            <div class="card brder-dark shadow">
                                <img src="../upload/<?= $row['gambar'] ?>" class="card-img-top" alt="...">
                                <div class="card-body shadow">
                                    <h5 class="card-title font-weight-bold"><?php echo $row['makanan'] ?></h5>
                                    <label class="card-text harga"><strong>Rp.</strong> <?php echo $row['harga']; ?></label><br>
                                    <p style="color:red">Sisa Stok : <?php echo $row['sisa_stok']; ?></p>
                                    <a href="detail_produk_makanan.php?id=<?php echo $row['id_menu_makanan']; ?>" class="btn btn-success btn-sm btn-block ">BELI</a>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "Tidak ada menu makanan.";
                }
                ?>
            </div>
        </section>
    </div>
    <!-- Awal Footer -->
    <hr class="footer">
    <div class="container">
        <div class="row footer-body">
            <div class="col-md-6">
                <div class="copyright">
                    <strong>Copyright</strong> <i class="far fa-copyright"></i> 2023 - Kelompok Web Bakso Mataram</p>
                </div>
            </div>

            <div class="col-md-6 d-flex justify-content-end">
                <div class="icon-contact">
                    <label class="font-weight-bold">Follow Us </label>
                    <a href="#"><img src="../img/images/icon/ig.png" class="mr-3 ml-4" data-toggle="tooltip" title="Instagram"></a>
                    <a href="#"><img src="../img/images/icon/fb.png" class="mr-3" data-toggle="tooltip" title="Facebook"></a>
                    <a href="#"><img src="../img/images/icon/tt.png" class="" data-toggle="tooltip" title="Tik tok"></a>
                </div>
            </div>
        </div>
    </div>
    <!-- Akhir Footer -->
    <script>
    $(document).ready(function() {
        $('#menu-makanan').DataTable({
            "pagingType": "full_numbers", // Menampilkan tombol First, Last, Next, Previous
            "lengthMenu": [10, 25, 50, 100], // Menampilkan opsi "Show entries" dengan jumlah tertentu
            "pageLength": 10, // Jumlah default entri yang ditampilkan
            "language": {
                "lengthMenu": "Show _MENU_ entries",
                "search": "Search:",
            },
        });
    });
</script>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>
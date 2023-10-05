<?php
// Inisialisasi variabel dengan nilai default
$meja = "Meja Default";
$waktu_pemesanan = "Waktu Pemesanan Default";
$max_waktu = "Maksimum Waktu Default";
$metode_pembayaran = "Metode Pembayaran Default";

session_start();

// Sambungkan ke database (ganti dengan konfigurasi database Anda)
$koneksi = new mysqli("localhost", "root", "ilkom123", "project_online");

// Periksa koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Query untuk mengambil detail pesanan dan total biaya
$query = "SELECT dp.id_detail_pesanan, 
                 CASE WHEN dp.id_menu_makanan IS NOT NULL THEN mm.makanan
                      WHEN dp.id_menu_minuman IS NOT NULL THEN mn.minuman
                 END AS nama_menu, 
                 dp.jumlah, 
                 dp.total_harga 
          FROM detail_pesanan dp
          LEFT JOIN menu_makanan mm ON dp.id_menu_makanan = mm.id_menu_makanan
          LEFT JOIN menu_minuman mn ON dp.id_menu_minuman = mn.id_menu_minuman";

// Eksekusi query
$result = $koneksi->query($query);


// Menghitung total biaya pesanan
$total_biaya = 0;


// Mengambil waktu pemesanan secara otomatis
$waktu_pemesanan = date("Y-m-d H:i:s");

// Menghitung waktu maksimum (1 jam 30 menit tambahan)
$max_waktu = date("Y-m-d H:i:s", strtotime($waktu_pemesanan) + 90 * 60); // 90 menit = 90 * 60 detik
// ...



// ...

if (isset($_POST['konfirmasi_pembayaran'])) {
    // Periksa apakah pesanan sudah dikonfirmasi sebelumnya
    $queryCekKonfirmasi = "SELECT id_detail_pesanan FROM detail_pesanan WHERE id_detail_pesanan = ? AND status_pesanan = 'Sudah Dikonfirmasi'";
    $stmt = $koneksi->prepare($queryCekKonfirmasi);
    $stmt->bind_param("i", $_SESSION['id_detail_pesanan']);
    $stmt->execute();
    $resultCekKonfirmasi = $stmt->get_result();

    if ($resultCekKonfirmasi->num_rows === 0) {
        // Pesanan belum pernah dikonfirmasi sebelumnya

        $meja = $_POST['meja'];
        $metode_pembayaran = $_POST['metode_pembayaran'];
        // Ambil data lainnya sesuai dengan kebutuhan

        // Simpan data ke dalam sesi
        $_SESSION['meja'] = $meja;
        $_SESSION['waktu_pemesanan'] = $waktu_pemesanan;
        $_SESSION['max_waktu'] = $max_waktu;
        $_SESSION['metode_pembayaran'] = $metode_pembayaran;

        // Redirect ke halaman pesanan_saya.php
        header("Location: pesanan_saya.php");
        exit(); // Pastikan untuk menghentikan eksekusi skrip setelah melakukan redirect
    }
}



// ...


// ... (Kode selanjutnya)


// ...

// Tutup koneksi ke database
$koneksi->close();
?>



<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https:stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/index.css">
    <link rel="stylesheet" type="text/css" href="../css/fontawesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">

    <title>Bakso Mataram</title>
    <style>
        /* Gaya untuk pop-up */
        .popup {
            background-color: white;
            width: 400px;
            padding: 20px;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 1px solid #ccc;
            box-shadow: 0px 0px 10px #888;
            z-index: 9999;
        }

        /* Gaya untuk judul struk */
        .struk-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        /* Gaya untuk detail pesanan */
        .detail-pesanan {
            font-size: 14px;
            margin-bottom: 10px;
        }

        /* Gaya untuk total biaya */
        .total-biaya {
            font-size: 16px;
            font-weight: bold;
        }

        /* Gaya untuk tombol cetak */
        .btn-cetak {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 20px;
        }

        .button-div {
            margin-top: 5px;
        }

        /* Gaya tombol Cetak */
        .btn-cetak {
            margin-right: 5px;
            /* Memberi jarak 5px dari tombol Tutup */
        }

        /* Gaya untuk tombol cetak saat dihover */
        .btn-cetak:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <!-- Jumbotron -->
    <div class="jumbotron jumbotron-fluid text-center">
        <div class="container">
            <h1 class="display-4"><span class="font-weight-bold">BAKSO MATARAM</span></h1>
            <hr>
            <p class="lead font-weight-bold">_Pesan Menu Sesuai Keinginanmu_<br> "Enjoy Your Meal"</p>
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
                        <a class="nav-link mr-4" href="menu_makanan.php">DAFTAR MAKANAN</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mr-4" href="menu_minuman.php">DAFTAR MINUMAN</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mr-4" href="pesanan_saya.php">PESANAN ANDA</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mr-4" href="logout.php">LOGOUT</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Akhir Navbar -->

    <!-- Menu -->
    <div class="container">
        <div class="judul-pesanan mt-5">
            <h3 class="text-center font-weight-bold">KERANJANG ANDA</h3>
            <?php
            // Memeriksa apakah ada pesanan yang telah ditambahkan ke keranjang
            if ($result->num_rows === 0) {
                echo '<div class="alert alert-warning">Anda belum melakukan pemesanan. Silakan melakukan pemesanan.</div>';
            } else {
                // Menampilkan daftar pesanan jika ada
                echo '<div class="alert alert-success">Pesanan Anda berhasil ditambahkan ke keranjang.</div>';
            }
            ?>
            <?php
            // Menampilkan daftar pesanan dalam tabel jika ada
            if ($result->num_rows > 0) {
            ?>
                <table class="table table-bordered shadow">
                    <thead class="bg-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Pesanan</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $nomor_urut = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr class='bg=secondari'>";
                            echo "<td>" . $nomor_urut++ . "</td>"; // Menampilkan nomor urut dan menambahkannya
                            echo "<td>" .
                                $row['nama_menu'] . "</td>";
                            echo "<td>" . $row['jumlah'] . "</td>";
                            echo "<td>Rp " . $row['total_harga'] . "</td>";
                            echo "<td>
                <a class='btn btn-danger' href='batalkan_pesanan.php?id=" . $row['id_detail_pesanan'] . "'>Batalkan Pesanan</a>
                </td>";
                            echo "</tr>";
                            $total_biaya += $row['total_harga'];
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4">Total Biaya: Rp <?php echo $total_biaya; ?>.000</th>
                            <br>
                        </tr>
                    </tfoot>
                </table>
            <?php
            }
            ?>
            <?php
            if ($result->num_rows > 0) {
                // Menampilkan form pembayaran online
            ?>
                <h4 class="mt-4">Metode Pembayaran</h4>
                <form method="post" action="">

                    <label for="meja">Meja:</label>
                    <select id="meja" name="meja" required>
                        <option value="">Pilih</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select><br><br>
                    <label for="metode_pembayaran">Metode Pembayaran:</label>
                    <select id="metode_pembayaran" name="metode_pembayaran" required>
                        <option value="">Pilih</option>
                        <option value="Dana">Dana</option>
                        <option value="Ovo">Ovo</option>
                        <option value="Gopay">Gopay</option>
                        <option value="Kartu Kredit">Kartu Kredit</option>
                    </select><br><br>

                    <!-- Anda dapat menambahkan elemen form lainnya sesuai kebutuhan -->

                    <input type="submit" class="btn btn-success" name="konfirmasi_pembayaran" value="Konfirmasi Pembayaran">
                </form>
            <?php
            }
            ?>
        </div>
    </div>
    <!-- Akhir Menu -->

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

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
</body>

</html>
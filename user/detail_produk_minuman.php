

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
        </div>
       </div> 
      </nav>
  <!-- Akhir Navbar -->

  <!-- Menu -->
  <div class="container">
        <?php
        // Sambungkan ke database (ganti dengan konfigurasi database Anda)
        $koneksi = new mysqli("localhost", "root", "ilkom123", "project_online");

        // Periksa koneksi
        if ($koneksi->connect_error) {
            die("Koneksi gagal: " . $koneksi->connect_error);
        }

        // Tangkap data dari URL (ID produk)
        $product_id = $_GET['id']; // Anda perlu menyesuaikan bagaimana Anda mengirim ID produk dalam URL

        // Query untuk mengambil detail produk berdasarkan ID
        $query = "SELECT * FROM menu_minuman WHERE id_menu_minuman = $product_id";
        $result = $koneksi->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $nama_menu = $row['minuman']; // Ganti 'nama_menu' dengan 'makanan'
        ?>
            <h1 class="mt-5">Detail Produk Makanan</h1>
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="card shadow">
                        <img src="../img/minuman1.jpg" class="card-img-top" alt="<?php echo $nama_menu; ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $nama_menu; ?></h5>
                            <p class="card-text">Deskripsi Produk.</p>
                            <p class="card-text">Harga: Rp <?php echo $row['harga']; ?></p>
                            <form method="POST" action="proses_pembelian_minuman.php">
                                <input type="hidden" name="product_id" value="<?php echo $row['id_menu_minuman']; ?>">
                                <!-- Tambahkan input tersembunyi untuk jenis produk -->
                                <input type="hidden" name="product_type" value="makanan">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-outline-secondary" onclick="decrement()">-</button>
                                    </div>
                                    <input type="number" id="quantity" name="quantity" class="form-control input-number" value="1" min="1" max="<?php echo $row['sisa_stok']; ?>">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" onclick="increment()">+</button>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label>Total Harga:</label>
                                    <span id="total-harga">Rp <?php echo $row['harga']; ?></span>
                                </div>
                                <button type="submit" name="submit" class="btn btn-success mt-3">Tambah Keranjang</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        } else {
            echo "Produk tidak ditemukan.";
        }
        ?>
    </div>
    <script>
        var hargaPerItem = <?php echo $row['harga']; ?>;
        var quantityField = document.getElementById('quantity');
        var totalHargaField = document.getElementById('total-harga');

        function updateTotalHarga() {
            var currentValue = parseInt(quantityField.value);
            var totalHarga = hargaPerItem * currentValue;
            totalHargaField.textContent = 'Rp' + totalHarga.toLocaleString() + '.000'; // Format angka dengan tanda ribuan
        }

        function increment() {
            var currentValue = parseInt(quantityField.value);
            var maxValue = parseInt(quantityField.getAttribute('max'));

            if (currentValue < maxValue) {
                quantityField.value = currentValue + 1;
                updateTotalHarga();
            }
        }

        function decrement() {
            var currentValue = parseInt(quantityField.value);
            var minValue = parseInt(quantityField.getAttribute('min'));

            if (currentValue > minValue) {
                quantityField.value = currentValue - 1;
                updateTotalHarga();
            }
        }

        // Memastikan total harga awal diinisialisasi dengan harga satu item
        updateTotalHarga();
    </script>
    
    
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
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
      } );
    </script>
  </body>
</html>

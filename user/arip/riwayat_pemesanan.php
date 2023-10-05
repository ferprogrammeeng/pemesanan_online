<?php
// Di bagian awal kode, sebelum session_start()
$meja = ""; // Meja
$waktu_pemesanan = ""; // Waktu Pemesanan
$max_waktu = ""; // Maksimum Waktu

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
$result = $koneksi->query($query);

// Menghitung total biaya pesanan
$total_biaya = 0;

// Generate nomor meja secara acak antara 1 dan 10 (sesuaikan dengan jumlah meja Anda)
$meja = rand(1, 20);

// Mengambil waktu pemesanan secara otomatis
$waktu_pemesanan = date("Y-m-d H:i:s");
if (isset($_POST['konfirmasi_pembayaran'])) {
    $meja = $_POST['meja'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    // Ambil data lainnya sesuai dengan kebutuhan

    // Sekarang Anda memiliki data yang diinputkan oleh pengguna
    // Gunakan data ini untuk menampilkan riwayat pemesanan atau melakukan operasi lainnya.

    // Contoh: Menampilkan data yang diinputkan oleh pengguna
    echo "Nomor Meja: $meja <br>";
    echo "Metode Pembayaran: $metode_pembayaran <br>";
    // Tambahkan kode lainnya sesuai kebutuhan
}
// Menghitung waktu maksimum (1 jam 30 menit tambahan)
$max_waktu = date("Y-m-d H:i:s", strtotime($waktu_pemesanan) + 90 * 60); // 90 menit = 90 * 60 detik
// Mengambil waktu saat ini
$waktu_sekarang = date("Y-m-d H:i:s");

if ($waktu_sekarang > $max_waktu) {
    // Meja tidak dapat ditempati lagi karena melewati waktu maksimum
    echo "Maaf, meja ini tidak dapat ditempati lagi karena waktu maksimum telah berakhir.";
    // Anda juga dapat mengosongkan meja atau tindakan lain sesuai kebijakan Anda
} else {
    // Meja masih dapat digunakan
    echo "";
}


if (isset($_SESSION['max_waktu'])) {
    $max_waktu = $_SESSION['max_waktu'];
}

// if (isset($_SESSION['pesan'])) {
//     echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
//           ' . $_SESSION['pesan'] . '
//           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
//             <span aria-hidden="true">&times;</span>
//           </button>
//         </div>';
//     // Hapus pesan dari session agar tidak ditampilkan lagi
//     unset($_SESSION['pesan']);
// }

// Di dalam blok PHP setelah pembayaran online berhasil dikonfirmasi

// Di dalam blok PHP setelah pembayaran online berhasil dikonfirmasi

// Selesai dengan konfirmasi, Anda dapat melakukan tindakan lain seperti mengirim email atau notifikasi.
// Setelah selesai, mungkin Anda ingin mengarahkan pengguna ke halaman terima kasih atau lainnya.

if (isset($_POST['konfirmasi_online'])) {
    // Proses konfirmasi pembayaran online di sini

    // Simpan informasi pesanan yang telah dikonfirmasi ke dalam variabel
    $meja = $_SESSION['meja']; // Ganti dengan cara Anda menyimpan informasi meja
    $waktu_pemesanan = $_SESSION['waktu_pemesanan']; // Ganti dengan cara Anda menyimpan waktu pemesanan
    $max_waktu = $_SESSION['max_waktu']; // Ganti dengan cara Anda menyimpan informasi maksimum waktu

    // Mengambil metode pembayaran dari form
    $metode_pembayaran = $_POST['metode_pembayaran'];

    // Mengambil data pesanan dari database
    $queryPesanan = "SELECT dp.jumlah, dp.total_harga, 
                         CASE WHEN dp.id_menu_makanan IS NOT NULL THEN mm.makanan
                              WHEN dp.id_menu_minuman IS NOT NULL THEN mn.minuman
                         END AS nama_menu 
                         FROM detail_pesanan dp
                         LEFT JOIN menu_makanan mm ON dp.id_menu_makanan = mm.id_menu_makanan
                         LEFT JOIN menu_minuman mn ON dp.id_menu_minuman = mn.id_menu_minuman";
    $resultPesanan = $koneksi->query($queryPesanan);

    // Menghitung total biaya dari pesanan
    $total_biaya = 0;

    // Membuat teks untuk struk pembayaran
    $strukText = "===== Struk Pembayaran =====\n";
    $strukText .= "Nomor Meja: $meja\n";
    $strukText .= "Waktu Pemesanan: $waktu_pemesanan\n";
    $strukText .= "Maksimum Waktu: $max_waktu\n";

    // Menambahkan detail pesanan ke teks struk
    $strukText .= "Pesanan:\n";
    while ($rowPesanan = $resultPesanan->fetch_assoc()) {
        $nama_menu = $rowPesanan['nama_menu'];
        $jumlah = $rowPesanan['jumlah'];
        $total_harga = $rowPesanan['total_harga'];

        // Menambahkan detail pesanan ke teks struk
        $strukText .= "- $nama_menu ($jumlah) Rp $total_harga\n";

        // Menghitung total biaya
        $total_biaya += $total_harga;
    }

    // Menambahkan total biaya ke teks struk
    $strukText .= ": Rp $total_biaya\n";
    $strukText .= "Metode Pembayaran: $metode_pembayaran\n";
    $strukText .= "==============================\n";

    // Simpan teks struk pembayaran ke file
    $namaFileStruk = "struk_pembayaran_$meja.txt";
    file_put_contents($namaFileStruk, $strukText);

    // Set status pesanan menjadi "Sudah Konfirmasi"
    $queryUpdateStatus = "UPDATE pesanan SET status = 'Sudah Konfirmasi' WHERE meja = $meja AND waktu_pemesanan = '$waktu_pemesanan'";
    $koneksi->query($queryUpdateStatus);

    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
              Pembayaran telah berhasil dikonfirmasi. Terima kasih!
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>';
} else {
}


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
                        <a class="nav-link mr-4" href="pesanan.php">PESANAN ANDA</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mr-4" href="riwayat_pemesanan.php">RIWAYAT</a>
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
            <h3 class="text-center font-weight-bold">PESANAN ANDA</h3>
            <table class="table table-bordered" hidden>
                <?php
                // ... Kode lainnya ...
                $nomor_urut = 1;
                // Memeriksa apakah ada pesanan yang telah ditambahkan ke keranjang
                if ($result->num_rows === 0) {
                    echo '<div class="alert alert-warning">Anda belum melakukan pemesanan. Silakan melakukan pemesanan.</div>';
                } else {
                    // Menampilkan daftar pesanan jika ada
                    echo '<div class="alert alert-success">Pesanan Anda berhasil ditambahkan ke keranjang.</div>';
                }

                // Menampilkan daftar pesanan dalam tabel
                ?>
                <thead>
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
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
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
            <button onclick="tampilkanDetailPesanan()">Donwload Struk</button>

            <form action="" method="post" hidden>
                <div class="form-group" hidden>
                    <input type="text" name="meja" placeholder="Nomor Meja" required hidden>
                    <input type="text" name="metode_pembayaran" placeholder="Metode Pembayaran" required hidden>
                    <!-- Tambahkan input field lainnya sesuai kebutuhan -->
                    <select class="form-control" id="metode_pembayaran" name="metode_pembayaran">
                        <option value="Dana">Dana</option>
                        <option value="OVO">OVO</option>
                        <option value="Gopay">Gopay</option>
                        <option value="Tunai">Tunai</option>
                    </select>
                </div>
                <!-- Tambahkan tombol Konfirmasi Pembayaran -->
                <button class="btn btn-primary" type="button" onclick="tampilkanDetailPesanan()">Konfirmasi Pembayaran</button>
            </form>

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

    <!-- JavaScript untuk menampilkan pop-up -->
    <script>
        function tampilkanDetailPesanan() {
            // Mengambil nilai meja, waktu pemesanan, dan max waktu dari PHP
            var meja = "<?php echo $meja; ?>";
            var waktu_pemesanan = "<?php echo $waktu_pemesanan; ?>";
            var max_waktu = "<?php echo $max_waktu; ?>";

            // Tambahkan variabel metode_pembayaran
            var metode_pembayaran = document.getElementById("metode_pembayaran").value;

            var pesananText = "<div class='struk-title'>Bakso Mataram</div>";
            pesananText += "<div class='opening-hours'>Buka Jam: 10:00 AM - 23:00 PM</div>";
            pesananText += "<div class='opening-hours'>Jl.Kaharuddin Nasution no.210, Pekanbaru</div><br>";

            // Tambahkan garis horizontal panjang
            pesananText += "<hr>";

            // Mendapatkan referensi ke tabel pesanan
            var tabel = document.querySelector("table");
            var rows = tabel.querySelectorAll("tbody tr");
            pesananText += "<div class='detail-pesanan'><h4>Detail Pesanan</h4></div>";

            pesananText += "<div class='detail-pesanan'>";
            rows.forEach(function(row) {
                var nama_menu = row.cells[1].innerText;
                var jumlah = row.cells[2].innerText;
                var harga = parseFloat(row.cells[3].innerText.replace(",", "").replace("Rp", "").trim());

                // Menampilkan jumlah dan nama_menu di sebelah kiri dengan jarak ke kiri
                pesananText += "<div class='float-left'>" + jumlah + " " + nama_menu + "</div>";

                // Menampilkan harga di sebelah kanan
                pesananText += "<div class='float-right'>Rp " + harga.toFixed(3) + "</div>";

                // Menggunakan clearfix untuk membersihkan float
                pesananText += "<div class='clearfix'></div>";
            });

            pesananText += "<div class='detail-pesanan float-left'>";
            var total_biaya = document.querySelector("tfoot th:last-child").innerText;
            pesananText += "<div class='text-left'>Total Biaya</div>";
            pesananText += "<div class='text-left'>Waktu Pemesanan</div>";
            pesananText += "<div class='text-left'>Maksimum Waktu</div>";
            pesananText += "<div class='text-left'>Meja</div>";
            pesananText += "<div class='text-left'>Pembayaran</div>";
            pesananText += "</div>";

            pesananText += "<div class='detail-pesanan float-right'>";
            pesananText += "<div class='text-right'>" + total_biaya + "</div>";
            pesananText += "<div class='text-right'>" + waktu_pemesanan + "</div>";
            pesananText += "<div class='text-right'>" + max_waktu + "</div>";
            pesananText += "<div class='text-right'>" + meja + "</div>";
            pesananText += "<div class='text-right'>" + metode_pembayaran + "</div>";
            pesananText += "</div> <br> ";

            // Tambahkan garis horizontal panjang
            pesananText += "==============================<br>";
            pesananText += "<div class='opening-hours'>Terima kasih Anda telah melakukan konfirmasi pembayaran secara online, Silahkan datang ke kasir dan membawa struk pemesanan.</div> ";
            pesananText += "<hr>";
            pesananText += "<div class='total-biaya text-center' style='font-weight:normal'><i>Waktu Pemesanan online maksimal 1 jam 30 menit terhitung setelah pembayaran online. Meja yang dipilih tidak bisa ditempati lagi. Tetapi pesanan tetap bisa diamnil</i></div>";

            // Buat div untuk pop-up
            var popupDiv = document.createElement("div");
            popupDiv.classList.add("popup");
            popupDiv.innerHTML = pesananText;

            // Tambahkan class 'text-center' untuk mengatur teks menjadi pusat
            popupDiv.style.textAlign = "center";

            // Buat div untuk tombol
            var buttonDiv = document.createElement("div");
            buttonDiv.classList.add("button-div");

            // Tambahkan tombol Cetak
            var cetakButton = document.createElement("button");
            cetakButton.classList.add("btn-cetak");
            cetakButton.textContent = "Cetak";
            cetakButton.addEventListener("click", function() {
                // Buka jendela cetak popup
                var popupWindow = window.open("", "_blank");

                // Isi jendela cetak popup dengan isi popup alert
                popupWindow.document.open();
                popupWindow.document.write("<html><head><title>Struk Pemesanan</title></head><body>");
                popupWindow.document.write("<div class='popup'>" + pesananText + "</div>");
                popupWindow.document.write("</body></html>");
                popupWindow.document.close();

                // Cetak jendela cetak popup
                popupWindow.print();
            });

            // Tambahkan tombol Tutup (Close)
            var closeButton = document.createElement("button");
            closeButton.classList.add("btn-close");
            closeButton.textContent = "Tutup";
            closeButton.addEventListener("click", function() {
                // Hapus pop-up dari dokumen
                document.body.removeChild(popupDiv);
            });

            buttonDiv.appendChild(cetakButton);
            buttonDiv.appendChild(closeButton);

            popupDiv.appendChild(buttonDiv);

            // Tampilkan pop-up
            document.body.appendChild(popupDiv);
        }
    </script>
   
</body>

</html>
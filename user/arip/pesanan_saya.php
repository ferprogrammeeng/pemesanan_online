<?php
// Memulai sesi
session_start();

// Inisialisasi variabel dengan nilai default
$meja = "Meja Default";
$waktu_pemesanan = "Waktu Pemesanan Default";
$max_waktu = "Maksimum Waktu Default";
$metode_pembayaran = "Metode Pembayaran Default";

// Memeriksa apakah variabel sesi sudah diset
if (isset($_SESSION['meja'])) {
    $meja = $_SESSION['meja'];
}
if (isset($_SESSION['waktu_pemesanan'])) {
    $waktu_pemesanan = $_SESSION['waktu_pemesanan'];
}
if (isset($_SESSION['max_waktu'])) {
    $max_waktu = $_SESSION['max_waktu'];
}
if (isset($_SESSION['metode_pembayaran'])) {
    $metode_pembayaran = $_SESSION['metode_pembayaran'];
}

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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pesanan Anda</title>
    <style>
        table {
            width: 50%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Detail Pesanan Anda</h1>

    <!-- Menampilkan informasi pesanan -->
    <table>
        <tr><th>Informasi</th><th>Nilai</th></tr>
        <tr><td>Meja</td><td><?php echo htmlspecialchars($meja); ?></td></tr>
        <tr><td>Waktu Pemesanan</td><td><?php echo htmlspecialchars($waktu_pemesanan); ?></td></tr>
        <tr><td>Maksimum Waktu</td><td><?php echo htmlspecialchars($max_waktu); ?></td></tr>
        <tr><td>Metode Pembayaran</td><td><?php echo htmlspecialchars($metode_pembayaran); ?></td></tr>
    </table>

    <!-- Menampilkan detail pesanan -->
    <h2>Detail Pesanan</h2>
    <table>
        <tr><th>Nama Menu</th><th>Jumlah</th><th>Total Harga</th></tr>
        <?php
        while ($row = $result->fetch_assoc()) {
            $nama_menu = htmlspecialchars($row['nama_menu']);
            $jumlah = htmlspecialchars($row['jumlah']);
            $total_harga = htmlspecialchars($row['total_harga']);
            echo "<tr><td>$nama_menu</td><td>$jumlah</td><td>$total_harga</td></tr>";
        }
        ?>
    </table>

    <!-- Tombol kembali ke halaman pesanan.php -->
    <a href="pesanan.php">Kembali</a>

</body>
</html>

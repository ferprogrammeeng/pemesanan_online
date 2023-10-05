<?php
session_start();

// Sambungkan ke database (ganti dengan konfigurasi database Anda)
$koneksi = new mysqli("localhost", "root", "ilkom123", "project_online");

// Periksa koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

if (isset($_GET['id'])) {
    $id_detail_pesanan = $_GET['id'];

    // Ambil data pesanan berdasarkan ID
    $query = "SELECT dp.id_detail_pesanan, 
                     CASE WHEN dp.id_menu_makanan IS NOT NULL THEN mm.makanan
                          WHEN dp.id_menu_minuman IS NOT NULL THEN mn.minuman
                     END AS nama_menu, 
                     dp.jumlah, 
                     dp.total_harga 
              FROM detail_pesanan dp
              LEFT JOIN menu_makanan mm ON dp.id_menu_makanan = mm.id_menu_makanan
              LEFT JOIN menu_minuman mn ON dp.id_menu_minuman = mn.id_menu_minuman
              WHERE dp.id_detail_pesanan = '$id_detail_pesanan'";
    $result = $koneksi->query($query);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Menampilkan formulir untuk mengubah pesanan
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <!-- Tambahkan bagian head sesuai dengan kebutuhan Anda -->
            <title>Ubah Pesanan</title>
        </head>
        <body>
            <h1>Ubah Pesanan</h1>
            <form action="proses_ubah_pesanan.php" method="post">
                <input type="hidden" name="id_detail_pesanan" value="<?php echo $row['id_detail_pesanan']; ?>">
                <label for="jumlah">Jumlah Pesanan:</label>
                <input type="number" id="jumlah" name="jumlah" value="<?php echo $row['jumlah']; ?>" min="1">
                <button type="submit">Simpan Perubahan</button>
            </form>

            <!-- Tambahkan tombol untuk membatalkan pesanan -->
            <form action="proses_hapus_pesanan.php" method="post">
                <input type="hidden" name="id_detail_pesanan" value="<?php echo $row['id_detail_pesanan']; ?>">
                <button type="submit">Batalkan Pesanan</button>
            </form>
        </body>
        </html>

        <?php
    } else {
        echo "Pesanan tidak ditemukan.";
    }
}

$koneksi->close();
?>

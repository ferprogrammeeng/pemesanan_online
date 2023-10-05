<?php
// Sambungkan ke database (ganti dengan konfigurasi database Anda)
$koneksi = new mysqli("localhost", "root", "ilkom123", "project_online");

// Periksa koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Tangkap data dari POST untuk pembelian minuman
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

// Query untuk mengambil harga minuman
$query = "SELECT harga FROM menu_minuman WHERE id_menu_minuman = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$stmt->bind_result($harga_minuman);
$stmt->fetch();
$stmt->close();

// Hitung total harga minuman
$total_harga_minuman = $harga_minuman * $quantity;

// Query untuk membuat pesanan minuman
$query = "INSERT INTO pesanan (id_pelanggan, total_biaya, pembayaran) VALUES (?, ?, ?)";
$stmt = $koneksi->prepare($query);
$id_pelanggan = 1; // Ganti dengan ID pelanggan yang sesuai
$total_biaya_minuman = $total_harga_minuman; // Total biaya sesuai dengan harga minuman yang dihitung
$pembayaran = 'tunai'; // Ganti sesuai dengan metode pembayaran yang sesuai
$stmt->bind_param("ids", $id_pelanggan, $total_biaya_minuman, $pembayaran);
$stmt->execute();
$id_pesanan_minuman = $stmt->insert_id; // Mendapatkan ID pesanan yang baru dibuat
$stmt->close();

// Masukkan pesanan minuman ke dalam tabel detail_pesanan
$query = "INSERT INTO detail_pesanan (id_pesanan, id_menu_minuman, jumlah, total_harga, gambar) VALUES (?, ?, ?, ?,?)";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("iiids", $id_pesanan_minuman, $product_id, $quantity, $total_harga_minuman,$gambar);
$gambar = ""; // Berikan nilai default atau null untuk kolom 'gambar'
$stmt->execute();
$stmt->close();

// Setelah pesanan berhasil dimasukkan, Anda bisa mengarahkan pengguna ke halaman pesanan Anda
header('Location: pesanan.php');
exit;
?>

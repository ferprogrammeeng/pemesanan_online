<!-- hapus_menu.php -->
<?php
session_start();


// Sambungkan ke database
$koneksi = new mysqli("localhost", "root", "ilkom123", "project_online");

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Periksa apakah ada parameter id_menu pada URL
if (isset($_GET['id_menu'])) {
    $id_menu = $_GET['id_menu'];
// Buat query untuk menghapus menu makanan berdasarkan id_menu
$deleteQuery = "DELETE FROM menu_makanan WHERE id_menu_makanan = $id_menu";

if ($koneksi->query($deleteQuery) === TRUE) {
    // Jika berhasil menghapus, alihkan ke halaman menu_makanan.php
    header("Location: menu_makanan.php");
    exit(); // Pastikan keluar dari skrip setelah pengalihan halaman
} else {
    echo "Error: " . $deleteQuery . "<br>" . $koneksi->error;
}

}
?>

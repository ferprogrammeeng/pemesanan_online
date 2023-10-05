<?php
session_start();

// Sambungkan ke database (ganti dengan konfigurasi database Anda)
$koneksi = new mysqli("localhost", "root", "ilkom123", "project_online");

// Periksa koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_detail_pesanan = $_POST['id_detail_pesanan'];

    // Hapus pesanan berdasarkan ID
    $query = "DELETE FROM detail_pesanan WHERE id_detail_pesanan = '$id_detail_pesanan'";
    if ($koneksi->query($query) === TRUE) {
        // Redirect kembali ke halaman pesanan.php setelah penghapusan
        $_SESSION['pesan'] = "Pesanan berhasil dihapus.";
        header("Location: pesanan.php");
        exit;
    } else {
        echo "Error: " . $query . "<br>" . $koneksi->error;
    }
}

$koneksi->close();
?>

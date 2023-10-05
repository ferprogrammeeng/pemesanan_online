<?php
session_start();

// Sambungkan ke database (ganti dengan konfigurasi database Anda)
$koneksi = new mysqli("localhost", "root", "ilkom123", "project_online");

// Periksa koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Periksa apakah parameter 'id' ada dalam URL
if (isset($_GET['id'])) {
    // Ambil ID pesanan dari URL
    $id_pesanan = $_GET['id'];

    // Query untuk menghapus pesanan berdasarkan ID
    $query = "DELETE FROM detail_pesanan WHERE id_detail_pesanan = $id_pesanan";

    if ($koneksi->query($query) === TRUE) {
        // Pesanan berhasil dihapus, simpan pesan dalam session dan arahkan ke halaman pesanan.php
        $_SESSION['pesan'] = "Pesanan berhasil dibatalkan.";
        header("Location: pesanan.php");
    } else {
        // Gagal menghapus pesanan, simpan pesan dalam session dan arahkan ke halaman pesanan.php
        $_SESSION['pesan'] = "Error: " . $query . "\n" . $koneksi->error;
        header("Location: pesanan.php");
    }
} else {
    // Jika parameter 'id' tidak ada dalam URL, simpan pesan dalam session dan arahkan ke halaman pesanan.php
    $_SESSION['pesan'] = "ID pesanan tidak ditemukan.";
    header("Location: pesanan.php");
}

// Tutup koneksi database
$koneksi->close();
?>

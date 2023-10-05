<?php
// Pastikan sesi sudah dimulai
session_start();

// Periksa apakah pengguna telah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Ganti "login.php" dengan halaman login Anda
    exit();
}

// Periksa apakah parameter id telah diterima
if (isset($_GET['id'])) {
    // Ambil ID pesanan dari parameter URL
    $id_pesanan = $_GET['id'];

    // Sambungkan ke database (ganti dengan konfigurasi database Anda)
    $koneksi = new mysqli("localhost", "root", "ilkom123", "project_online");

    // Periksa koneksi
    if ($koneksi->connect_error) {
        die("Koneksi gagal: " . $koneksi->connect_error);
    }

    // Query untuk menghapus data pesanan berdasarkan ID
    $queryHapusPesanan = "DELETE FROM pesanan WHERE id_pesanan = ?";
    $stmt = $koneksi->prepare($queryHapusPesanan);
    $stmt->bind_param("i", $id_pesanan);

    if ($stmt->execute()) {
        // Jika penghapusan berhasil, Anda dapat melakukan tindakan lain, seperti menghapus detail pesanan terkait
        // Contoh:
        // $queryHapusDetailPesanan = "DELETE FROM detail_pesanan WHERE id_pesanan = ?";
        // $stmtDetail = $koneksi->prepare($queryHapusDetailPesanan);
        // $stmtDetail->bind_param("i", $id_pesanan);
        // $stmtDetail->execute();

        header('Location: pesanan.php');
        exit();
    } else {
        echo "Gagal menghapus data pesanan: " . $koneksi->error;
    }

    $koneksi->close();
} else {
    // Jika parameter id tidak diterima
    echo "Parameter ID pesanan tidak valid.";
}
?>

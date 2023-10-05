<!DOCTYPE html>
<html>
<head>
    <title>Registrasi</title>
</head>
<body>
    <?php
    // Inisialisasi variabel error
    $namaErr = $asalErr = $emailErr = $usernameErr = $passwordErr = "";
    
    // Inisialisasi variabel data pengguna
    $nama_lengkap = $asal = $email = $username = $password = "";

    // Koneksi ke database MySQL
    $koneksi = new mysqli("localhost", "root", "ilkom123", "project_online");

    // Cek koneksi
    if($koneksi->connect_error) {
        die("Koneksi gagal: " . $koneksi->connect_error);
    }

    // Fungsi untuk membersihkan input
    function bersihkanInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Penanganan saat formulir disubmit
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validasi nama lengkap
        if (empty($_POST["nama_lengkap"])) {
            $namaErr = "Nama lengkap diperlukan";
        } else {
            $nama_lengkap = bersihkanInput($_POST["nama_lengkap"]);
        }

        // Validasi asal
        if (empty($_POST["asal"])) {
            $asalErr = "Asal diperlukan";
        } else {
            $asal = bersihkanInput($_POST["asal"]);
        }

        // Validasi email
        if (empty($_POST["email"])) {
            $emailErr = "Email diperlukan";
        } else {
            $email = bersihkanInput($_POST["email"]);
            // Periksa apakah alamat email valid
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Format email tidak valid";
            }
        }

        // Validasi username
        if (empty($_POST["username"])) {
            $usernameErr = "Username diperlukan";
        } else {
            $username = bersihkanInput($_POST["username"]);
            // Periksa apakah username sudah ada dalam database
            $sql_cek_username = "SELECT id_pelanggan FROM registrasi WHERE username = ?";
            $stmt_cek_username = $koneksi->prepare($sql_cek_username);
            $stmt_cek_username->bind_param("s", $username);
            $stmt_cek_username->execute();
            $stmt_cek_username->store_result();
            if ($stmt_cek_username->num_rows > 0) {
                $usernameErr = "Username sudah digunakan. Silakan pilih username lain.";
            }
            $stmt_cek_username->close();
        }

        // Validasi kata sandi
        if (empty($_POST["password"])) {
            $passwordErr = "Kata sandi diperlukan";
        } else {
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hashing kata sandi
        }

       // Jika tidak ada kesalahan validasi, masukkan data ke tabel registrasi
if (empty($namaErr) && empty($asalErr) && empty($emailErr) && empty($usernameErr) && empty($passwordErr)) {
    $sql = "INSERT INTO registrasi (nama_lengkap, asal, email, username, kata_sandi) VALUES (?, ?, ?, ?, ?)";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("sssss", $nama_lengkap, $asal, $email, $username, $password);
    
    if ($stmt->execute()) {
        echo "Registrasi berhasil. Silakan <a href='login.php'>login</a>.";
        // Redirect ke halaman login.php setelah registrasi berhasil
        header("Location: login.php");
        exit; // Pastikan untuk keluar setelah melakukan redirect
    } else {
        echo "Error: " . $sql . "<br>" . $koneksi->error;
    }
    
    $stmt->close();
}

        
        $koneksi->close();
    }
    ?>

    <h2>Form Registrasi</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Nama Lengkap: <input type="text" name="nama_lengkap" value="<?php echo $nama_lengkap; ?>">
        <span class="error">* <?php echo $namaErr; ?></span>
        <br><br>
        Asal: <input type="text" name="asal" value="<?php echo $asal; ?>">
        <span class="error">* <?php echo $asalErr; ?></span>
        <br><br>
        Email: <input type="text" name="email" value="<?php echo $email; ?>">
        <span class="error">* <?php echo $emailErr; ?></span>
        <br><br>
        Username: <input type="text" name="username" value="<?php echo $username; ?>">
        <span class="error">* <?php echo $usernameErr; ?></span>
        <br><br>
        Kata Sandi: <input type="password" name="password">
        <span class="error">* <?php echo $passwordErr; ?></span>
        <br><br>
        <input type="submit" name="submit" value="Registrasi">
    </form>
</body>
</html>

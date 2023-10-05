<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="">
    <link rel="stylesheet" type="text/css" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">


    <title>Halaman Registrasi</title>
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

 <!-- Form Registrasi -->
  <div class="container">
    <h3 class="text-center mt-3 mb-5">HALAMAN REGISTRASI</h3>
    <div class="card p-5 mb-5">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="user">Username</label>
          <input type="text" class="form-control" id="user" name="username" placeholder="Masukan Username">
          <span class="error"> <?php echo $usernameErr; ?></span>
        </div>
        <div class="form-group col-md-6">
          <label for="pass">Password</label>
          <input type="password" class="form-control" id="pass" name="password" placeholder="Masukan Password">
          <span class="error"> <?php echo $passwordErr; ?></span>
        </div>
      </div>
      <div class="form-group">
        <label for="nama">Nama Lengkap</label>
        <input type="text" class="form-control" id="nama" name="nama_lengkap" placeholder="Masukan Nama Lengkap">
        <span class="error"><?php echo $namaErr; ?></span>
        <br><br>
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Masukan Email Lengkap">
        <span class="error"> <?php echo $emailErr; ?></span>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="rumah">Alamat</label>
          <input type="text" class="form-control" id="rumah" name="asal" placeholder="Masukan Alamat">
          <span class="error"> <?php echo $passwordErr; ?></span>
        </div>
      </div>    
      <div class="mb-3" >
          <small><a href="login.php" class="text-dark">Sudah Punya Akun ? Login Sekarang !</a></small>
        </div> 
      <button type="register" class="btn btn-primary">Register</button>
    </form>
  </div>
  </div>
  <!-- Akhir Form Registrasi -->

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery.js"></script>
  </body>
</html>
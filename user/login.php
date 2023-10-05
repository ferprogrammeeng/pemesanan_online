
<!doctype html>
<html lang="en">
  <head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <!-- <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> -->


    <title>Halaman Login</title>
    <style>
        .container {
	width: 30%;
	margin-top: 10%;
	box-shadow: 0 3px 20px rgba(0,0,0,0.4);
	padding: 40px;
	border-radius: 10px;
}
 
button {
	width: 49%;
}

    </style>
  </head>
  <body>
  <!-- Form Login -->
    <div class="container">
      <h4 class="text-center">FORM LOGIN</h4>
      <hr>
      <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
          <label for="exampleInputEmail1">Username</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-user"></i></div>
              </div>
              <input type="text" class="form-control" placeholder="Masukkan Username" name="username" >
            </div>
        </div>
        <div class="form-group">
          <label for="exampleInputPassword1">Password</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-unlock-alt"></i></div>
              </div>
              <input type="password" class="form-control" placeholder="Masukkan Password" name="password">
          </div>
        </div>
        <div class="mb-3" >
          <small><a href="registrasi.php" class="text-dark">Belum Punya Akun ? Buat Akun Anda !</a></small>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">LOGIN</button>
        <button type="reset" name="reset" class="btn btn-danger">RESET</button>
      </form>
  <!-- Akhir Form Login -->

  <!-- Eksekusi Form Login -->
  <?php
        // Inisialisasi variabel error
        $usernameErr = $passwordErr = "";

        // Inisialisasi variabel data pengguna
        $username = $password = "";

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
            // Validasi username
            if (empty($_POST["username"])) {
                $usernameErr = "Username diperlukan";
            } else {
                $username = bersihkanInput($_POST["username"]);
            }

            // Validasi kata sandi
            if (empty($_POST["password"])) {
                $passwordErr = "Kata sandi diperlukan";
            } else {
                $password = bersihkanInput($_POST["password"]);
            }

            // Jika tidak ada kesalahan validasi, verifikasi login
            if (empty($usernameErr) && empty($passwordErr)) {
                $sql = "SELECT id_pelanggan, kata_sandi FROM registrasi WHERE username = ?";
                $stmt = $koneksi->prepare($sql);
                $stmt->bind_param("s", $username);

                if ($stmt->execute()) {
                    $result = $stmt->get_result();
                    if ($result->num_rows == 1) {
                        $row = $result->fetch_assoc();
                        if (password_verify($password, $row['kata_sandi'])) {
                            // Login berhasil
                            session_start();
                            $_SESSION['username'] = $username;
                            header("Location: index.php"); // Ganti dengan halaman utama setelah login
                        } else {
                            echo "Login gagal. Periksa kembali username dan kata sandi Anda.";
                        }
                    } else {
                        echo "Login gagal. Periksa kembali username dan kata sandi Anda.";
                    }
                } else {
                    echo "Error: " . $sql . "<br>" . $koneksi->error;
                }

                $stmt->close();
            }

            $koneksi->close();
        }
        ?>
    </div>
  <!-- Akhir Eksekusi Form Login -->







    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery.js"></script>
  </body>
</html>
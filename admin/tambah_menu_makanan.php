<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

   
   <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/index.css">
    <link rel="stylesheet" type="text/css" href="../css/fontawesome/css/all.min.css">

    <title>Form Tambah Menu</title>
</head>
<body>
    <!-- Form Tambah Menu -->
    <div class="container">
        <h3 class="text-center mt-3 mb-5">SILAHKAN TAMBAH MENU</h3>
        <div class="card p-5 mb-5">
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="makanan">Menu Makanan</label>
                    <input type="text" class="form-control" id="makanan" name="makanan" required>
                </div>
                <div class="form-group">
                    <label for="sisa_stok">Stok</label>
                    <input type="number" class="form-control" id="sisa_stok" name="sisa_stok" placeholder="10"required>
                </div>
                <div class="form-group">
                    <label for="harga">Harga Menu</label>
                    <input type="number" class="form-control" id="harga" name="harga" placeholder="12.000"required>
                </div>
                <div class="form-group">
                    <label for="gambar">Foto Menu</label>
                    <input type="file" class="form-control-file border" id="gambar" name="gambar">
                </div><br>
                <button type="submit" class="btn btn-primary" name="tambah">Tambah</button>
               
            </form>
            
            <?php
            $host = "localhost";
            $username = "root";
            $password = "ilkom123";
            $database = "project_online";

            $koneksi = new mysqli($host, $username, $password, $database);

            // Periksa koneksi
            if ($koneksi->connect_error) {
                die("Koneksi gagal: " . $koneksi->connect_error);
            }

            if (isset($_POST['tambah'])) {
                $nama = $_POST['makanan'];
                $stok = $_POST['sisa_stok'];
                $harga = $_POST['harga'];
                $nama_file = $_FILES['gambar']['name'];
                $source = $_FILES['gambar']['tmp_name'];
                $folder = '../upload/';

                // Cek apakah file gambar valid
                $file_extension = pathinfo($nama_file, PATHINFO_EXTENSION);
                $allowed_extensions = array("jpg", "jpeg", "png", "gif");
                if (!in_array($file_extension, $allowed_extensions)) {
                    echo "Maaf, hanya file gambar dengan ekstensi JPG, JPEG, PNG, dan GIF yang diizinkan.";
                } else {
                    move_uploaded_file($source, $folder . $nama_file);
                    $insert = $koneksi->query("INSERT INTO menu_makanan (makanan, sisa_stok, harga, gambar) VALUES ('$nama', '$stok', '$harga', '$nama_file')");

                    if ($insert) {
                        header("location: menu_makanan.php");
                    } else {
                        echo "Maaf, terjadi kesalahan saat mencoba menyimpan data ke database";
                    }
                }
            }
            ?>
            
        </div>
    </div>
    <!-- Akhir Form Tambah Menu -->

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery.js"></script>
</body>
</html>

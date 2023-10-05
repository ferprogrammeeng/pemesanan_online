<!-- edit_menu.php -->
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

    // Query untuk mengambil data menu makanan berdasarkan id_menu
    $query = "SELECT * FROM menu_makanan WHERE id_menu_makanan = $id_menu";
    $result = $koneksi->query($query);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
    } else {
        echo "Menu makanan tidak ditemukan.";
        exit();
    }
} else {
    echo "ID menu makanan tidak valid.";
    exit();
}

// Tangani proses pengeditan menu makanan jika ada data yang dikirimkan melalui POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Tangani pengeditan menu makanan di sini
    $newMakanan = $_POST['makanan'];
    $newHarga = $_POST['harga'];
    $newStok = $_POST['stok'];

    // Buat query untuk mengupdate data menu makanan
    $updateQuery = "UPDATE menu_makanan SET makanan = '$newMakanan', harga = $newHarga, sisa_stok = $newStok WHERE id_menu_makanan = $id_menu";

    if ($koneksi->query($updateQuery) === TRUE) {
header("Location: menu_makanan.php?success=Menu+makanan+berhasil+diperbarui");
exit();



        exit(); // Penting untuk menghentikan eksekusi kode selanjutnya
    } else {
        echo "Error: " . $updateQuery . "<br>" . $koneksi->error;
    }
}


?>

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

    <title>Bakso Mataram</title>
</head>
<body>
  
    <!-- Form edit -->
  <div class="container">
    <h3 class="text-center mt-3 mb-5">SILAHKAN EDIT MENU</h3>
    <div class="card p-5 mb-5">
      <form method="POST" >
        <div class="form-group">
          <label for="makanan">Nama Menu</label>
          <input type="text" class="form-control" id="makanan" name="makanan" value="<?php echo $row['makanan']; ?>" required>
        </div>
        <div class="form-group">
          <label for="stok">Stok</label>
          <input type="text" class="form-control" id="stok" name="sisa_stok" value="<?php echo $row['sisa_stok'] ?>">
        </div>
        <div class="form-group">
          <label for="harga">Harga Menu</label>
          <input type="text" class="form-control" id="harga" name="harga" value="<?php echo $row['harga'] ?>" placeholder="12.000">
        </div>
        <button type="submit" class="btn btn-primary" name="tambah">Edit</button>
      </form>
  </div>
  </div>
  <!-- Akhir Form edit --> 

   <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>

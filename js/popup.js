// File: popup.js
function tampilkanDetailPesanan() {
    // Mengambil nilai meja, waktu pemesanan, dan max waktu dari PHP
    var meja = "<?php echo $meja; ?>";
    var waktu_pemesanan = "<?php echo $waktu_pemesanan; ?>";
    var max_waktu = "<?php echo $max_waktu; ?>";

    // Tambahkan variabel metode_pembayaran
    var metode_pembayaran = document.getElementById("metode_pembayaran").value;

    var pesananText = "<div class='struk-title'>Bakso Mataram</div>";
    pesananText += "<div class='opening-hours'>Buka Jam: 10:00 AM - 23:00 PM</div>";
    pesananText += "<div class='opening-hours'>Jl.Kaharuddin Nasution no.210, Pekanbaru</div><br>";

    // Tambahkan garis horizontal panjang
    pesananText += "<hr>";

    // Mendapatkan referensi ke tabel pesanan
    var tabel = document.querySelector("table");
    var rows = tabel.querySelectorAll("tbody tr");
    pesananText += "<div class='detail-pesanan'><h4>Detail Pesanan</h4></div>";

    pesananText += "<div class='detail-pesanan'>";
    rows.forEach(function (row) {
        var nama_menu = row.cells[1].innerText;
        var jumlah = row.cells[2].innerText;
        var harga = parseFloat(row.cells[3].innerText.replace(",", "").replace("Rp", "").trim());

        // Menampilkan jumlah dan nama_menu di sebelah kiri dengan jarak ke kiri
        pesananText += "<div class='float-left'>" + jumlah + " " + nama_menu + "</div>";

        // Menampilkan harga di sebelah kanan
        pesananText += "<div class='float-right'>Rp " + harga.toFixed(3) + "</div>";

        // Menggunakan clearfix untuk membersihkan float
        pesananText += "<div class='clearfix'></div>";
    });

    pesananText += "<div class='detail-pesanan float-left'>";
    var total_biaya = document.querySelector("tfoot th:last-child").innerText;
    pesananText += "<div class='text-left'>Total Biaya</div>";
    pesananText += "<div class='text-left'>Waktu Pemesanan</div>";
    pesananText += "<div class='text-left'>Maksimum Waktu</div>";
    pesananText += "<div class='text-left'>Meja</div>";
    pesananText += "<div class='text-left'>Pembayaran</div>";
    pesananText += "</div>";

    pesananText += "<div class='detail-pesanan float-right'>";
    pesananText += "<div class='text-right'>" + total_biaya + "</div>";
    pesananText += "<div class='text-right'>" + waktu_pemesanan + "</div>";
    pesananText += "<div class='text-right'>" + max_waktu + "</div>";
    pesananText += "<div class='text-right'>" + meja + "</div>";
    pesananText += "<div class='text-right'>" + metode_pembayaran + "</div>";
    pesananText += "</div> <br> ";

    // Tambahkan garis horizontal panjang
    pesananText += "==============================<br>";
    pesananText += "<div class='opening-hours'>Terima kasih Anda telah melakukan konfirmasi pembayaran secara online, Silahkan datang ke kasir dan membawa struk pemesanan.</div> ";
    pesananText += "<hr>";
    pesananText += "<div class='total-biaya text-center' style='font-weight:normal'><i>Waktu Pemesanan online maksimal 1 jam 30 menit terhitung setelah pembayaran online. Meja yang dipilih tidak bisa ditempati lagi. Tetapi pesanan tetap bisa diamnil</i></div>";

    // Buat div untuk pop-up
    var popupDiv = document.createElement("div");
    popupDiv.classList.add("popup");
    popupDiv.innerHTML = pesananText;

    // Tambahkan class 'text-center' untuk mengatur teks menjadi pusat
    popupDiv.style.textAlign = "center";

    // Buat div untuk tombol
    var buttonDiv = document.createElement("div");
    buttonDiv.classList.add("button-div");

    // Tambahkan tombol Cetak
    var cetakButton = document.createElement("button");
    cetakButton.classList.add("btn-cetak");
    cetakButton.textContent = "Cetak";
    cetakButton.addEventListener("click", function () {
        // Buka jendela cetak popup
        var popupWindow = window.open("", "_blank");

        // Isi jendela cetak popup dengan isi popup alert
        popupWindow.document.open();
        popupWindow.document.write("<html><head><title>Struk Pemesanan</title></head><body>");
        popupWindow.document.write("<div class='popup'>" + pesananText + "</div>");
        popupWindow.document.write("</body></html>");
        popupWindow.document.close();

        // Cetak jendela cetak popup
        popupWindow.print();
    });

    // Tambahkan tombol Tutup (Close)
    var closeButton = document.createElement("button");
    closeButton.classList.add("btn-close");
    closeButton.textContent = "Tutup";
    closeButton.addEventListener("click", function () {
        // Hapus pop-up dari dokumen
        document.body.removeChild(popupDiv);
    });

    buttonDiv.appendChild(cetakButton);
    buttonDiv.appendChild(closeButton);

    popupDiv.appendChild(buttonDiv);

    // Tampilkan pop-up
    document.body.appendChild(popupDiv);
}

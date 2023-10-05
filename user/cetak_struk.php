<?php
// Sisipkan file tcpdf.php
require_once('tcpdf/tcpdf.php');

// Buat instance objek TCPDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set informasi dokumen
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nama Anda');
$pdf->SetTitle('Struk Pesanan');
$pdf->SetSubject('Struk Pesanan');
$pdf->SetKeywords('Struk, Pesanan');

// Set margin
$pdf->SetMargins(10, 10, 10);

// Tambahkan halaman
$pdf->AddPage();

// Tambahkan konten ke dalam PDF di sini (misalnya, daftar pesanan)

// Output file PDF ke browser
$pdf->Output('struk_pesanan.pdf', 'I');
?>

<?php
session_start();
require_once '../db_connection.php'; // Sesuaikan dengan path yang tepat
require_once('fpdf/fpdf.php'); // Sesuaikan dengan path yang tepat

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'founder') {
    header("Location: ../login.php");
    exit();
}

// Query untuk mengambil data mitra
$query = "SELECT mitra.name, mitra.address, mitra.phone_number, mitra.status_mitra, pendaftaran.longitude, pendaftaran.latitude 
          FROM mitra 
          LEFT JOIN pendaftaran ON pendaftaran.mitra_id = mitra.mitra_id";
$result = $conn->query($query);

// Membuat PDF menggunakan FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);

// Title
$pdf->Cell(0, 10, 'Laporan Data Mitra', 0, 1, 'C');
$pdf->Ln(10);

// Header tabel
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(40, 6, 'Nama Mitra', 1);
$pdf->Cell(50, 6, 'Alamat', 1);
$pdf->Cell(30, 6, 'No. Telepon', 1);
$pdf->Cell(20, 6, 'Status', 1);
$pdf->Cell(25, 6, 'Longitude', 1);
$pdf->Cell(25, 6, 'Latitude', 1);
$pdf->Ln();

// Isi tabel
$pdf->SetFont('Arial', '', 10);
while ($row = $result->fetch_assoc()) {
    $pdf->Cell(40, 6, $row['name'], 1);
    $pdf->Cell(50, 6, $row['address'], 1);
    $pdf->Cell(30, 6, $row['phone_number'], 1);
    $pdf->Cell(20, 6, $row['status_mitra'], 1);
    $pdf->Cell(25, 6, $row['longitude'], 1);
    $pdf->Cell(25, 6, $row['latitude'], 1);
    $pdf->Ln();
}

// Output PDF
$filename = 'generate_laporan_mitra.pdf';
$pdf->Output('D', $filename); // 'D' untuk download file langsung
exit();
?>

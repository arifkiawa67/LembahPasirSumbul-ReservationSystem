<?php
require '../fpdf/fpdf.php'; // Path ke library FPDF
include '../db_connection.php';

// Query data reservasi
$query_reservations = "
    SELECT 
        tbrsv.id_reservation, 
        tbs.name_services, 
        tbtrst.name_tourist, 
        tbrsv.reservation_date, 
        tbrsv.visit_start_date, 
        tbrsv.visit_end_date 
    FROM 
        tb_reservation tbrsv 
    JOIN 
        tb_tourist tbtrst ON tbtrst.id_tourist = tbrsv.id_tourist 
    JOIN 
        tb_services tbs ON tbs.id_services = tbrsv.id_services 
    GROUP BY 
        tbrsv.id_reservation ASC;
";

$result_reservations = $conn->query($query_reservations);

// Inisialisasi FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);

// Header PDF
$pdf->SetTextColor(50, 50, 50);
$pdf->Cell(0, 10, 'Camping Ground Lembah Pasir Sumbul', 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 10, 'Jalan Raya Puncak Kp Pangabetah, RT.01/RW.01, Ciloto, Kabupaten Cianjur, Jawa Barat 43253', 0, 1, 'C');
$pdf->Ln(10); // Tambahkan jarak kosong

// Sub-header
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Data Reservasi', 0, 1, 'C');
$pdf->Ln(5);

// Header tabel
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(200, 220, 255); // Warna header tabel
$pdf->Cell(10, 10, 'No', 1, 0, 'C', true);
$pdf->Cell(10, 10, 'ID', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Nama Layanan', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Nama Wisatawan', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Tgl Reservasi', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Tgl Mulai', 1, 0, 'C', true);
$pdf->Cell(0, 10, 'Tgl Akhir', 1, 1, 'C', true);

// Isi tabel
$pdf->SetFont('Arial', '', 10);
$no = 1;
if ($result_reservations->num_rows > 0) {
    while ($row = $result_reservations->fetch_assoc()) {
        $pdf->Cell(10, 10, $no++, 1, 0, 'C');
        $pdf->Cell(10, 10, $row['id_reservation'], 1, 0, 'C');
        $pdf->Cell(40, 10, $row['name_services'], 1, 0, 'C');
        $pdf->Cell(40, 10, $row['name_tourist'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['reservation_date'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['visit_start_date'], 1, 0, 'C');
        $pdf->Cell(0, 10, $row['visit_end_date'], 1, 1, 'C');
    }
} else {
    $pdf->Cell(245, 10, 'No reservations available.', 1, 1, 'C');
}

// Output PDF
$pdf->Output('D', 'data_reservasi_online.pdf');
?>

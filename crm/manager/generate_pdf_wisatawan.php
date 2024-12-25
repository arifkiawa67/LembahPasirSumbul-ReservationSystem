<?php
require '../fpdf/fpdf.php'; // Path ke library FPDF
include '../db_connection.php';

// Query untuk menggabungkan data dari dua tabel
$query_reservations = "
    SELECT 
        name_off_tourist AS name,
        phoneno_off_tourist AS phone,
        COALESCE(nik_tourist, '-') AS nik,
        '-' AS email
    FROM 
        tb_offline_tourist
    UNION
    SELECT 
        name_tourist AS name,
        phone_number_tourist AS phone,
        '-' AS nik,
        COALESCE(email_tourist, '-') AS email
    FROM 
        tb_tourist
    ORDER BY name ASC;
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
$pdf->Cell(0, 10, 'Data Wisatawan', 0, 1, 'C');
$pdf->Ln(5);

// Header tabel
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(200, 220, 255); // Warna header tabel
$pdf->Cell(10, 10, 'No', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Nama', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'No. HP', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'NIK', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Email', 1, 1, 'C', true);

// Isi tabel
$pdf->SetFont('Arial', '', 10);
$no = 1;
if ($result_reservations->num_rows > 0) {
    while ($row = $result_reservations->fetch_assoc()) {
        $pdf->Cell(10, 10, $no++, 1, 0, 'C');
        $pdf->Cell(50, 10, htmlspecialchars($row['name']), 1, 0, 'C');
        $pdf->Cell(50, 10, htmlspecialchars($row['phone']), 1, 0, 'C');
        $pdf->Cell(30, 10, htmlspecialchars($row['nik']), 1, 0, 'C');
        $pdf->Cell(50, 10, htmlspecialchars($row['email']), 1, 1, 'C');
    }
} else {
    $pdf->Cell(190, 10, 'No data available.', 1, 1, 'C');
}

// Output PDF
$pdf->Output('D', 'data_wisatawan.pdf');
?>

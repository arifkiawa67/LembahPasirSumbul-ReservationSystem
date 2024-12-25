<?php
session_start();
include('db_connection.php');  

if (!isset($_SESSION['user']) || !isset($_SESSION['id_tourist']) || !isset($_SESSION['name_tourist'])) {
    header('Location: login.php');  
    exit();
}


if (!isset($_GET['id_reservation'])) {
    echo "Reservation ID is missing.";
    exit();
}

$id_reservation = $_GET['id_reservation'];


$query = "SELECT tbr.reservation_date, tbr.visit_start_date, tbr.visit_end_date, tbs.name_services, 
                 tbr.is_tools, tbr.price, tbst.title_status, tbr.id_status, tbr.id_tourist, tbr.payment_picture 
          FROM `tb_reservation` tbr 
          JOIN tb_tourist tbt ON tbt.id_tourist = tbr.id_tourist 
          JOIN tb_services tbs ON tbs.id_services = tbr.id_services 
          JOIN tb_status tbst ON tbst.id_status = tbr.id_status 
          WHERE tbr.id_reservation = ?";

$stmt = $conn->prepare($query);


if (!$stmt) {
    die("Query preparation failed: " . $conn->error);  
}

$stmt->bind_param('i', $id_reservation);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Reservation not found.";
    exit();
}

$reservation = $result->fetch_assoc();

$reservation_date = date('Y-m-d', strtotime($reservation['reservation_date']));
$visit_start_date = date('Y-m-d H:i', strtotime($reservation['visit_start_date']));
$visit_end_date = date('H:i', strtotime($reservation['visit_end_date']));
$status = $reservation['title_status'];
$price = number_format($reservation['price'], 2, ',', '.');

// SQL query to get the rented tools details
$tools_query = "SELECT tbt.name_tools, tbr.qty_rent, tbr.price_rent 
                FROM `tb_rent_tools` tbr 
                JOIN tb_tools tbt ON tbt.id_tools = tbr.id_tools 
                WHERE tbr.id_reservation = ?";
$tools_stmt = $conn->prepare($tools_query);

if (!$tools_stmt) {
    die("Query preparation failed: " . $conn->error);  
}

$tools_stmt->bind_param('i', $id_reservation);

$tools_stmt->execute();

$tools_result = $tools_stmt->get_result();

$show_upload_button = ($reservation['id_status'] == 2); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Details</title>
    <link rel="stylesheet" href="styleviewres.css">
</head>
<body>

    <div class="reservation-container">
        <h2>Detail Reservasi</h2>
        <table class="reservation-details">
            <tr>
                <th>Tanggal Reservasi</th>
                <td><?php echo $reservation_date; ?></td>
            </tr>
            <tr>
                <th>Waktu Kunjungan</th>
                <td><?php echo $visit_start_date . " - " . $visit_end_date; ?></td>
            </tr>
            <tr>
                <th>Nama Layanan</th>
                <td><?php echo $reservation['name_services']; ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?php echo $status; ?></td>
            </tr>
            <tr>
                <th>Harga</th>
                <td><?php echo "Rp " . $price; ?></td>
            </tr>
            <tr>
                <th>Bukti Pembayaran</th>
                <td>
                    <?php if (!empty($reservation['payment_picture'])): ?>
                        <img src="data:image/jpeg;base64,<?php echo $reservation['payment_picture']; ?>" alt="Payment Proof" style="width: 100px; height: auto;">
                    <?php else: ?>
                        Tidak ada bukti pembayaran.
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Alat Sewa</th>
                <td><?php echo ($reservation['is_tools'] == 1) ? 'Ya' : 'Tidak'; ?></td>
            </tr>
        </table>
        
        <h3>Daftar Alat yang Disewa</h3>
        <table class="tools-table">
            <thead>
                <tr>
                    <th>Nama Alat</th>
                    <th>Jumlah Sewa</th>
                    <th>Harga Sewa</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($tools_result->num_rows > 0) {
                    while ($tool = $tools_result->fetch_assoc()) {
                        $tool_price = number_format($tool['price_rent'], 2, ',', '.');
                        echo "
                        <tr>
                            <td>{$tool['name_tools']}</td>
                            <td>{$tool['qty_rent']}</td>
                            <td>Rp {$tool_price}</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No tools rented for this reservation.</td></tr>";
                }
                ?>
            </tbody>
        </table>
            <br></br>
        <?php if ($show_upload_button): ?>
            <div class="upload-payment-proof">
                <form action="upload_payment_proof.php" method="post" enctype="multipart/form-data">
                    <label for="payment_picture">Unggah Bukti Pembayaran:</label>
                    <input type="file" name="payment_picture" id="payment_picture" required>
                    <input type="hidden" name="id_reservation" value="<?php echo $id_reservation; ?>">
                    <button type="submit">Upload</button>
                </form>
            </div>
        <?php endif; ?>

        <br></br>
        <div class="back-btn">
            <a href="index.php">Kembali ke Riwayat</a>
        </div>
    </div>

</body>
</html>

<?php
include '../db_connection.php'; 

if (!isset($_GET['id'])) {
    die("Permintaan tidak valid. ID reservasi tidak ditemukan.");
}

$id = intval($_GET['id']);

$query = "SELECT tbro.id_reservation, tbr.name_tourist, tbr.email_tourist, tbr.phone_number_tourist, 
          tbs.name_services, tbs.desc_services, tbs.price_services, tbro.reservation_date, 
          tbro.visit_start_date, tbro.visit_end_date, tbro.payment_picture, 
          tbst.title_status, tbst.desc_status, tbro.id_status
          FROM tb_reservation tbro 
          JOIN tb_tourist tbr ON tbr.id_tourist = tbro.id_tourist 
          JOIN tb_services tbs ON tbs.id_services = tbro.id_services 
          JOIN tb_status tbst ON tbst.id_status = tbro.id_status 
          WHERE tbro.id_reservation = ?";

$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Gagal mempersiapkan query: " . $conn->error);
}

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Reservasi dengan ID tersebut tidak ditemukan.");
}

$data = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_status = intval($_POST['new_status']);
    
    if ($new_status == 5) {
        $current_date = date('Y-m-d H:i:s');  
        $updateQuery = "UPDATE tb_reservation SET id_status = ?, visit_end_date = ? WHERE id_reservation = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("isi", $new_status, $current_date, $id);
    } else {
        $updateQuery = "UPDATE tb_reservation SET id_status = ? WHERE id_reservation = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("ii", $new_status, $id);
    }
    
    if ($updateStmt->execute()) {
        echo "<script>alert('Status berhasil diupdate!'); window.location.href = ''; </script>";
    } else {
        echo "Gagal mengupdate status: " . $conn->error;
    }
    $updateStmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Reservasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f7f7f7;
            color: #555;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            color: #ffffff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn-back {
            background-color: #6c757d;
        }

        .btn-back:hover {
            background-color: #5a6268;
        }

        img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Detail Reservasi</h1>
        <table>
            <tr>
                <th>ID Reservasi</th>
                <td><?= htmlspecialchars($data['id_reservation']) ?></td>
            </tr>
            <tr>
                <th>Nama</th>
                <td><?= htmlspecialchars($data['name_tourist']) ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?= htmlspecialchars($data['email_tourist']) ?></td>
            </tr>
            <tr>
                <th>Nomor Telepon</th>
                <td><?= htmlspecialchars($data['phone_number_tourist']) ?></td>
            </tr>
            <tr>
                <th>Layanan</th>
                <td><?= htmlspecialchars($data['name_services']) ?></td>
            </tr>
            <tr>
                <th>Deskripsi Layanan</th>
                <td><?= htmlspecialchars($data['desc_services']) ?></td>
            </tr>
            <tr>
                <th>Harga</th>
                <td>Rp<?= htmlspecialchars(number_format($data['price_services'], 0, ',', '.')) ?></td>
            </tr>
            <tr>
                <th>Bukti Pembayaran</th>
                <td>
                    <?php if (!empty($data['payment_picture'])): ?>
                        <img src="data:image/jpeg;base64,<?php echo $data['payment_picture']; ?>" alt="Payment Proof" style="width: 100px; height: auto;">
                    <?php else: ?>
                        Tidak ada bukti pembayaran.
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Tanggal Reservasi</th>
                <td><?= htmlspecialchars($data['reservation_date']) ?></td>
            </tr>
            <tr>
                <th>Tanggal Mulai Kunjungan</th>
                <td><?= htmlspecialchars($data['visit_start_date']) ?></td>
            </tr>
            <tr>
                <th>Tanggal Akhir Kunjungan</th>
                <td><?= htmlspecialchars($data['visit_end_date']) ?></td>
            </tr>
            
            <tr>
                <th>Status</th>
                <td><?= htmlspecialchars($data['title_status']) ?></td>
            </tr>
            <tr>
                <th>Deskripsi Status</th>
                <td><?= htmlspecialchars($data['desc_status']) ?></td>
            </tr>
        </table>

        <?php if ($data['id_status'] == 1): ?>
            <form method="post">
                <button type="submit" name="new_status" value="2" class="btn">Setujui Reservasi</button>
                <button type="submit" name="new_status" value="6" class="btn">Tolak Reservasi</button>
            </form>
        <?php elseif ($data['id_status'] == 2): ?>
            <form method="post">
                <button type="submit" name="new_status" value="3" class="btn">Pembayaran Diterima</button>
                <button type="submit" name="new_status" value="7" class="btn">Pembayaran Ditolak</button>
            </form>
        <?php elseif ($data['id_status'] == 3): ?>
            <form method="post">
                <button type="submit" name="new_status" value="4" class="btn">Reservasi Telah Dilakukan</button>
                <button type="submit" name="new_status" value="8" class="btn">Reservasi Dibatalakan</button>
            </form>
        <?php elseif ($data['id_status'] == 4): ?>
            <form method="post">
                <button type="submit" name="new_status" value="5" class="btn">Selesaikan Reservasi</button>
            </form>
        <?php endif; ?>

        <br>
        <a href="list_reservation.php" class="btn btn-back">Kembali ke Daftar Reservasi</a>
    </div>
</body>
</html>

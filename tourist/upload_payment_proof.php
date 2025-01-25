<?php
session_start();
include('db_connection.php'); // Include the database connection
include('send_notif_payment.php'); // Include the notification function

// Check if the user is logged in and the session variables are set
if (!isset($_SESSION['user']) || !isset($_SESSION['id_tourist']) || !isset($_SESSION['name_tourist'])) {
    header('Location: login.php');
    exit();
}

// Check if the file was uploaded
if (isset($_FILES['payment_picture']) && $_FILES['payment_picture']['error'] == 0) {
    // Get the uploaded file
    $fileTmpPath = $_FILES['payment_picture']['tmp_name'];

    // Read the file content and encode it as base64
    $fileContent = file_get_contents($fileTmpPath);
    $base64Encoded = base64_encode($fileContent);

    // Get the reservation ID from the form
    $id_reservation = $_POST['id_reservation'];
    $userPhone = $_SESSION['user']; // Assuming phone number is stored in session
    $userName = $_SESSION['name_tourist'];

    // SQL query to update the payment_picture field with the base64-encoded string
    $query = "UPDATE tb_reservation SET payment_picture = ? WHERE id_reservation = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Query preparation failed: " . $conn->error); // Show error if query preparation fails
    }

    // Bind the parameters and execute the query
    $stmt->bind_param('si', $base64Encoded, $id_reservation);

    if ($stmt->execute()) {
        // Call the sendNotification function with a custom message
        $message = "Halo $userName,\n\nBukti pembayaran Anda untuk reservasi ID $id_reservation telah berhasil diunggah. Mohon menunggu konfirmasi dari admin. Kami akan mengirimkan notifikasi jika pembayaran Anda telah disetujui.\n\nTerima kasih atas kepercayaannya.";
        sendNotificationUploadBayar($userPhone, $message);

        echo "Payment proof uploaded successfully!";
        header('Location: view_reservation.php');
    } else {
        echo "Error uploading payment proof.";
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Error: No file uploaded or there was an upload issue.";
}

$conn->close();
?>

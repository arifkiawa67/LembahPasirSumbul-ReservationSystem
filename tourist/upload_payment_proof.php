<?php
session_start();
include('db_connection.php');  // Include the database connection

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
    
    // SQL query to update the payment_picture field with the base64-encoded string
    $query = "UPDATE tb_reservation SET payment_picture = ? WHERE id_reservation = ?";
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);  // Show error if query preparation fails
    }
    
    // Bind the parameters and execute the query
    $stmt->bind_param('si', $base64Encoded, $id_reservation);
    
    if ($stmt->execute()) {
        echo "Payment proof uploaded successfully!";
        header('Location: index.php');
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

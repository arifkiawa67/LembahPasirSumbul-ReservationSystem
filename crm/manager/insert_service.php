<?php
session_start();
include '../db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name_services = $_POST['name_services'];
    $desc_services = $_POST['desc_services'];
    $price_services = $_POST['price_services'];

    $query = "INSERT INTO tb_services (name_services, desc_services, price_services) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssd", $name_services, $desc_services, $price_services);

    if ($stmt->execute()) {
        header("Location: manageservice.php?success=Service added successfully");
    } else {
        header("Location: manageservice.php?error=Failed to add service");
    }

    $stmt->close();
    $conn->close();
}
?>

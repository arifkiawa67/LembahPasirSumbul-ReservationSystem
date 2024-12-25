<?php
session_start();
include '../db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

if (isset($_GET['id'])) {
    $id_services = $_GET['id'];

    $query = "DELETE FROM tb_services WHERE id_services = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_services);

    if ($stmt->execute()) {
        header("Location: manageservice.php?success=Service deleted successfully");
    } else {
        header("Location: manageservice.php?error=Failed to delete service");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: manageservice.php?error=Invalid service ID");
}
?>

<?php
session_start();
include '../db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

if (isset($_GET['id'])) {
    $id_tools = $_GET['id'];

    $query = "DELETE FROM tb_tools WHERE id_tools = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_tools);

    if ($stmt->execute()) {
        header('Location: tools.php'); 
        exit();
    } else {
        echo "Failed to delete tool: " . $stmt->error;
    }
}
?>

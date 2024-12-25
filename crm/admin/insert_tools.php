<?php
session_start();
include '../db_connection.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name_tools = $_POST['name_tools'];
    $qty_tools = $_POST['qty_tools'];
    $price_tools = $_POST['price_tools'];

    if (empty($name_tools) || empty($qty_tools) || empty($price_tools)) {
        echo "All fields are required!";
    } else {
        $query = "INSERT INTO tb_tools (name_tools, qty_tools, price_tools) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        
        $stmt->bind_param("sii", $name_tools, $qty_tools, $price_tools);
        
        if ($stmt->execute()) {
            echo "Data alat berhasil disimpan!";
            header("refresh:1;url=tools.php");
            exit();
        } else {
            echo "Gagal menyimpan data: " . $stmt->error;
        }
        
        $stmt->close(); 
    }
}
?>

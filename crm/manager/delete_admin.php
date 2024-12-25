<?php
include '../db_connection.php'; 

// Check if ID is passed
if (isset($_GET['id'])) {
    $id_admin = $_GET['id'];

    // Delete query
    $delete_query = "DELETE FROM tb_admin WHERE id_admin = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $id_admin);

    if ($stmt->execute()) {
        header("Location: manageadmin.php"); // Redirect to the admin list page after deletion
        exit;
    } else {
        echo "Failed to delete admin.";
    }
} else {
    echo "Invalid request.";
    exit;
}
?>

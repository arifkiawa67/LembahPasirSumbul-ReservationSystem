<?php
include '../db_connection.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name_admin = $_POST['name_admin'];
    $email_admin = $_POST['email_admin'];
    $password_admin = $_POST['password_admin'];

    // Insert query
    $insert_query = "INSERT INTO tb_admin (name_admin, email_admin, password_admin) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("sss", $name_admin, $email_admin, $password_admin);

    if ($insert_stmt->execute()) {
        header("Location: manageadmin.php"); // Redirect to the admin list page after successful insertion
        exit;
    } else {
        echo "Failed to insert admin data.";
    }
}
?>

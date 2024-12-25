<?php
include '../db_connection.php'; 

// Check if ID is passed
if (isset($_GET['id'])) {
    $id_admin = $_GET['id'];

    // Fetch admin data from database
    $query = "SELECT id_admin, name_admin, email_admin, password_admin FROM tb_admin WHERE id_admin = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_admin);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if admin exists
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
    } else {
        echo "Admin not found.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}

// Update admin data if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name_admin = $_POST['name_admin'];
    $email_admin = $_POST['email_admin'];
    $password_admin = $_POST['password_admin'];

    // Update query
    $update_query = "UPDATE tb_admin SET name_admin = ?, email_admin = ?, password_admin = ? WHERE id_admin = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("sssi", $name_admin, $email_admin, $password_admin, $id_admin);

    if ($update_stmt->execute()) {
        header("Location: manageadmin.php"); // Redirect to the admin list page after successful update
        exit;
    } else {
        echo "Failed to update admin details.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        label {
            font-size: 14px;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Admin</h2>
        <form method="POST">
            <div>
                <label for="name_admin">Name:</label>
                <input type="text" id="name_admin" name="name_admin" value="<?php echo $admin['name_admin']; ?>" required>
            </div>
            <div>
                <label for="email_admin">Email:</label>
                <input type="email" id="email_admin" name="email_admin" value="<?php echo $admin['email_admin']; ?>" required>
            </div>
            <div>
                <label for="password_admin">Password:</label>
                <input type="password" id="password_admin" name="password_admin" value="<?php echo $admin['password_admin']; ?>" required>
            </div>
            <div>
                <button type="submit">Update Admin</button>
            </div>
        </form>
        <div class="back-link">
            <a href="manageadmin.php">Back to Admin List</a>
        </div>
    </div>
</body>
</html>

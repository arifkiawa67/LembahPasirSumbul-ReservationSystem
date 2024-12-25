<?php
session_start();
include '../db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

// Proses Update Data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_services = $_POST['id_services'];
    $name_services = $_POST['name_services'];
    $desc_services = $_POST['desc_services'];
    $price_services = $_POST['price_services'];

    $query = "UPDATE tb_services SET name_services = ?, desc_services = ?, price_services = ? WHERE id_services = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssdi", $name_services, $desc_services, $price_services, $id_services);

    if ($stmt->execute()) {
        header("Location: manageservice.php?success=Service updated successfully");
        exit();
    } else {
        $error_message = "Failed to update service.";
    }
    $stmt->close();
}

// Menampilkan Data di Form Edit
if (isset($_GET['id'])) {
    $id_services = $_GET['id'];

    $query = "SELECT * FROM tb_services WHERE id_services = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_services);
    $stmt->execute();
    $result = $stmt->get_result();
    $service = $result->fetch_assoc();

    if (!$service) {
        die("Service not found.");
    }
    $stmt->close();
} else {
    header("Location: manageservice.php?error=Invalid service ID");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Service</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        h3 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        form label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
        form input, form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        form button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        form button:hover {
            background-color: #218838;
        }
        a {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #007bff;
        }
        a:hover {
            text-decoration: underline;
        }
        .error-message {
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Edit Service</h3>
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form action="edit_service.php" method="POST">
            <input type="hidden" name="id_services" value="<?php echo $service['id_services']; ?>">

            <label>Nama Service:</label>
            <input type="text" name="name_services" value="<?php echo htmlspecialchars($service['name_services']); ?>" required>

            <label>Deskripsi:</label>
            <textarea name="desc_services" rows="5" required><?php echo htmlspecialchars($service['desc_services']); ?></textarea>

            <label>Harga:</label>
            <input type="number" name="price_services" value="<?php echo htmlspecialchars($service['price_services']); ?>" step="0.01" required>

            <button type="submit">Update</button>
            <a href="manageservice.php">Back</a>
        </form>
    </div>
</body>
</html>

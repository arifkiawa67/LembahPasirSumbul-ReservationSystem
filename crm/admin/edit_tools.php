<?php
session_start();
include '../db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

if (isset($_GET['id'])) {
    $id_tools = $_GET['id'];

    // Ambil data alat berdasarkan ID
    $query = "SELECT * FROM tb_tools WHERE id_tools = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_tools);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $tool = $result->fetch_assoc();
    } else {
        echo "Tool not found!";
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name_tools = $_POST['name_tools'];
    $qty_tools = $_POST['qty_tools'];
    $price_tools = $_POST['price_tools'];

    $query_update = "UPDATE tb_tools SET name_tools = ?, qty_tools = ?, price_tools = ? WHERE id_tools = ?";
    $stmt_update = $conn->prepare($query_update);
    $stmt_update->bind_param("siii", $name_tools, $qty_tools, $price_tools, $id_tools);

    if ($stmt_update->execute()) {
        header('Location: tools.php');
        exit();
    } else {
        echo "Failed to update data: " . $stmt_update->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Alat Camping</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f9;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            font-size: 24px;
            padding: 10px;
            border-radius: 8px;
        }
        .form-group label {
            font-weight: bold;
        }
        .form-control {
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-header">
            Edit Alat Camping
        </div>
        <div class="card-body">
            <form action="edit_tools.php?id=<?php echo $id_tools; ?>" method="POST">
                <div class="form-group">
                    <label for="name_tools">Nama Alat</label>
                    <input type="text" name="name_tools" class="form-control" value="<?php echo $tool['name_tools']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="qty_tools">Jumlah</label>
                    <input type="number" name="qty_tools" class="form-control" value="<?php echo $tool['qty_tools']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="price_tools">Harga Sewa Satuan</label>
                    <input type="number" step="0.01" name="price_tools" class="form-control" value="<?php echo $tool['price_tools']; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Update Data</button>
            </form>
            <a href="tools.php" class="back-link">Kembali ke Daftar Alat</a>
        </div>
    </div>
</div>

</body>
</html>

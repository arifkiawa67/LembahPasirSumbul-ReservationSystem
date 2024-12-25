<?php
session_start();
include 'db_connection.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']); 
    $password = trim($_POST['password']); 
    $role = $_POST['role'];

    if (empty($email) || empty($password) || empty($role)) {
        echo "<script>alert('Please fill all fields!');</script>";
    } else {
        if ($role === 'Admin') {
            $stmt = $conn->prepare("SELECT * FROM tb_admin WHERE email_admin = ? AND password_admin = ?");
            $stmt->bind_param("ss", $email, $password);
        } elseif ($role === 'Manager') {
            $stmt = $conn->prepare("SELECT * FROM tb_manager WHERE email_manager = ? AND password_manager = ?");
            $stmt->bind_param("ss", $email, $password);
        } else {
            echo "<script>alert('Invalid role selected!');</script>";
            exit();
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            $_SESSION['user_id'] = $role === 'Admin' ? $user['id_admin'] : $user['id_manager'];
            $_SESSION['role'] = $role;

            echo "<script>alert('Login successful!');</script>";
            $redirect = $role === 'Admin' ? 'admin/index.php' : 'manager/index.php';
            echo "<script>window.location='$redirect';</script>";
            exit();
        } else {
            echo "<script>alert('Incorrect email or password!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Roboto', sans-serif; }
    </style>
</head>
<body class="bg-green-700 flex items-center justify-center min-h-screen">
    <div class="bg-green-600 rounded-lg shadow-lg flex overflow-hidden max-w-4xl w-full">
        <div class="w-1/2 p-8 hidden md:flex items-center justify-center">
            <img src="images.png" alt="Illustration" class="w-full h-auto" height="400" width="400" />
        </div>
        <div class="w-full md:w-1/2 bg-white p-8">
            <h1 class="text-2xl font-bold text-gray-800">Hello!<br />Good Morning</h1>
            <form method="POST" class="mt-8">
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600" required />
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700">Password</label>
                    <input type="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600" required />
                </div>
                <div class="mb-4">
                    <span class="block text-gray-700">Role</span>
                    <div class="flex items-center mt-2">
                        <input type="radio" name="role" value="Admin" id="admin" class="mr-2" required />
                        <label for="admin" class="text-gray-700">Admin</label>
                    </div>
                    <div class="flex items-center mt-2">
                        <input type="radio" name="role" value="Manager" id="manager" class="mr-2" required />
                        <label for="manager" class="text-gray-700">Manager</label>
                    </div>
                </div>
                <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition duration-300">Login</button>
            </form>
        </div>
    </div>
</body>
</html>

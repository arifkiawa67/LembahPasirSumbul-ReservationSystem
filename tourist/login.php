<?php
session_start();
include('db_connection.php');  // Menghubungkan dengan file koneksi database

// Jika form login disubmit
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query untuk mengecek kecocokan email dan password
    $sql = "SELECT * FROM tb_tourist WHERE email_tourist = '$email' AND password_tourist = '$password'";
    $result = $conn->query($sql);

    // Jika ditemukan user
    if ($result->num_rows > 0) {
        // Ambil data user
        $user = $result->fetch_assoc();

        // Simpan data ke session
        $_SESSION['user'] = $email;  // Menyimpan email ke session
        $_SESSION['id_tourist'] = $user['id_tourist'];  // Menyimpan id_tourist ke session
        $_SESSION['name_tourist'] = $user['name_tourist'];  // Menyimpan name_tourist ke session

        header('Location: index.php');  // Arahkan ke index.php jika login berhasil
        exit();
    } else {
        $error_message = "Email atau password salah!";
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Customer</title>
    <style>
        /* Mengatur gaya dasar halaman */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Mengatur wadah login */
        .login-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        /* Gaya untuk heading */
        h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        /* Gaya form input dan label */
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #4CAF50;
            outline: none;
        }

        /* Gaya tombol login */
        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Gaya pesan error */
        p {
            color: red;
            text-align: center;
            margin-top: 10px;
        }

        /* Gaya tautan ke register */
        p a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }

        p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login Customer</h2>
        <?php
        if (isset($error_message)) {
            echo "<p>$error_message</p>";
        }
        ?>
        <form method="POST" action="">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <button type="submit" name="login">Login</button>
        </form>
        <p>Belum punya akun? <a href="register.php">Daftar sekarang</a></p>
    </div>
</body>
</html>

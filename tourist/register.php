<?php
include('db_connection.php');  // Menghubungkan dengan file koneksi database

// Jika form registrasi disubmit
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    // Query untuk memasukkan data ke database
    $sql = "INSERT INTO tb_tourist (name_tourist, email_tourist, phone_number_tourist, password_tourist) 
            VALUES ('$name', '$email', '$phone', '$password')";

    if ($conn->query($sql) === TRUE) {
        // Set pesan sukses jika registrasi berhasil
        $success_message = "Registrasi berhasil untuk $email, silahkan masuk untuk melanjutkan.";
    } else {
        $error_message = "Terjadi kesalahan saat mendaftar. Coba lagi.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Customer</title>
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

        /* Mengatur wadah registrasi */
        .register-container {
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

        input[type="text"],
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

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #4CAF50;
            outline: none;
        }

        /* Gaya tombol registrasi */
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

        /* Gaya tautan ke login */
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
    <div class="register-container">
        <h2>Daftar Customer</h2>
        <?php
        if (isset($error_message)) {
            echo "<p>$error_message</p>";
        }
        ?>
        <form method="POST" action="">
            <label for="name">Nama:</label>
            <input type="text" name="name" id="name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="phone">Nomor Telepon:</label>
            <input type="text" name="phone" id="phone" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <button type="submit" name="register">Daftar</button>
        </form>
        <p>Sudah punya akun? <a href="login.php">Login sekarang</a></p>
    </div>
</body>
</html>

<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'db_camp';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('Koneksi gagal: ' . $conn->connect_error);
}

// Jangan tutup koneksi di sini!
// echo 'Koneksi berhasil ke database ' . $database; // Ini opsional, bisa dihapus jika tidak perlu.
?>

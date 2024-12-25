<?php
session_start(); // Mulai session

// Hapus semua session yang disimpan
session_unset();

// Hancurkan session
session_destroy();

// Redirect ke halaman login atau halaman lain setelah logout
header('Location: ../index.php'); // Ganti dengan path yang sesuai jika perlu
exit();
?>

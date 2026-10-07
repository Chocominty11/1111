<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "study_buddy_db";

// Perhatikan nama variabel ini harus $conn
$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}
?>
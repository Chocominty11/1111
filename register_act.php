<?php
include '../config/db.php';

// Ambil data dari form
$username = $_POST['username'];
$email    = $_POST['email'];
$password = $_POST['password'];

// 1. Cek apakah email sudah terdaftar?
$check = mysqli_query($conn, "SELECT email FROM users WHERE email = '$email'");
if(mysqli_num_rows($check) > 0){
    echo "<script>
            alert('Email sudah terdaftar! Silahkan login.');
            window.location.href='../login.php';
          </script>";
    exit; // Stop proses
}

// 2. Enkripsi Password (Security Best Practice)
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// 3. Buat Avatar Random (Pakai API UI Avatars biar otomatis ada gambarnya)
$avatar_url = "https://ui-avatars.com/api/?name=" . urlencode($username) . "&background=random";

// 4. Masukkan ke Database
$query = "INSERT INTO users (username, email, password, avatar) 
          VALUES ('$username', '$email', '$hashed_password', '$avatar_url')";

if(mysqli_query($conn, $query)){
    echo "<script>
            alert('Pendaftaran Berhasil! Silahkan Login.');
            window.location.href='../login.php';
          </script>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
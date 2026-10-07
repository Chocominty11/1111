<?php
session_start();
include '../config/db.php'; // Hubungkan ke database

// Cek apakah data dikirim lewat POST
if(isset($_POST['group_id']) && isset($_POST['message'])){
    
    $group_id = $_POST['group_id'];
    $user_id  = $_SESSION['user_id'];
    
    // Bersihkan input biar aman dari karakter aneh (SQL Injection)
    $message  = mysqli_real_escape_string($conn, $_POST['message']);

    // Pastikan pesan tidak kosong
    if(!empty($message)){
        $query = "INSERT INTO messages (group_id, user_id, message) 
                  VALUES ('$group_id', '$user_id', '$message')";
        
        if(mysqli_query($conn, $query)){
            echo "Berhasil";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>
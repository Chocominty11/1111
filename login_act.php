<?php
session_start();
include '../config/db.php'; // Path naik satu level (..)

$email    = $_POST['email'];
$password = $_POST['password'];

$query  = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
$user   = mysqli_fetch_assoc($query);

if($user){
    if(password_verify($password, $user['password'])){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['avatar'] = $user['avatar'];
        header("Location: ../index.php");
    } else {
        echo "<script>alert('Password salah!'); window.location.href='../login.php';</script>";
    }
} else {
    echo "<script>alert('Email tidak terdaftar!'); window.location.href='../register.php';</script>";
}
?>
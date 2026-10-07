<?php
// Cek session, kalau belum login lempar ke login page
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Study Buddy Dashboard</title>
    
    <link rel="stylesheet" href="assets/css/dashboard.css"> 
    
</head>
<body>

    <nav class="navbar">
        <a href="index.php" class="logo">StudyBuddy 🎓</a>
        <div class="nav-links">
            <a href="index.php">Cari Grup</a>
            <a href="my_groups.php">Grup Saya</a>
            <a href="profil.php">Profil</a>
            <a href="logout.php" style="color: #e74c3c;">Logout</a>
        </div>
    </nav>
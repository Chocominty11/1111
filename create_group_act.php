<?php
session_start();
include '../config/db.php';

// Cek login
if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}

$user_id     = $_SESSION['user_id'];
$group_name  = mysqli_real_escape_string($conn, $_POST['group_name']);
$description = mysqli_real_escape_string($conn, $_POST['description']);

// 1. QUERY INSERT GRUP
$query_group = "INSERT INTO study_groups (group_name, description, created_by) 
                VALUES ('$group_name', '$description', '$user_id')";

if(mysqli_query($conn, $query_group)){
    
    // 2. AMBIL ID GRUP YANG BARU DIBUAT
    $new_group_id = mysqli_insert_id($conn);

    // 3. MASUKKAN USER SEBAGAI ANGGOTA (MEMBER) PERTAMA
    // PERBAIKAN DISINI: Tambahkan status='active'
    $query_member = "INSERT INTO group_members (group_id, user_id, status) 
                     VALUES ('$new_group_id', '$user_id', 'active')";
    
    mysqli_query($conn, $query_member);

    // Sukses, langsung arahkan ke Chat Room
    header("Location: ../chat_room.php?id=" . $new_group_id);

} else {
    echo "Gagal membuat grup: " . mysqli_error($conn);
}
?>
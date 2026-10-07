<?php
session_start();
include '../config/db.php';

$user_id  = $_SESSION['user_id'];
$group_id = $_GET['id'];

// 1. Cek apakah user sudah pernah gabung/request?
$check = mysqli_query($conn, "SELECT * FROM group_members WHERE user_id='$user_id' AND group_id='$group_id'");

if(mysqli_num_rows($check) == 0){
    // 2. Kalau belum, masukkan dengan status 'PENDING'
    // Jadi dia belum bisa chat, harus nunggu di-acc
    $query = "INSERT INTO group_members (group_id, user_id, status) VALUES ('$group_id', '$user_id', 'pending')";
    
    if(mysqli_query($conn, $query)){
        echo "<script>
            alert('Permintaan gabung terkirim! Tunggu admin grup menerima kamu ya.');
            window.location.href='../index.php';
        </script>";
    }
} else {
    // Kalau sudah pernah request/gabung
    $data = mysqli_fetch_assoc($check);
    if($data['status'] == 'pending'){
        echo "<script>alert('Sabar ya, permintaanmu masih menunggu persetujuan admin.'); window.location.href='../index.php';</script>";
    } else {
        // Kalau sudah active, langsung masuk chat
        header("Location: ../chat_room.php?id=" . $group_id);
    }
}
?>
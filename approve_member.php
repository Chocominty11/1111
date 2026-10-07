<?php
session_start();
include '../config/db.php';

$member_id = $_GET['id']; // ID dari tabel group_members
$action    = $_GET['action'];

if($action == 'accept'){
    mysqli_query($conn, "UPDATE group_members SET status='active' WHERE id='$member_id'");
} else {
    mysqli_query($conn, "DELETE FROM group_members WHERE id='$member_id'");
}

// Kembali ke halaman sebelumnya
header("Location: " . $_SERVER['HTTP_REFERER']);
?>
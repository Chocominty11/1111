<?php
session_start();
include '../config/db.php';

// 1. Cek Login
if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}

$group_id = $_GET['id'];
$user_id  = $_SESSION['user_id'];

// 2. VERIFIKASI KEAMANAN (PENTING!)
// Cek apakah yang mau menghapus benar-benar ADMIN (pembuat) grup itu?
// Jangan sampai orang iseng ngetik URL delete punya orang lain.
$check_owner = mysqli_query($conn, "SELECT * FROM study_groups WHERE id='$group_id' AND created_by='$user_id'");

if(mysqli_num_rows($check_owner) > 0){
    
    // Urutan Menghapus (Wajib urut biar database gak error)
    
    // A. Hapus semua Pesan di grup ini dulu
    mysqli_query($conn, "DELETE FROM messages WHERE group_id='$group_id'");

    // B. Hapus semua Anggota di grup ini
    mysqli_query($conn, "DELETE FROM group_members WHERE group_id='$group_id'");

    // C. Terakhir, Hapus Grupnya
    $delete_group = mysqli_query($conn, "DELETE FROM study_groups WHERE id='$group_id'");

    if($delete_group){
        echo "<script>
                alert('Grup berhasil dibubarkan!');
                window.location.href='../index.php';
              </script>";
    } else {
        echo "Gagal menghapus: " . mysqli_error($conn);
    }

} else {
    // Kalau bukan pemilik grup coba-coba hapus
    echo "<script>
            alert('ANDA BUKAN ADMIN GRUP INI! Dilarang menghapus.');
            window.location.href='../index.php';
          </script>";
}
?>
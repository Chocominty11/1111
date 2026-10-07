<?php
session_start();
include '../config/db.php';

// Pastikan user login
if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// === KASUS 1: USER KLIK TOMBOL "GANTI GAYA AVATAR" ===
if(isset($_POST['new_avatar'])){
    
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    
    // Generate URL Avatar Random
    $rand = rand(1, 1000);
    $avatar_url = "https://ui-avatars.com/api/?name=" . urlencode($username) . "&background=random&length=1&font-size=0.5&v=" . $rand;
    
    // Hanya update kolom avatar
    $query = "UPDATE users SET avatar='$avatar_url' WHERE id='$user_id'";
    
    if(mysqli_query($conn, $query)){
        header("Location: ../profil.php"); // Sukses, langsung refresh
    } else {
        echo "Error Ganti Avatar: " . mysqli_error($conn);
    }

} 
// === KASUS 2: USER KLIK TOMBOL "SIMPAN PERUBAHAN" ===
else if(isset($_POST['update'])){
    
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $major    = mysqli_real_escape_string($conn, $_POST['major']);
    $semester = (int) $_POST['semester'];
    $bio      = mysqli_real_escape_string($conn, $_POST['bio']);

    // Update data diri (Avatar tidak disentuh)
    $query = "UPDATE users SET 
              username='$username',
              major='$major',
              semester='$semester',
              bio='$bio'
              WHERE id='$user_id'";

    if(mysqli_query($conn, $query)){
        // Update session username biar navbar berubah juga
        $_SESSION['username'] = $username;
        
        echo "<script>
                alert('Data profil berhasil disimpan!');
                window.location.href='../profil.php';
              </script>";
    } else {
        echo "Error Simpan Data: " . mysqli_error($conn);
    }

} else {
    // Kalau user iseng buka file ini langsung tanpa lewat tombol
    header("Location: ../profil.php");
}
?>
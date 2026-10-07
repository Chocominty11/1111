<?php
session_start();
include 'config/db.php';
include 'includes/header.php'; 

// Cek login
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data user terbaru
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
$data = mysqli_fetch_assoc($query);

// Hitung Statistik: Berapa grup yang diikuti?
$query_stats = mysqli_query($conn, "SELECT * FROM group_members WHERE user_id='$user_id' AND status='active'");
$total_groups = mysqli_num_rows($query_stats);
?>

<style>
    body { background-color: #FDFBF7; }
    
    .profile-wrapper {
        max-width: 900px;
        margin: 40px auto;
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 30px;
        align-items: start;
        padding: 0 20px;
    }

    /* SIDEBAR KIRI */
    .profile-sidebar {
        background: white;
        border-radius: 20px;
        text-align: center;
        padding: 30px 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    .profile-avatar {
        width: 120px; height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #FDFBF7;
        margin-bottom: 15px;
    }
    .btn-random {
        background: #fff; border: 1px solid #ddd; color: #555;
        padding: 8px 15px; border-radius: 20px; font-size: 0.8rem; cursor: pointer;
        transition: 0.3s;
    }
    .btn-random:hover { background: #f0f0f0; border-color: #ccc; }

    .stat-box {
        background: #f8f9fa; padding: 15px; border-radius: 10px;
        margin-top: 20px; border: 1px solid #eee;
    }
    .stat-number { font-size: 1.5rem; font-weight: bold; color: #88B04B; display: block; }
    
    /* KONTEN KANAN */
    .profile-content {
        background: white; border-radius: 20px; padding: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    .section-title {
        color: #444; font-size: 1.2rem; margin-bottom: 25px;
        border-bottom: 2px solid #f0f0f0; padding-bottom: 10px;
    }

    /* FORM STYLES */
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .input-group { margin-bottom: 20px; }
    .input-group label { display: block; font-weight: 500; color: #666; margin-bottom: 8px; font-size: 0.9rem; }
    
    .custom-input {
        width: 100%; padding: 10px 15px;
        border: 1px solid #ddd; border-radius: 8px;
        box-sizing: border-box; font-family: inherit;
    }
    .custom-input:focus { border-color: #88B04B; outline: none; }

    .btn-save {
        background-color: #88B04B; color: white; border: none;
        padding: 12px 30px; border-radius: 8px; font-weight: 600;
        cursor: pointer; margin-top: 10px; width: 100%;
    }
    .btn-save:hover { background-color: #769a40; }
</style>

<div class="profile-wrapper">
    
    <div class="profile-sidebar">
        <img src="<?php echo $data['avatar']; ?>" class="profile-avatar">
        
        <form action="actions/update_profil.php" method="POST">
            <button type="submit" name="new_avatar" value="true" class="btn-random">🎲 Ganti Gaya Avatar</button>
            <input type="hidden" name="username" value="<?php echo htmlspecialchars($data['username']); ?>">
        </form>

        <h2 style="margin: 15px 0 5px 0;"><?php echo htmlspecialchars($data['username']); ?></h2>
        <p style="color: #999; font-size: 0.9rem; margin: 0;"><?php echo $data['email']; ?></p>

        <div class="stat-box">
            <span class="stat-number"><?php echo $total_groups; ?></span>
            <span style="font-size: 0.8rem; color: #888;">Grup Belajar Diikuti</span>
        </div>
        
        <br>
        <a href="logout.php" style="color: #e74c3c; text-decoration: none; font-size: 0.9rem;">Keluar Aplikasi</a>
    </div>

    <div class="profile-content">
        <h3 class="section-title">✏️ Edit Informasi Diri</h3>

        <form action="actions/update_profil.php" method="POST">
            
            <div class="input-group">
                <label>Nama Tampilan</label>
                <input type="text" name="username" class="custom-input" 
                       value="<?php echo htmlspecialchars($data['username']); ?>" required>
            </div>

            <div class="form-grid">
                <div class="input-group">
                    <label>Jurusan / Prodi</label>
                    <input type="text" name="major" class="custom-input" 
                           placeholder="Contoh: Informatika" 
                           value="<?php echo htmlspecialchars($data['major'] ?? ''); ?>">
                </div>
                <div class="input-group">
                    <label>Semester</label>
                    <input type="number" name="semester" class="custom-input" 
                           placeholder="1 - 14" min="1" max="14"
                           value="<?php echo htmlspecialchars($data['semester'] ?? ''); ?>">
                </div>
            </div>

            <div class="input-group">
                <label>Bio Singkat</label>
                <textarea name="bio" class="custom-input" rows="4" 
                          placeholder="Ceritakan ketertarikan belajarmu..."><?php echo htmlspecialchars($data['bio'] ?? ''); ?></textarea>
            </div>

            <button type="submit" name="update" value="true" class="btn-save">Simpan Perubahan</button>
        </form>
    </div>

</div>

</body>
</html>
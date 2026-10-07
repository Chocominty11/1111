<?php
session_start();
include 'config/db.php';
include 'includes/header.php';

$group_id = $_GET['id'];
$my_id = $_SESSION['user_id'];

// Cek apakah yang buka halaman ini adalah PEMBUAT GRUP?
$check_admin = mysqli_query($conn, "SELECT * FROM study_groups WHERE id='$group_id' AND created_by='$my_id'");
if(mysqli_num_rows($check_admin) == 0){
    echo "<script>alert('Kamu bukan admin grup ini!'); window.location.href='index.php';</script>";
    exit;
}

// Ambil info grup buat judul
$group_info = mysqli_fetch_assoc($check_admin);

// Ambil list member yang statusnya PENDING
$query_pending = mysqli_query($conn, "SELECT m.*, u.username, u.avatar FROM group_members m 
                                      JOIN users u ON m.user_id = u.id 
                                      WHERE m.group_id='$group_id' AND m.status='pending'");
?>

<style>
    body { background-color: #FDFBF7; }
    
    .manage-container {
        max-width: 600px;
        margin: 40px auto;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        overflow: hidden; /* Supaya header tumpul ngikutin border */
        font-family: 'Poppins', sans-serif;
    }

    /* Header Hijau di Atas */
    .manage-header {
        background: linear-gradient(135deg, #88B04B 0%, #769a40 100%);
        padding: 30px 20px;
        text-align: center;
        color: white;
    }
    .manage-header h2 { margin: 0; font-size: 1.5rem; }
    .manage-header p { margin: 5px 0 0 0; opacity: 0.9; font-size: 0.9rem; }

    .manage-body { padding: 30px; }

    /* Style List Member */
    .req-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        border-bottom: 1px solid #f0f0f0;
        transition: 0.2s;
    }
    .req-card:hover { background-color: #fafafa; }
    .req-user { display: flex; align-items: center; gap: 12px; }
    .req-avatar { width: 45px; height: 45px; border-radius: 50%; object-fit: cover; }
    .req-name { font-weight: 600; color: #444; display: block; }
    
    .btn-action {
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.8rem;
        font-weight: 600;
        margin-left: 5px;
        transition: 0.2s;
    }
    .btn-acc { background: #e8f5e9; color: #2e7d32; }
    .btn-acc:hover { background: #c8e6c9; }
    .btn-rej { background: #ffebee; color: #c62828; }
    .btn-rej:hover { background: #ffcdd2; }

    /* Tombol Kembali */
    .link-back {
        display: block;
        text-align: center;
        margin-bottom: 30px;
        color: #88B04B;
        text-decoration: none;
        font-weight: 600;
    }
    .link-back:hover { text-decoration: underline; }

    /* Zona Bahaya */
    .danger-zone {
        margin-top: 20px;
        padding: 20px;
        border: 2px dashed #ffcdd2;
        background-color: #fffafb;
        border-radius: 12px;
        text-align: center;
    }
    .danger-title { color: #e74c3c; font-weight: bold; margin-bottom: 5px; display: block; }
    .danger-desc { font-size: 0.85rem; color: #888; margin-bottom: 15px; }
    
    .btn-delete-group {
        background-color: #ff5252;
        color: white;
        padding: 10px 20px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-block;
        box-shadow: 0 4px 10px rgba(255, 82, 82, 0.3);
        transition: 0.3s;
    }
    .btn-delete-group:hover {
        background-color: #ff1744;
        transform: translateY(-2px);
    }
</style>

<div class="manage-container">
    <div class="manage-header">
        <h2>🛡️ Kelola Anggota</h2>
        <p>Grup: <?php echo htmlspecialchars($group_info['group_name']); ?></p>
    </div>

    <div class="manage-body">
        
        <h4 style="color: #555; margin-bottom: 15px;">Permintaan Bergabung (<?php echo mysqli_num_rows($query_pending); ?>)</h4>

        <?php if(mysqli_num_rows($query_pending) > 0): ?>
            <div style="border: 1px solid #eee; border-radius: 10px; overflow: hidden;">
                <?php while($row = mysqli_fetch_assoc($query_pending)): ?>
                    <div class="req-card">
                        <div class="req-user">
                            <img src="<?php echo $row['avatar']; ?>" class="req-avatar">
                            <div>
                                <span class="req-name"><?php echo htmlspecialchars($row['username']); ?></span>
                                <span style="font-size: 0.8rem; color: #999;">Ingin bergabung</span>
                            </div>
                        </div>
                        <div>
                            <a href="actions/approve_member.php?id=<?php echo $row['id']; ?>&action=accept" class="btn-action btn-acc">✔ Terima</a>
                            <a href="actions/approve_member.php?id=<?php echo $row['id']; ?>&action=reject" class="btn-action btn-rej">✖ Tolak</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 30px; background: #f9f9f9; border-radius: 10px;">
                <img src="https://img.icons8.com/ios/50/cccccc/checked-user-male.png" style="opacity: 0.5;">
                <p style="color: #aaa; margin-top: 10px;">Belum ada teman yang minta bergabung.</p>
            </div>
        <?php endif; ?>

        <div class="danger-zone">
            <span class="danger-title">⚠️ Zona Bahaya</span>
            <p class="danger-desc">
                Ingin membubarkan grup ini? Semua pesan & data akan hilang permanen.
            </p>
            <a href="actions/delete_group.php?id=<?php echo $group_id; ?>" 
               onclick="return confirm('YAKIN?? Grup akan dihapus selamanya!')"
               class="btn-delete-group">
                🗑️ Bubarkan Grup
            </a>
        </div>

    </div>
    
    <a href="chat_room.php?id=<?php echo $group_id; ?>" class="link-back">&larr; Kembali ke Chat Room</a>
</div>

</body>
</html>
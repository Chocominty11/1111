<?php
session_start();
include 'config/db.php';
include 'includes/header.php';

// Cek Login
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// QUERY: Ambil grup yang diikuti user
$query = "SELECT g.*, m.joined_at, 
          (SELECT COUNT(*) FROM group_members WHERE group_id = g.id AND status='active') as total_members
          FROM study_groups g
          JOIN group_members m ON g.id = m.group_id
          WHERE m.user_id = '$user_id' AND m.status = 'active'
          ORDER BY m.joined_at DESC";

$result = mysqli_query($conn, $query);
?>

<style>
    body { background-color: #FDFBF7; }

    /* --- HERO DENGAN GAMBAR --- */
    .my-hero {
        /* Gambar Background Orang Belajar */
        background: linear-gradient(rgba(44, 62, 80, 0.8), rgba(76, 161, 175, 0.7)), 
                    url('https://images.unsplash.com/photo-1519389950473-47ba0277781c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
        background-size: cover;
        background-position: center;
        padding: 80px 20px;
        color: white;
        text-align: center;
        border-radius: 0 0 50px 50px;
        margin-bottom: 50px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    }
    .my-hero h2 { margin: 0; font-size: 2.5rem; font-weight: 800; text-shadow: 0 2px 5px rgba(0,0,0,0.3); }
    .my-hero p { opacity: 0.9; margin-top: 10px; font-size: 1.1rem; font-weight: 500; }

    /* Grid Layout */
    .container { max-width: 1100px; margin: 0 auto; padding: 0 20px; }
    .grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 30px;
    }

    /* --- CARD STYLE BARU (ADA GAMBAR) --- */
    .group-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        border: none;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .group-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    /* Wrapper Gambar Cover */
    .card-img-wrapper {
        height: 160px;
        overflow: hidden;
        position: relative;
    }

    .card-img-top {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: 0.5s;
    }
    
    .group-card:hover .card-img-top { transform: scale(1.1); } /* Efek Zoom pas hover */

    /* Badge Status (Admin/Member) nempel di gambar */
    .role-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        z-index: 2;
    }
    .role-admin { background: #FFD700; color: #5a4a00; }
    .role-member { background: #ffffff; color: #333; }

    .card-body { padding: 25px; flex: 1; display: flex; flex-direction: column; }

    .group-title {
        font-size: 1.3rem; margin: 0 0 10px 0; font-weight: 700; color: #2c3e50;
    }
    .group-desc {
        color: #666; font-size: 0.9rem; line-height: 1.6;
        margin-bottom: 20px;
        display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
    }
    
    .card-footer-info {
        margin-top: auto;
        border-top: 1px solid #eee;
        padding-top: 15px;
        display: flex; justify-content: space-between; align-items: center;
    }

    .btn-chat {
        background: #2c3e50;
        color: white;
        padding: 10px 20px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.85rem;
        transition: 0.3s;
    }
    .btn-chat:hover { background: #88B04B; box-shadow: 0 5px 15px rgba(136, 176, 75, 0.4); }

    /* Empty State */
    .empty-state {
        grid-column: 1 / -1; text-align: center; padding: 60px;
        background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
</style>

<div class="my-hero">
    <h2>Komunitas Belajarku 📖</h2>
    <p>Tempat kamu berbagi ilmu dan diskusi bareng teman.</p>
</div>

<div class="container">
    <div class="grid-container">
        
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                
                <?php 
                    // TRIK: Bikin gambar beda-beda tiap grup pakai ID sebagai "seed"
                    // Jadi grup ID 1 gambarnya A, grup ID 2 gambarnya B. Keren kan?
                    $cover_img = "https://picsum.photos/seed/" . $row['id'] . "/400/200"; 
                ?>

                <div class="group-card">
                    
                    <div class="card-img-wrapper">
                        <img src="<?php echo $cover_img; ?>" class="card-img-top" alt="Cover Group">
                        
                        <?php if($row['created_by'] == $user_id): ?>
                            <span class="role-badge role-admin">👑 Admin</span>
                        <?php else: ?>
                            <span class="role-badge role-member">Member</span>
                        <?php endif; ?>
                    </div>

                    <div class="card-body">
                        <h3 class="group-title"><?php echo htmlspecialchars($row['group_name']); ?></h3>
                        <p class="group-desc"><?php echo htmlspecialchars($row['description']); ?></p>
                        
                        <div class="card-footer-info">
                            <span style="font-size:0.8rem; color:#888;">
                                👥 <strong><?php echo $row['total_members']; ?></strong> Anggota
                            </span>
                            <a href="chat_room.php?id=<?php echo $row['id']; ?>" class="btn-chat">Buka Chat 💬</a>
                        </div>
                    </div>
                </div>

            <?php endwhile; ?>
        <?php else: ?>
            
            <div class="empty-state">
                <img src="https://img.icons8.com/clouds/200/000000/todo-list.png" alt="Empty">
                <h3 style="color: #555; margin-top: 20px;">Belum ada grup yang diikuti</h3>
                <p style="color: #999;">Gabung grup dulu yuk di halaman pencarian!</p>
                <a href="index.php" style="display:inline-block; margin-top:15px; text-decoration:none; background:#88B04B; color:white; padding: 12px 30px; border-radius: 50px; font-weight:bold;">
                    Cari Grup Sekarang &rarr;
                </a>
            </div>

        <?php endif; ?>

    </div>
</div>

<?php include 'includes/footer.php'; ?>
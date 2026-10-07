<?php
session_start();
include 'config/db.php';
include 'includes/header.php';

// Cek Login
if(!isset($_SESSION['user_id'])){
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data user
$query_user = mysqli_query($conn, "SELECT username FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($query_user);

// --- LOGIKA PENCARIAN ---
$search_query = "";
if(isset($_GET['q'])){
    $q = mysqli_real_escape_string($conn, $_GET['q']);
    $sql_add = "WHERE group_name LIKE '%$q%' OR description LIKE '%$q%'";
    $search_query = $q;
} else {
    $sql_add = "";
}

// Query Utama
$query_sql = "
    SELECT study_groups.*, 
    (SELECT COUNT(*) FROM group_members WHERE group_id = study_groups.id AND status='active') as total_member,
    (SELECT status FROM group_members WHERE group_id = study_groups.id AND user_id = '$user_id') as my_status
    FROM study_groups 
    $sql_add 
    ORDER BY created_at DESC
";

$query_groups = mysqli_query($conn, $query_sql);
?>

<style>
    body { background-color: #FDFBF7; }

    /* --- HERO SECTION --- */
    .hero-section {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
                    url('https://images.unsplash.com/photo-1523240795612-9a054b0db644?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
        background-size: cover;
        background-position: center;
        padding: 100px 20px;
        text-align: center;
        color: white;
        border-radius: 0 0 50px 50px;
        margin-bottom: 50px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .hero-section h1 { margin: 0; font-size: 3rem; font-weight: 700; text-shadow: 0 2px 4px rgba(0,0,0,0.3); }
    .hero-section p { font-size: 1.2rem; opacity: 0.9; margin-top: 10px; }
    
    /* Search Bar */
    .search-floater {
        background: white; padding: 8px; border-radius: 50px; display: inline-flex;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2); margin-top: 30px; width: 100%; max-width: 600px;
        transition: transform 0.3s;
    }
    .search-floater:focus-within { transform: scale(1.02); }
    .search-floater input { border: none; outline: none; flex: 1; padding: 15px 25px; font-size: 1rem; border-radius: 50px; }
    .search-floater button {
        background: #88B04B; color: white; border: none; padding: 12px 35px;
        border-radius: 40px; cursor: pointer; font-weight: bold; font-size: 1rem; transition: 0.3s;
    }
    .search-floater button:hover { background: #769a40; }

    /* Kategori */
    .category-pills { display: flex; justify-content: center; gap: 10px; margin-top: 20px; flex-wrap: wrap; }
    .pill {
        background: rgba(255,255,255,0.2); color: white; padding: 8px 16px;
        border-radius: 20px; font-size: 0.9rem; text-decoration: none; border: 1px solid rgba(255,255,255,0.3);
        transition: 0.3s;
    }
    .pill:hover { background: white; color: #333; }

    /* --- CARD STYLE BARU (SAMA KAYAK MY_GROUPS) --- */
    .container { max-width: 1100px; margin: 0 auto; padding: 0 20px; }
    .group-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px;
    }

    .group-card {
        background: white; border-radius: 20px; overflow: hidden; border: none;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05); transition: all 0.3s ease;
        display: flex; flex-direction: column; height: 100%;
    }
    .group-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.15); }

    .card-img-wrapper { height: 160px; overflow: hidden; position: relative; }
    .card-img-top { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
    .group-card:hover .card-img-top { transform: scale(1.1); }

    /* Badge Status di atas gambar */
    .status-badge {
        position: absolute; top: 15px; right: 15px; padding: 6px 14px;
        border-radius: 50px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2); z-index: 2;
    }
    .badge-active { background: #2f9e83; color: white; }
    .badge-pending { background: #ffc107; color: #333; }

    .card-body { padding: 25px; flex: 1; display: flex; flex-direction: column; }
    .card-title { font-size: 1.3rem; margin: 0 0 10px 0; font-weight: 700; color: #2c3e50; }
    .card-desc {
        color: #666; font-size: 0.9rem; line-height: 1.6; margin-bottom: 20px;
        display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
    }
    
    .card-footer-action {
        margin-top: auto; border-top: 1px solid #eee; padding-top: 15px;
        display: flex; justify-content: space-between; align-items: center;
    }

    /* Tombol Utama */
    .btn-action {
        padding: 10px 20px; border-radius: 50px; text-decoration: none; font-weight: 600;
        font-size: 0.85rem; transition: 0.3s; display: inline-block;
    }
    .btn-join { background: #88B04B; color: white; }
    .btn-join:hover { background: #769a40; box-shadow: 0 5px 15px rgba(136, 176, 75, 0.4); }
    
    .btn-open { background: #2f9e83; color: white; }
    .btn-open:hover { background: #25856e; box-shadow: 0 5px 15px rgba(47, 158, 131, 0.4); }
    
    .btn-wait { background: #e9ecef; color: #999; cursor: not-allowed; }
</style>

<div class="hero-section">
    <h1>Hai, <?php echo htmlspecialchars($user['username']); ?>! </h1>
    <p>Temukan teman sefrekuensi, diskusikan tugas, dan raih nilai A bersama-sama.</p>
    
    <form action="" method="GET" style="display:inline;">
        <div class="search-floater">
            <input type="text" name="q" placeholder="Cari: Pemrograman, Matematika, Bahasa..." 
                   value="<?php echo htmlspecialchars($search_query); ?>" autocomplete="off">
            <button type="submit">Cari</button>
        </div>
    </form>

    <div class="category-pills">
        <a href="?q=Pemrograman" class="pill">💻 Pemrograman</a>
        <a href="?q=Bahasa" class="pill">🇬🇧 Bahasa Inggris</a>
        <a href="?q=Matematika" class="pill">📐 Matematika</a>
        <a href="?q=Desain" class="pill">🎨 Desain Grafis</a>
        <a href="?q=Bisnis" class="pill">💼 Bisnis & Manajemen</a>
        <a href="?q=Hukum" class="pill">⚖️ Hukum</a>
        <a href="?q=Jaringan" class="pill">🌐 Jaringan Komputer</a>
        <a href="?q=Sains" class="pill">🔬 Sains & Fisika</a>
        <a href="?q=Akuntansi" class="pill">📊 Akuntansi</a>
    </div>
</div>

<div class="container">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h3 style="color: #444; margin:0; font-size: 1.5rem;">
                <?php echo ($search_query != "") ? "Hasil Pencarian: '$search_query'" : "Grup Belajar Terbaru"; ?>
            </h3>
            <p style="color:#888; margin:5px 0 0 0;">Gabung dan mulai diskusi sekarang.</p>
        </div>
        <a href="create_group.php" class="btn-create" style="background:#2c3e50; color:white; padding:10px 20px; border-radius:50px; text-decoration:none; font-weight:bold;">+ Bikin Grup</a>
    </div>
    
    <div class="group-grid">
        <?php if(mysqli_num_rows($query_groups) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($query_groups)): ?>
                
                <?php 
                    // Gambar Cover Otomatis (Sama kayak my_groups)
                    $cover_img = "https://picsum.photos/seed/" . $row['id'] . "/400/200"; 
                ?>

                <div class="group-card">
                    <div class="card-img-wrapper">
                        <img src="<?php echo $cover_img; ?>" class="card-img-top">
                        
                        <?php if($row['my_status'] == 'active'): ?>
                            <span class="status-badge badge-active">✅ Member</span>
                        <?php elseif($row['my_status'] == 'pending'): ?>
                            <span class="status-badge badge-pending">⏳ Menunggu</span>
                        <?php endif; ?>
                    </div>

                    <div class="card-body">
                        <h3 class="card-title"><?php echo htmlspecialchars($row['group_name']); ?></h3>
                        <p class="card-desc"><?php echo htmlspecialchars($row['description']); ?></p>
                        
                        <div class="card-footer-action">
                            <span style="font-size:0.8rem; color:#888;">
                                👥 <strong><?php echo $row['total_member']; ?></strong> Anggota
                            </span>
                            
                            <?php if($row['my_status'] == 'active'): ?>
                                <a href="chat_room.php?id=<?php echo $row['id']; ?>" class="btn-action btn-open">Buka Chat 💬</a>
                            <?php elseif($row['my_status'] == 'pending'): ?>
                                <button class="btn-action btn-wait">Menunggu Admin...</button>
                            <?php else: ?>
                                <a href="actions/join_group.php?id=<?php echo $row['id']; ?>" class="btn-action btn-join">✋ Minta Gabung</a>
                            <?php endif; ?>
                        </div>
                        
                        <?php if($row['my_status'] == 'active' && $row['created_by'] == $user_id): ?>
                             <a href="manage_group.php?id=<?php echo $row['id']; ?>" style="display:block; text-align:center; font-size:0.75rem; margin-top:10px; color:#999; text-decoration:none;">⚙️ Kelola Grup Ini</a>
                        <?php endif; ?>

                    </div>
                </div>

            <?php endwhile; ?>
        <?php else: ?>
            <div style="grid-column: 1/-1; text-align: center; padding: 60px; background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
                <img src="https://img.icons8.com/ios/100/cccccc/search--v1.png">
                <h3 style="color: #999; margin-top: 20px;">Yah, grup tidak ditemukan</h3>
                <p style="color: #aaa;">Coba cari kata kunci lain atau jadilah yang pertama membuat grup!</p>
                <a href="index.php" style="color: #88B04B; font-weight: bold; text-decoration: none;">Reset Pencarian</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
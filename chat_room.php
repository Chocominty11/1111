<?php 
session_start();
include 'config/db.php';

// Pastikan ada ID grup
if(!isset($_GET['id'])) { header("Location: index.php"); exit; }

$group_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Ambil Info Grup untuk Header
$query_group = mysqli_query($conn, "SELECT * FROM study_groups WHERE id = '$group_id'");
$group = mysqli_fetch_assoc($query_group);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - <?php echo htmlspecialchars($group['group_name']); ?></title>
    <link rel="stylesheet" href="assets/css/chat.css">
</head>
<body>

    <div class="chat-header">
        <a href="index.php" class="btn-back">
            &larr; Kembali
        </a>
        <div class="group-info" style="text-align: right;">
            <h3><?php echo htmlspecialchars($group['group_name']); ?></h3>
            <p>Ruang Diskusi</p>
        </div>
    </div>

    <div id="chat-box" class="chat-area">
        <p style="text-align:center; color:#ccc;">Memuat percakapan...</p>
    </div>

    <form id="chat-form" class="input-area">
        <input type="hidden" id="group_id" value="<?php echo $group_id; ?>">
        
        <input type="text" id="message" class="input-field" 
               placeholder="Ketik pesan disini..." autocomplete="off" required>
        
        <button type="submit" class="btn-send">Kirim ✈️</button>
    </form>

    <script src="assets/js/chat.js"></script>

</body>
</html>
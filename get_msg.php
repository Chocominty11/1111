<?php
session_start();
include '../config/db.php';

$group_id = $_GET['group_id'];
$my_id = $_SESSION['user_id'];

// Ambil pesan beserta foto & nama pengirimnya
$query = "SELECT m.*, u.username, u.avatar 
          FROM messages m 
          JOIN users u ON m.user_id = u.id 
          WHERE group_id = '$group_id' 
          ORDER BY sent_at ASC";

$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)) {
        
        // Cek ini pesan siapa? (Me atau Other)
        $is_me = ($row['user_id'] == $my_id) ? 'me' : 'other';
        
        // Format jam (ambil jam:menit saja)
        $time = date('H:i', strtotime($row['sent_at']));

        // Output HTML Bubble
        echo '
        <div class="message-row '.$is_me.'">
            <img src="'.$row['avatar'].'" class="avatar" alt="User">
            <div class="bubble">
                '. ($is_me == 'other' ? '<span class="sender-name">'.$row['username'].'</span>' : '') .'
                '. htmlspecialchars($row['message']) .'
                <span class="time">'.$time.'</span>
            </div>
        </div>
        ';
    }
} else {
    echo '<div style="text-align:center; color:#ccc; margin-top:20px;">Belum ada pesan. Sapa temanmu! 👋</div>';
}
?>
const chatBox = document.getElementById('chat-box');
const chatForm = document.getElementById('chat-form');
const groupId = document.getElementById('group_id').value;

// Fungsi agar chat selalu scroll ke bawah
function scrollToBottom() {
    chatBox.scrollTop = chatBox.scrollHeight;
}

// Variabel biar scroll cuma jalan kalau ada pesan baru
let isFirstLoad = true;

// 1. Fungsi Kirim Pesan
chatForm.addEventListener('submit', function(e){
    e.preventDefault();
    const messageInput = document.getElementById('message');
    const message = messageInput.value;

    if(message.trim() === "") return; // Jangan kirim kalau kosong

    fetch('actions/send_msg.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `group_id=${groupId}&message=${message}`
    })
    .then(response => response.text())
    .then(data => {
        messageInput.value = ''; // Kosongkan input
        loadChat(true); // Refresh dan paksa scroll
    });
});

// 2. Fungsi Ambil Pesan
function loadChat(forceScroll = false) {
    fetch(`actions/get_msg.php?group_id=${groupId}`)
    .then(response => response.text())
    .then(data => {
        // Cek apakah konten berubah sebelum replace (opsional, biar ga kedip)
        if(chatBox.innerHTML !== data || forceScroll || isFirstLoad) {
            chatBox.innerHTML = data;
            
            if(isFirstLoad || forceScroll){
                scrollToBottom();
                isFirstLoad = false;
            }
        }
    });
}

// Jalankan realtime
setInterval(loadChat, 1000);
// Jalankan sekali saat buka
loadChat();
<?php
session_start();
include 'config/db.php';
include 'includes/header.php'; // Navbar otomatis muncul
?>

<div class="container">
    
    <div class="form-container">
        <h2 style="text-align: center; color: var(--primary); margin-top: 0;">Buat Grup Baru 📚</h2>
        <p style="text-align: center; color: #999; margin-bottom: 30px;">
            Ajak teman-temanmu berdiskusi topik menarik.
        </p>

        <form action="actions/create_group_act.php" method="POST">
            
            <div class="form-group">
                <label>Nama Grup / Mata Kuliah</label>
                <input type="text" name="group_name" class="form-input" 
                       placeholder="Contoh: Belajar Algoritma Semester 1" required>
            </div>

            <div class="form-group">
                <label>Deskripsi Singkat</label>
                <textarea name="description" class="form-input" 
                          placeholder="Jelaskan apa yang akan dipelajari di grup ini..." required></textarea>
            </div>

            <button type="submit" class="btn-submit">🚀 Buat Grup Sekarang</button>
            
            <a href="index.php" class="link-back">&larr; Batal & Kembali ke Dashboard</a>
        </form>
    </div>

</div>

</body>
</html>
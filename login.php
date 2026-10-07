<?php
session_start();
if(isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Study Buddy</title>
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>

    <div class="auth-card">
        <h2>Login 👋</h2>
        <p style="color:#999; margin-bottom: 20px;">Selamat datang kembali!</p>
        
        <form action="actions/login_act.php" method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-input" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-input" required>
            </div>

            <button type="submit" class="btn-submit">Masuk</button>
        </form>

        <p class="link-text">
            Belum punya akun? <a href="register.php">Daftar disini</a>
        </p>
    </div>

</body>
</html>
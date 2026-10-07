<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Study Buddy</title>
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>

    <div class="auth-card">
        <h2>Buat Akun 🚀</h2>
        <p style="color:#999; margin-bottom: 20px;">Cari teman belajar sekarang.</p>
        
        <form action="actions/register_act.php" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-input" required placeholder="Contoh: Budi Santoso">
            </div>

            <div class="form-group">
                <label>Email Kampus</label>
                <input type="email" name="email" class="form-input" required placeholder="budi@univ.ac.id">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-input" required placeholder="Minimal 6 karakter">
            </div>

            <button type="submit" class="btn-submit">Daftar Sekarang</button>
        </form>

        <p class="link-text">
            Sudah punya akun? <a href="login.php">Login disini</a>
        </p>
    </div>

</body>
</html>
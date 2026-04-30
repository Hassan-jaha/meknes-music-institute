<?php
// admin/login.php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("SELECT id, password_hash FROM administrateurs WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            $_SESSION['admin_id'] = $admin['id'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = __('admin_error_id');
        }
    } else {
        $error = __('admin_error_fields');
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>" dir="<?= getLangDir() ?>">
<head>
    <meta charset="UTF-8">
    <title><?= __('admin_login_title') ?> | Institut de Musique</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 5rem auto;
            padding: 2rem;
            background: var(--color-white);
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-top: 4px solid var(--color-red-primary);
        }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: .5rem; color: var(--color-blue-primary); font-weight: 500; }
        .form-control { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        .error { color: var(--color-red-accent); margin-bottom: 1rem; text-align: center; font-weight: 500; }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h2 style="text-align: center;"><?= __('admin_login_header') ?></h2>
            <?php if ($error): ?>
                <div class="error"><?= h($error) ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username"><?= __('admin_username') ?></label>
                    <input type="text" id="username" name="username" class="form-control" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password"><?= __('admin_password') ?></label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;"><?= __('admin_login_btn') ?></button>
            </form>
            <div style="text-align: center; margin-top: 1rem;">
                <a href="../index.php"><?= __('admin_back_to_site') ?></a>
            </div>
        </div>
    </div>
</body>
</html>

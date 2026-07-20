<?php

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

if (isLoggedIn()) {
    header('Location: /index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = 'لطفاً نام کاربری و رمز عبور را وارد کنید.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['user_role'] = $user['role'];

            header('Location: /index.php');
            exit;
        } else {
            $error = 'نام کاربری یا رمز عبور اشتباه است.';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>


<div class="auth-form">
    <h2 class="page-title" style="text-align: center; border: none;">ورود</h2>

    <?php if (!empty($error)): ?>
        <div class="message message-error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/login.php">
        <div class="form-group">
            <label for="username">نام کاربری:</label>
            <input type="text" id="username" name="username"
                   value="<?php echo htmlspecialchars($username ?? ''); ?>"
                   placeholder="نام کاربری خود را وارد کنید" required>
        </div>

        <div class="form-group">
            <label for="password">رمز عبور:</label>
            <input type="password" id="password" name="password"
                   placeholder="رمز عبور خود را وارد کنید" required>
        </div>

        <button type="submit" class="btn btn-primary">ورود</button>
    </form>

    <a href="/register.php" class="form-link">حساب کاربری ندارید؟ ثبت‌نام کنید</a>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>

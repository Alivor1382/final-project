<?php

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

if (isLoggedIn()) {
    header('Location: /index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $firstName = trim($_POST['first_name'] ?? '');
    $lastName  = trim($_POST['last_name'] ?? '');
    $username  = trim($_POST['username'] ?? '');
    $password  = trim($_POST['password'] ?? '');

    if (empty($firstName) || empty($lastName) || empty($username) || empty($password)) {
        $error = 'لطفاً تمام فیلدها را پر کنید.';
    }
    elseif (strlen($password) < 6) {
        $error = 'رمز عبور باید حداقل ۶ کاراکتر باشد.';
    }
    else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $error = 'این نام کاربری قبلاً استفاده شده است.';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, username, password, role) VALUES (?, ?, ?, ?, 'user')");
            $stmt->execute([$firstName, $lastName, $username, $hashedPassword]);

            $success = 'ثبت‌نام با موفقیت انجام شد! اکنون می‌توانید وارد شوید.';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="auth-form">
    <h2 class="page-title" style="text-align: center; border: none;">ثبت‌نام</h2>

    <?php if (!empty($error)): ?>
        <div class="message message-error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="message message-success">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/register.php">
        <div class="form-group">
            <label for="first_name">نام:</label>
            <input type="text" id="first_name" name="first_name"
                   value="<?php echo htmlspecialchars($firstName ?? ''); ?>"
                   placeholder="نام خود را وارد کنید" required>
        </div>

        <div class="form-group">
            <label for="last_name">نام خانوادگی:</label>
            <input type="text" id="last_name" name="last_name"
                   value="<?php echo htmlspecialchars($lastName ?? ''); ?>"
                   placeholder="نام خانوادگی خود را وارد کنید" required>
        </div>

        <div class="form-group">
            <label for="username">نام کاربری:</label>
            <input type="text" id="username" name="username"
                   value="<?php echo htmlspecialchars($username ?? ''); ?>"
                   placeholder="یک نام کاربری انتخاب کنید" required>
        </div>

        <div class="form-group">
            <label for="password">رمز عبور:</label>
            <input type="password" id="password" name="password"
                   placeholder="حداقل ۶ کاراکتر" required>
        </div>

        <button type="submit" class="btn btn-primary">ثبت‌نام</button>
    </form>

    <a href="/login.php" class="form-link">قبلاً ثبت‌نام کرده‌اید؟ وارد شوید</a>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>

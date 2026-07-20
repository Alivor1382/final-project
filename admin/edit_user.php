<?php



require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';


requireAdmin();


$error   = '';
$success = '';

$userId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($userId <= 0) {

    header('Location: /admin/users.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {

    header('Location: /admin/users.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $firstName = trim($_POST['first_name'] ?? '');
    $lastName  = trim($_POST['last_name'] ?? '');
    $username  = trim($_POST['username'] ?? '');
    $role      = trim($_POST['role'] ?? 'user');
    $newPassword = trim($_POST['password'] ?? '');


    if (empty($firstName) || empty($lastName) || empty($username)) {
        $error = 'لطفاً نام، نام خانوادگی و نام کاربری را پر کنید.';
    }

    elseif ($username !== $user['username']) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? AND id != ?");
        $stmt->execute([$username, $userId]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $error = 'این نام کاربری قبلاً استفاده شده است.';
        }
    }

    if (empty($error)) {

        $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, username = ?, role = ? WHERE id = ?");
        $stmt->execute([$firstName, $lastName, $username, $role, $userId]);


        if (!empty($newPassword)) {
            if (strlen($newPassword) < 6) {
                $error = 'رمز عبور جدید باید حداقل ۶ کاراکتر باشد.';
            } else {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$hashedPassword, $userId]);
            }
        }

        if (empty($error)) {
            $success = 'اطلاعات کاربر با موفقیت به‌روزرسانی شد.';

            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
}


require_once __DIR__ . '/../includes/header.php';
?>


<h2 class="page-title">ویرایش کاربر: <?php echo htmlspecialchars($user['username']); ?></h2>


<a href="/admin/users.php" class="btn btn-secondary btn-small" style="margin-bottom: 20px;">بازگشت به لیست کاربران</a>

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


<div class="card">
    <form method="POST" action="/admin/edit_user.php?id=<?php echo $userId; ?>">
        <div class="form-group">
            <label for="first_name">نام:</label>
            <input type="text" id="first_name" name="first_name"
                   value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
        </div>

        <div class="form-group">
            <label for="last_name">نام خانوادگی:</label>
            <input type="text" id="last_name" name="last_name"
                   value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
        </div>

        <div class="form-group">
            <label for="username">نام کاربری:</label>
            <input type="text" id="username" name="username"
                   value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>

        <div class="form-group">
            <label for="role">نقش:</label>
            <select id="role" name="role">
                <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>کاربر عادی</option>
                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>مدیر</option>
            </select>
        </div>

        <div class="form-group">
            <label for="password">رمز عبور جدید (اختیاری):</label>
            <input type="password" id="password" name="password"
                   placeholder="فقط در صورت نیاز به تغییر پر کنید">
            <small style="color: #777; display: block; margin-top: 5px;">
                اگر نمی‌خواهید رمز عبور را تغییر دهید، این فیلد را خالی بگذارید.
            </small>
        </div>

        <button type="submit" class="btn btn-primary">ذخیره تغییرات</button>
    </form>
</div>

<?php

require_once __DIR__ . '/../includes/footer.php';
?>

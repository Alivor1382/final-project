<?php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

requireAdmin();

$success = '';
$error   = '';

$stmt = $pdo->prepare("SELECT * FROM about WHERE id = 1");
$stmt->execute();
$about = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $content = trim($_POST['content'] ?? '');

    if (empty($content)) {
        $error = 'محتوا نمی‌تواند خالی باشد.';
    } else {
        if ($about) {

            $stmt = $pdo->prepare("UPDATE about SET content = ? WHERE id = 1");
            $stmt->execute([$content]);
        } else {

            $stmt = $pdo->prepare("INSERT INTO about (id, content) VALUES (1, ?)");
            $stmt->execute([$content]);
        }

        $success = 'محتوای درباره ما با موفقیت ذخیره شد.';

        $stmt = $pdo->prepare("SELECT * FROM about WHERE id = 1");
        $stmt->execute();
        $about = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}


require_once __DIR__ . '/../includes/header.php';
?>

<h2 class="page-title">ویرایش درباره ما</h2>

<a href="/admin/index.php" class="btn btn-secondary btn-small" style="margin-bottom: 20px;">بازگشت به داشبورد</a>

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
    <h3 style="margin-bottom: 10px; color: #2c3e50;">محتوای فعلی:</h3>
    <div class="about-content">
        <?php
        if ($about) {
            echo nl2br(htmlspecialchars($about['content']));
        } else {
            echo 'محتوایی وجود ندارد.';
        }
        ?>
    </div>
</div>

<div class="card">
    <h3 style="margin-bottom: 15px; color: #2c3e50;">ویرایش محتوا:</h3>
    <form method="POST" action="/admin/edit_about.php">
        <div class="form-group">
            <label for="content">محتوای درباره ما:</label>
            <textarea id="content" name="content" rows="8"><?php
                echo htmlspecialchars($about ? $about['content'] : '');
            ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">ذخیره تغییرات</button>
    </form>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>

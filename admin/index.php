<?php



require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';


requireAdmin();


$stmt = $pdo->query("SELECT COUNT(*) FROM users");
$totalUsers = $stmt->fetchColumn();


$stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'");
$totalAdmins = $stmt->fetchColumn();

require_once __DIR__ . '/../includes/header.php';
?>


<h2 class="page-title">پنل مدیریت</h2>


<div style="display: flex; gap: 20px; margin-bottom: 30px; flex-wrap: wrap;">
    <div class="card" style="flex: 1; min-width: 200px; text-align: center;">
        <h3 style="color: #3498db; font-size: 2em;"><?php echo $totalUsers; ?></h3>
        <p>تعداد کل کاربران</p>
    </div>
    <div class="card" style="flex: 1; min-width: 200px; text-align: center;">
        <h3 style="color: #e67e22; font-size: 2em;"><?php echo $totalAdmins; ?></h3>
        <p>تعداد مدیران</p>
    </div>
</div>


<div style="display: flex; gap: 20px; flex-wrap: wrap;">
    <div class="card" style="flex: 1; min-width: 250px;">
        <h3 style="margin-bottom: 15px; color: #2c3e50;">مدیریت کاربران</h3>
        <p style="margin-bottom: 15px;">مشاهده، جستجو، ویرایش و حذف کاربران</p>
        <a href="/admin/users.php" class="btn btn-primary">مدیریت کاربران</a>
    </div>

    <div class="card" style="flex: 1; min-width: 250px;">
        <h3 style="margin-bottom: 15px; color: #2c3e50;">مدیریت محتوا</h3>
        <p style="margin-bottom: 15px;">ویرایش محتوای صفحه درباره ما</p>
        <a href="/admin/edit_about.php" class="btn btn-warning">ویرایش درباره ما</a>
    </div>
</div>

<?php

require_once __DIR__ . '/../includes/footer.php';
?>

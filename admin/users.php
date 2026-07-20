<?php
/**
 * ============================================
 *  صفحه مدیریت کاربران
 *  User Management Page
 * ============================================
 *
 *  در این صفحه مدیر می‌تواند:
 *  - لیست تمام کاربران را ببیند
 *  - کاربران را بر اساس نام، نام خانوادگی یا نام کاربری جستجو کند
 *  - کاربران را ویرایش کند
 *  - کاربران را حذف کند
 */

// فراخوانی فایل‌های کمکی
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

// بررسی دسترسی مدیر
requireAdmin();

// ============================================
//  جستجوی کاربران
// ============================================

// دریافت پارامترهای جستجو از URL
$searchFirstName = trim($_GET['first_name'] ?? '');
$searchLastName  = trim($_GET['last_name'] ?? '');
$searchUsername  = trim($_GET['username'] ?? '');

// ساخت پرسش جستجو با شرایط پویا
// ابتدا یک لیست شرایط و پارامترها می‌سازیم
$where = [];
$params = [];

// اگر نام پر شده باشد، شرط اضافه شود
if (!empty($searchFirstName)) {
    $where[] = "first_name LIKE ?";
    $params[] = '%' . $searchFirstName . '%';
}

// اگر نام خانوادگی پر شده باشد
if (!empty($searchLastName)) {
    $where[] = "last_name LIKE ?";
    $params[] = '%' . $searchLastName . '%';
}

// اگر نام کاربری پر شده باشد
if (!empty($searchUsername)) {
    $where[] = "username LIKE ?";
    $params[] = '%' . $searchUsername . '%';
}

// ساخت نهایی پرسش SQL
$sql = "SELECT * FROM users";
if (!empty($where)) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY id ASC";

// اجرای پرسش
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// فراخوانی سربرگ
require_once __DIR__ . '/../includes/header.php';
?>

<!-- صفحه مدیریت کاربران -->
<h2 class="page-title">مدیریت کاربران</h2>

<!-- لینک بازگشت به داشبورد -->
<a href="/admin/index.php" class="btn btn-secondary btn-small" style="margin-bottom: 20px;">بازگشت به داشبورد</a>

<!-- فرم جستجو -->
<div class="card">
    <h3 style="margin-bottom: 15px; color: #2c3e50;">جستجوی کاربران</h3>
    <form method="GET" action="/admin/users.php" class="search-form">
        <div class="form-group">
            <label for="first_name">نام:</label>
            <input type="text" id="first_name" name="first_name"
                   value="<?php echo htmlspecialchars($searchFirstName); ?>"
                   placeholder="جستجو بر اساس نام">
        </div>
        <div class="form-group">
            <label for="last_name">نام خانوادگی:</label>
            <input type="text" id="last_name" name="last_name"
                   value="<?php echo htmlspecialchars($searchLastName); ?>"
                   placeholder="جستجو بر اساس نام خانوادگی">
        </div>
        <div class="form-group">
            <label for="username">نام کاربری:</label>
            <input type="text" id="username" name="username"
                   value="<?php echo htmlspecialchars($searchUsername); ?>"
                   placeholder="جستجو بر اساس نام کاربری">
        </div>
        <div>
            <button type="submit" class="btn btn-primary">جستجو</button>
            <a href="/admin/users.php" class="btn btn-secondary">پاک کردن</a>
        </div>
    </form>
</div>

<!-- جدول لیست کاربران -->
<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>ردیف</th>
                <th>نام</th>
                <th>نام خانوادگی</th>
                <th>نام کاربری</th>
                <th>نقش</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
                <!-- اگر کاربری یافت نشد -->
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">کاربری یافت نشد.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($users as $index => $user): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td>
                            <?php
                            // نمایش فارسی نقش کاربر
                            if ($user['role'] === 'admin') {
                                echo '<span style="color: #e67e22; font-weight: bold;">مدیر</span>';
                            } else {
                                echo 'کاربر عادی';
                            }
                            ?>
                        </td>
                        <td>
                            <div class="actions">
                                <!-- دکمه ویرایش -->
                                <a href="/admin/edit_user.php?id=<?php echo $user['id']; ?>"
                                   class="btn btn-warning btn-small">ویرایش</a>

                                <!-- دکمه حذف (با تأیید) -->
                                <a href="/admin/delete_user.php?id=<?php echo $user['id']; ?>"
                                   class="btn btn-danger btn-small"
                                   onclick="return confirm('آیا از حذف این کاربر مطمئن هستید؟');">حذف</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<p style="color: #777; font-size: 0.9em;">تعداد نتایج: <?php echo count($users); ?></p>

<?php
// فراخوانی پابرگ
require_once __DIR__ . '/../includes/footer.php';
?>

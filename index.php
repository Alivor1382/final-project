<?php
/**
 * ============================================
 *  صفحه اصلی وب‌سایت
 *  Home Page
 * ============================================
 *
 *  این صفحه نخستین صفحه‌ای است که کاربر مشاهده می‌کند.
 *  شامل عنوان سایت، منوی ناوبری و پیام خوش‌آمدگویی است.
 */

// فراخوانی فایل‌های کمکی
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

// فراخوانی سربرگ (شامل شروع HTML، منوی ناوبری و شروع محتوا)
require_once __DIR__ . '/includes/header.php';
?>

<h2 class="page-title">خانه</h2>

<!-- پیام خوش‌آمدگویی -->
<div class="card">
    <h3 style="margin-bottom: 15px; color: #2c3e50;">پروژه ملی مهارت</h3>
    <p>
        علی وراثی پروژه نهایی درس مبتنی بر وب
    </p>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>

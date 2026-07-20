<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>1405</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<header class="header">
    <div class="container">
        <h1 class="site-title"> پروژه پایان ترم </h1>

        <nav class="nav">
            <a href="/index.php" class="nav-link">خانه</a>
            <a href="/about.php" class="nav-link">درباره ما</a>

            <?php if (isLoggedIn()): ?>
                <span class="nav-welcome">خوش آمدید، <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>

                <?php if (isAdmin()): ?>

                    <a href="/admin/index.php" class="nav-link nav-admin">پنل مدیریت</a>
                <?php endif; ?>

                <a href="/logout.php" class="nav-link">خروج</a>
            <?php else: ?>
                <a href="/login.php" class="nav-link">ورود</a>
                <a href="/register.php" class="nav-link">ثبت‌نام</a>
            <?php endif; ?>
        </nav>
    </div>
</header>


<main class="main">
    <div class="container">

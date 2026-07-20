<?php


$dbPath = __DIR__ . '/../database/database.sqlite';

$pdo = new PDO('sqlite:' . $dbPath);


$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec('PRAGMA foreign_keys = ON');

$pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        first_name TEXT NOT NULL,
        last_name TEXT NOT NULL,
        username TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        role TEXT NOT NULL DEFAULT 'user'
    )
");

$pdo->exec("
    CREATE TABLE IF NOT EXISTS about (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        content TEXT NOT NULL
    )
");

$stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE username = 'admin'");
$adminExists = $stmt->fetchColumn();

if ($adminExists == 0) {

    $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, username, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute(['مدیر', 'سیستم', 'admin', $hashedPassword, 'admin']);
}

$stmt = $pdo->query("SELECT COUNT(*) FROM about");
$aboutExists = $stmt->fetchColumn();

if ($aboutExists == 0) {
    // درج محتوای پیش‌فرض
    $defaultContent = "این وب‌سایت یک پروژه آموزشی ساده برای یادگیری PHP با استفاده از پایگاه داده SQLite است. در این پروژه با مفاهیم پایه‌ای برنامه‌نویسی وب شامل فرم‌ها، احراز هویت، مدیریت کاربران و کار با پایگاه داده آشنا خواهید شد.";

    $stmt = $pdo->prepare("INSERT INTO about (content) VALUES (?)");
    $stmt->execute([$defaultContent]);
}

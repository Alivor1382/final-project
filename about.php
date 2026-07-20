<?php

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';


$stmt = $pdo->prepare("SELECT content FROM about WHERE id = 1");
$stmt->execute();
$about = $stmt->fetch(PDO::FETCH_ASSOC);

$content = $about ? $about['content'] : 'محتوایی یافت نشد.';

require_once __DIR__ . '/includes/header.php';
?>

<h2 class="page-title">درباره ما</h2>

<div class="card">
    <p><?php echo nl2br(htmlspecialchars($content)); ?></p>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>

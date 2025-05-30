<?php
// 載入提醒資料
$reminders = include __DIR__ . '/time_table_array.php';
$valid = array_filter($reminders, fn($r)=> preg_match('/^\d{4}-\d{2}-\d{2}$/',$r['date']));
$grouped = [];
foreach($valid as $it) $grouped[$it['date']][] = $it;
ksort($grouped);

$today = date('Y-m-d');
if (isset($grouped[$today])) {
    $startDate = $today;
} else {
    foreach (array_keys($grouped) as $d) {
        if ($d > $today) { $startDate = $d; break; }
    }
}
if (!isset($startDate)) {
    $keys = array_keys($grouped);
    $startDate = $keys[0] ?? null;
}

$slides = [];
if ($startDate) {
    $dates = array_keys($grouped);
    $pos = array_search($startDate, $dates, true);
    if ($pos !== false) $slides = array_slice($dates, $pos);
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>廣告</title>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC&display=swap" rel="stylesheet">
<style>
    body {
    font-family: 'Noto Sans TC', sans-serif;
    background: #f7f8fc;
      /* 假设 header 高度约 70px，向内容区域添加内边距 */
    padding-top: 70px;
    }
    .card {
    border-radius: .75rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    /* 让 carousel-inner 区域可以滚动，不撑高卡片 */
    #reminderCarousel .carousel-inner {
    max-height: 300px;
    overflow-y: auto;
    position: relative;
    }
    /* 确保箭头永远在最上层 */
    #reminderCarousel .carousel-control-prev,
    #reminderCarousel .carousel-control-next {
    z-index: 10;
    }
    /* 微调箭头大小 & 颜色 */
    #reminderCarousel .carousel-control-prev-icon,
    #reminderCarousel .carousel-control-next-icon {
    filter: invert(100%);
    width: 1.5rem;
    height: 1.5rem;
    }
</style>
</head>
<body>
<!-- 这段包含你的共用 header，是固定在顶端的 -->
<?php include 'time_header.php'; ?>
<!-- 主内容放在这里 -->
<div class="container mt-4">
    <div class="row gx-4 align-items-start">
    <!-- 左侧 Carousel 卡片区域 -->
    <div class="col-12 col-md-4 mb-4">
        <div class="card h-100">
        <?php if ($slides): ?>
        <div id="reminderCarousel" class="carousel slide h-100" data-bs-ride="false">
            <div class="carousel-inner">
            <?php foreach ($slides as $i => $d): ?>
            <div class="carousel-item<?= $i===0?' active':'' ?>">
                <div class="p-3 bg-secondary text-white">
                <h6 class="mb-2">
                    <?= $d === $today
                        ? '【今日提醒】'
                        : '【即將提醒：'.htmlspecialchars($d).'】' ?>
                </h6>
                <ul class="small mb-0 ps-3">
                    <?php foreach ($grouped[$d] as $it): ?>
                    <li><?= htmlspecialchars("{$it['event']} {$it['time']} {$it['location']}") ?></li>
                    <?php endforeach; ?>
                </ul>
                </div>
            </div>
            <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button"
                    data-bs-target="#reminderCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button"
                    data-bs-target="#reminderCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
            </button>
        </div>
        <?php else: ?>
        <div class="p-4 text-center text-secondary">暫無提醒</div>
        <?php endif; ?>
        </div>
    </div>
<!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', ()=> {
        const el = document.getElementById('reminderCarousel');
        if (el) {
            bootstrap.Carousel.getOrCreateInstance(el, {
            interval: 3000,
            ride: 'carousel'
            });
        }
        });
    </script>
</body>
</html>
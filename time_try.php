<?php
$pageTitle = '轉系時程表';
include 'time_header.php';

// 主表格資料
$Arrays = [
    ['date'=>'2025-03-10','event'=>'音樂系申請開始','time'=>'08:00','location'=>'音樂系辦AM206'],
    ['date'=>'2025-03-14','event'=>'音樂系申請結束','time'=>'16:30','location'=>'音樂系辦AM206'],
    ['date'=>'2025-03-24','event'=>'申請開始(不包含音樂系)','time'=>'01:00','location'=>'繳至申請學系辦公室'],
    ['date'=>'2025-03-26','event'=>'申請結束(不包含音樂系)','time'=>'23:59','location'=>'繳至申請學系辦公室'],
    ['date'=>'2025-05-12','event'=>'公告結果','time'=>'12:00','location'=>'Email通知'],
];
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
<title><?= htmlspecialchars($pageTitle) ?></title>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Noto Sans TC', sans-serif; background: #f7f8fc; }
    .card { border-radius: .75rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    /* Carousel 內部可滾動，避免超高內容撐開卡片 */
    #reminderCarousel .carousel-inner {
    max-height: 200px;
    overflow-y: auto;
    }
    /* 輕微縮小箭頭按鈕，不壓到文字 */
    #reminderCarousel .carousel-control-prev-icon,
    #reminderCarousel .carousel-control-next-icon {
    filter: invert(100%);
    width: 1.5rem;
    height: 1.5rem;
    }
</style>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($pageTitle) ?></title>
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
        <!-- 右侧原本主表格区域 -->
        <div class="col-12 col-md-8 mb-4">
            <div class="card shadow-sm">
            <div class="card-body">
            <h3 class="card-title text-center text-secondary mb-4"><?= htmlspecialchars($pageTitle) ?></h3>
            <div class="table-responsive">
                <table class="table table-bordered text-center mb-0">
                <thead class="table-secondary">
                    <tr>
                    <th>活動</th><th>日期</th><th>時間</th><th>地點</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($Arrays as $row): ?>
                    <tr>
                    <td><?= htmlspecialchars($row['event'])    ?></td>
                    <td><?= htmlspecialchars($row['date'])     ?></td>
                    <td><?= htmlspecialchars($row['time'])     ?></td>
                    <td><?= htmlspecialchars($row['location']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                </table>
            </div>
            <div class="text-center mt-3">
                <a href="index.php" class="btn btn-primary">返回首頁</a>
            </div>
            </div>
        </div>
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

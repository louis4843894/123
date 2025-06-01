<?php
$pageTitle = '轉系時程表';
include 'header.php';
require_once 'config.php';   // 載入後即可使用 $pdo

// ---------------
// 2. 從 transfer_schedule 表查詢所有記錄，並存到 $schedules
try {
    $stmt = $pdo->query("
        SELECT `event`, `date`, `time`, `location`
        FROM `transfer_schedule`
        ORDER BY `date` ASC, `time` ASC
    ");
    $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // 如果查詢失敗，直接顯示錯誤並結束
    echo "讀取轉系時程失敗: " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>轉系時程表</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.4.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.4.2/dist/js/bootstrap.bundle.min.js" defer></script>
</head>
<body style="font-family: 'Noto Sans TC', sans-serif; background-color: #f7f8fc; margin: 0; padding: 2rem;">
    <div style="max-width: 700px; margin: 100px auto 0 auto; background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 0 12px rgba(0,0,0,0.1);">
        <h1 style="text-align: center; color: #333;">轉系時程表</h1>
        <table style="width: 100%; border-collapse: collapse; margin-top: 3rem;">
            <thead>
                <tr style="background-color: rgb(140, 140, 140);">
                    <th style="border: 1px solid #ccc; padding: 0.75rem; color: white;">活動</th>
                    <th style="border: 1px solid #ccc; padding: 0.75rem; color: white;">日期</th>
                    <th style="border: 1px solid #ccc; padding: 0.75rem; color: white;">時間</th>
                    <th style="border: 1px solid #ccc; padding: 0.75rem; color: white;">地點</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($schedules)): ?>
                    <?php foreach ($schedules as $schedule): ?>
                    <tr>
                        <td style="border: 1px solid #ccc; padding: 0.75rem; text-align: center;">
                            <?= htmlspecialchars($schedule['event']) ?>
                        </td>
                        <td style="border: 1px solid #ccc; padding: 0.75rem; text-align: center;">
                            <?= htmlspecialchars($schedule['date']) ?>
                        </td>
                        <td style="border: 1px solid #ccc; padding: 0.75rem; text-align: center;">
                            <?= htmlspecialchars($schedule['time']) ?>
                        </td>
                        <td style="border: 1px solid #ccc; padding: 0.75rem; text-align: center;">
                            <?= htmlspecialchars($schedule['location']) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 1rem;">目前尚無任何轉系時程</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

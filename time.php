<?php
$pageTitle = '轉系時程表';
include 'time_header.php';

$schedules = [
    ['date' => '2025-03-10', 'event' => '音樂系申請開始', 'time' => '08:00', 'location' => '音樂系辦AM206'],
    ['date' => '2025-03-14', 'event' => '音樂系申請結束', 'time' => '16:30', 'location' => '音樂系辦AM206'],
    ['date' => '2025-03-24', 'event' => '申請開始(不包含音樂系)', 'time' => '01:00', 'location' => '繳至申請學系辦公室'],
    ['date' => '2025-03-26', 'event' => '申請結束(不包含音樂系)', 'time' => '23:59', 'location' => '繳至申請學系辦公室'],
    ['date' => '2025-05-12', 'event' => '公告結果', 'time' => '12:00', 'location' => 'Email通知'],
];
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>轉系時程表</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.4.2/dist/css/bootstrap.min.css"rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.4.2/dist/js/bootstrap.bundle.min.js" defer></script>
</head>
<body style="font-family: 'Noto Sans TC', sans-serif; background-color: #f7f8fc; margin: 0; padding: 2rem;">
    <!-- ⬇️ Ini dia kotak konten utama -->
    <div style="max-width: 700px; margin: 100px auto 0 auto; background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 0 12px rgba(0,0,0,0.1);">
        <h1 style="text-align: center; color: #333;">轉系時程表</h1>
        <table style="width: 100%; border-collapse: collapse; margin-top: 3rem;">
            <thead>
                <tr style="background-color: rgb(140, 140, 140);">
                    <th style="border: 1px solid #ccc; padding: 0.75rem;">活動</th>
                    <th style="border: 1px solid #ccc; padding: 0.75rem;">日期</th>
                    <th style="border: 1px solid #ccc; padding: 0.75rem;">時間</th>
                    <th style="border: 1px solid #ccc; padding: 0.75rem;">地點</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($schedules as $schedule): ?>
                <tr>
                    <td style="border: 1px solid #ccc; padding: 0.75rem; text-align: center;"><?= htmlspecialchars($schedule['event']) ?></td>
                    <td style="border: 1px solid #ccc; padding: 0.75rem; text-align: center;"><?= htmlspecialchars($schedule['date']) ?></td>
                    <td style="border: 1px solid #ccc; padding: 0.75rem; text-align: center;"><?= htmlspecialchars($schedule['time']) ?></td>
                    <td style="border: 1px solid #ccc; padding: 0.75rem; text-align: center;"><?= htmlspecialchars($schedule['location']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button style="display: block; margin: 2rem auto 0; padding: 0.75rem 1.5rem; font-size: 1rem; background-color:rgb(91, 120, 189); color: white; border: none; border-radius: 8px; cursor: pointer;" 
            onmouseover="this.style.backgroundColor='#005fa3';"
            onmouseout="this.style.backgroundColor='#0078d7';">
            返回首頁
        </button>
    </div>

</body>
</html>
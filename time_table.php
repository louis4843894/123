<?php
$pageTitle = '各系面試筆試時程表';
include 'time_header.php';
$schedules = [
    ['date' => '由系辦聯繫告知', 'event' => '中國文學系', 'type' => '面試', 'time' => '由系辦聯繫告知', 'location' => '由系辦聯繫告知'],
    ['date' => '由系辦聯繫告知', 'event' => '歷史學系', 'type' => '面試', 'time' => '由系辦聯繫告知', 'location' => '由系辦聯繫告知'],
    ['date' => '由系辦聯繫告知', 'event' => '哲學系', 'type' => '面試', 'time' => '由系辦聯繫告知', 'location' => '由系辦聯繫告知'],
    ['date' => '由系辦聯繫告知', 'event' => '人文與社區創新學士學位學程', 'type' => '面試', 'time' => '由系辦聯繫告知', 'location' => '由系辦聯繫告知'],
    ['date' => '2025-03-17', 'event' => '音樂系', 'type' => '筆試', 'time' => '由系辦聯繫告知', 'location' => '由系辦聯繫告知'],
    ['date' => '2025-03-20', 'event' => '音樂系', 'type' => '面試', 'time' => '由系辦聯繫告知', 'location' => '由系辦聯繫告知'],
    ['date' => '2025-04-23', 'event' => '應用美術系', 'type' => '筆試', 'time' => '13:30~15:00', 'location' => '由系辦聯繫告知'],
    ['date' => '2025-04-21', 'event' => '景觀設計學系', 'type' => '面試', 'time' => '10:00', 'location' => '考試當日公告於藝術學院三樓'],
    ['date' => '由系辦聯繫告知', 'event' => '新聞傳播學系', 'type' => '面試', 'time' => '由系辦聯繫告知', 'location' => '由系辦聯繫告知'],
    ['date' => '由系辦聯繫告知', 'event' => '影像傳播學系', 'type' => '面試', 'time' => '由系辦聯繫告知', 'location' => '系辦聯繫告知'],
    ['date' => '由系辦聯繫告知', 'event' => '廣告傳播學系', 'type' => '面試', 'time' => '12:00', 'location' => '廣告系辦'],
    ['date' => '2025-04-16', 'event' => '圖書資訊學系', 'type' => '面試', 'time' => '12:30', 'location' => '文開樓LE507'],
    ['date' => '2025-04-18', 'event' => '教育領導與科技發展學士學位學程', 'type' => '面試', 'time' => '13:20', 'location' => '文開樓LE615'],
    ['date' => '由系辦聯繫告知', 'event' => '公共衛生學系', 'type' => '面試', 'time' => '由系辦聯繫告知', 'location' => '系辦聯繫告知'],
    ['date' => '由系辦聯繫告知', 'event' => '醫學系', 'type' => '面試', 'time' => '由系辦聯繫告知', 'location' => '系辦聯繫告知'],
    ['date' => '由系辦聯繫告知', 'event' => '醫學系', 'type' => '筆試', 'time' => '由系辦聯繫告知', 'location' => '系辦聯繫告知'],
    ['date' => '由系辦聯繫告知', 'event' => '護理學系', 'type' => '面試', 'time' => '由系辦聯繫告知', 'location' => '系辦聯繫告知'],
    ['date' => '2025-04-30', 'event' => '職能治療學系', 'type' => '面試', 'time' => '13:15', 'location' => '國璽樓MD847'],
    ['date' => '2025-04-14', 'event' => '臨床心理學系', 'type' => '面試', 'time' => '13:30', 'location' => '國璽樓MD570'],
    ['date' => '2025-04-16', 'event' => '呼吸治療學系', 'type' => '面試', 'time' => '13:50', 'location' => '國璽樓MD824'],
    ['date' => '2025-03-27', 'event' => '英國語文學系', 'type' => '筆試', 'time' => '12:30', 'location' => '外語學院FG308'],
    ['date' => '2025-04-16', 'event' => '英國語文學系', 'type' => '面試', 'time' => '13:40', 'location' => '系辦聯繫告知'],
    ['date' => '2025-04-14', 'event' => '法國語文學系', 'type' => '面試', 'time' => '12:35', 'location' => '外語學院LA204'],
    ['date' => '2025-04-22', 'event' => '日本語文學系', 'type' => '筆試', 'time' => '12:40-13:30', 'location' => '外語學院LA314'],
    ['date' => '2025-04-23', 'event' => '日本語文學系', 'type' => '面試', 'time' => '12:40', 'location' => '外語學院LA114'],
    ['date' => '2025-04-18', 'event' => '義大利語文學系', 'type' => '面試', 'time' => '13:00', 'location' => '外語學院LA214'],
    ['date' => '2025-03-27', 'event' => '國際溝通與科技創新學士學位學程(英文組)', 'type' => '筆試', 'time' => '12:30-13:30', 'location' => '外語學院FG308'],
    ['date' => '2025-04-16', 'event' => '國際溝通與科技創新學士學位學程(英文組)', 'type' => '面試', 'time' => '13:40', 'location' => '外語學院LA301'],
    ['date' => '2025-04-16', 'event' => '國際溝通與科技創新學士學位學程(義文組)', 'type' => '筆試', 'time' => '12:30-13:30', 'location' => '外語學院FG308'],
    ['date' => '2025-04-18', 'event' => '國際溝通與科技創新學士學位學程(義文組)', 'type' => '面試', 'time' => '12:30-13:30', 'location' => '外語學院FG308'],
    ['date' => '由系辦聯繫告知', 'event' => '織品服裝學系(織品服飾行銷組)', 'type' => '面試', 'time' => '由系辦聯繫告知', 'location' => '系辦聯繫告知'],
    ['date' => '由系辦聯繫告知', 'event' => '織品服裝學系(服飾設計組)', 'type' => '面試', 'time' => '由系辦聯繫告知', 'location' => '系辦聯繫告知'],
    ['date' => '由系辦聯繫告知', 'event' => '織品服裝學系(織品設計組)', 'type' => '面試', 'time' => '由系辦聯繫告知', 'location' => '系辦聯繫告知'],
    ['date' => '2025-04-16', 'event' => '餐旅管理學系', 'type' => '面試', 'time' => '12:10', 'location' => '民生學院HE101'],
    ['date' => '2025-04-23', 'event' => '食品科學系', 'type' => '面試', 'time' => '12:15-12:45', 'location' => '食品科研大樓EP104'],
    ['date' => '2025-04-17', 'event' => '兒童與家庭學系', 'type' => '面試', 'time' => '12:30', 'location' => '民生二館CF111'],
    ['date' => '2025-04-30', 'event' => '營養科學系', 'type' => '面試', 'time' => '01:30', 'location' => '民生學院NF151'],
    ['date' => '2025-04-23', 'event' => '法律學系', 'type' => '筆試', 'time' => '13:40-15:30', 'location' => '樹德樓LW214'],
    ['date' => '2025-04-23', 'event' => '財經法律學系', 'type' => '筆試', 'time' => '13:40-15:30', 'location' => '樹德樓LW212'],
    ['date' => '2025-04-16', 'event' => '心理學系', 'type' => '筆試', 'time' => '12:30-14:00', 'location' => '聖言樓SF237'],
    ['date' => '2025-04-30', 'event' => '心理學系', 'type' => '面試', 'time' => '12:20', 'location' => '聖言樓SF844'],
    ['date' => '由系辦聯繫告知', 'event' => '社會工作學系', 'type' => '面試', 'time' => '由系辦聯繫告知', 'location' => '系辦聯繫告知'],
    ['date' => '2025-04-16', 'event' => '經濟學系', 'type' => '面試', 'time' => '13:20', 'location' => '羅耀拉大樓SL375'],
    ['date' => '2025-04-16', 'event' => '宗教學系', 'type' => '面試', 'time' => '12:10', 'location' => '羅耀拉大樓SL340'],
    ['date' => '2025-04-23', 'event' => '金融與國際企業學系', 'type' => '面試', 'time' => '12:40 ', 'location' => '利瑪竇大樓LM201'],
    ['date' => '2025-04-23', 'event' => '企業管理學系', 'type' => '面試', 'time' => '14:00', 'location' => '系辦聯繫告知'],
    ['date' => '2025-04-17', 'event' => '會計學系', 'type' => '面試', 'time' => '12:30', 'location' => '利瑪竇大樓LM201'],
    ['date' => '2025-04-25', 'event' => '資訊管理學系', 'type' => '面試', 'time' => '12:30~13:30', 'location' => '利瑪竇大樓LM306'],
    ['date' => '2025-04-14', 'event' => '統計資訊學系', 'type' => '筆試', 'time' => '12:30~13:30', 'location' => '利瑪竇大樓LM200'],
    ['date' => '2025-04-15', 'event' => '統計資訊學系', 'type' => '面試', 'time' => '12:30~13:00', 'location' => '利瑪竇大樓LM202'],
    ['date' => '由系辦聯繫告知', 'event' => '數學系(資訊數學組)', 'type' => '面試', 'time' => '由系辦聯繫告知', 'location' => '系辦聯繫告知'],
    ['date' => '由系辦聯繫告知', 'event' => '數學系(應用數學組)', 'type' => '面試', 'time' => '由系辦聯繫告知', 'location' => '系辦聯繫告知'],
    ['date' => '2025-04-23', 'event' => '生命科學系', 'type' => '面試', 'time' => '13:00', 'location' => '耕莘樓LS111'],
    ['date' => '由系辦聯繫告知', 'event' => '物理學系(物理組)', 'type' => '面試', 'time' => '由系辦聯繫告知', 'location' => '系辦聯繫告知'],
    ['date' => '由系辦聯繫告知', 'event' => '物理學系(光電物理組)', 'type' => '面試', 'time' => '由系辦聯繫告知', 'location' => '系辦聯繫告知'],
    ['date' => '由系辦聯繫告知', 'event' => '物理學系(電子物理組)', 'type' => '面試', 'time' => '由系辦聯繫告知', 'location' => '系辦聯繫告知'],
    ['date' => '由系辦聯繫告知', 'event' => '化學系', 'type' => '面試', 'time' => '由系辦聯繫告知', 'location' => '系辦聯繫告知'],
    ['date' => '由系辦聯繫告知', 'event' => '資訊工程學系', 'type' => '面試', 'time' => '由系辦聯繫告知', 'location' => '系辦聯繫告知'],
    ['date' => '由系辦聯繫告知', 'event' => '跨領域全英語學士學位學程', 'type' => '面試', 'time' => '由系辦聯繫告知', 'location' => '系辦聯繫告知'],
];
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>各系面試筆試時程表</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC&display=swap" rel="stylesheet">
</head>
<body style="font-family: 'Noto Sans TC', sans-serif; background-color: #f7f8fc; margin: 0; padding: 2rem;">

    <!-- ⬇️ Ini dia kotak konten utama -->
    <div style="max-width: 1000px; margin: 100px auto 0 auto; background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 0 12px rgba(0,0,0,0.1);">
        <h1 style="text-align: center; color: #333;">各系面試筆試時程表</h1>
        <div style="text-align: right; margin-bottom: 1rem;">
            <input
                type="text"
                id="searchInput"
                placeholder="輸入關鍵字搜尋"
                style="padding: 0.5rem; width: 200px; border: 1px solid #ccc; border-radius: 4px;"
                onkeyup="filterTable()"
            >
        </div>
        <table id="scheduleTable" style="width: 100%; border-collapse: collapse; margin-top: 3rem;">
            <thead>
                <tr style="background-color: rgb(140, 140, 140);">
                    <th style="border: 1px solid #ccc; padding: 0.75rem;">系所</th>
                    <th style="border: 1px solid #ccc; padding: 0.75rem;">種類</th>
                    <th style="border: 1px solid #ccc; padding: 0.75rem;">日期</th>
                    <th style="border: 1px solid #ccc; padding: 0.75rem;">時間</th>
                    <th style="border: 1px solid #ccc; padding: 0.75rem;">地點</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($schedules as $schedule): ?>
                <tr>
                    <td style="border: 1px solid #ccc; padding: 0.75rem; text-align: center;"><?= htmlspecialchars($schedule['event']) ?></td>
                    <td style="border: 1px solid #ccc; padding: 0.75rem; text-align: center;"><?= htmlspecialchars($schedule['type']) ?></td>
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
    <script>
        function filterTable() {
            const input = document.getElementById('searchInput').value.trim().toLowerCase();
            const rows = document.querySelectorAll('#scheduleTable tbody tr');

            rows.forEach(row => {
                // 將每個儲存格的文字串接起來
                const text = Array.from(row.cells)
                                .map(cell => cell.textContent.toLowerCase())
                                .join(' ');
                // 包含關鍵字就顯示，否則隱藏
                row.style.display = text.includes(input) ? '' : 'none';
            });
        }
    </script>
</body>
</html>
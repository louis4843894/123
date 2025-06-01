<?php
// time_table.php or manage_exam_schedule.php
session_start();
require_once 'config.php';  // $pdo 應已初始化

$pageTitle = '各系面試筆試時程表';
include 'header.php';  // 負責 <head>、navbar、動態 <title> 等

try {
    // 「擷取所有時程、同時把 event 拆掉括號後對到 departments.name，才能取到正確的 college_name」
    $stmt = $pdo->query("
        SELECT
            es.id,
            es.`event`           AS department_name,
            es.`type`            AS schedule_type,
            es.`date`,
            es.`time`,
            es.`location`,
            COALESCE(d.`college_name`, '') AS college_name
        FROM `exam_schedule` es
        LEFT JOIN `departments` d
        ON d.`name` = es.`event`
        OR d.`name` = TRIM(
                            SUBSTRING_INDEX(
                                REPLACE(REPLACE(es.`event`, '（', '('), '）', ')'),
                                '(',
                                1
                            )
                            )
        ORDER BY
            FIELD(es.`date`, '由系辦聯繫告知') ASC,
            STR_TO_DATE(es.`date`, '%Y-%m-%d') ASC,
            es.`time` ASC
    ");
    $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class=\"alert alert-danger\">讀取各系面試／筆試時程失敗："
        . htmlspecialchars($e->getMessage())
        . "</div>";
    include 'footer.php';
    exit;
}
?>

<!-- 以下直接從 <body> 開始輸出 -->
<body style="font-family: 'Noto Sans TC', sans-serif; background-color: #f7f8fc; margin: 0; padding: 2rem;">
    <div style="max-width: 1300px; margin: 100px auto 0 auto; background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 0 12px rgba(0,0,0,0.1);">
        <h1 style="text-align: center; color: #333; margin: 0 0 1rem;">各系面試筆試時程表</h1>

        <!-- ============================ -->
        <!-- 1. 學院分類按鈕 (改為與 index.php 一致的外觀) -->
        <!-- ============================ -->
        <div class="college-filter-wrapper text-center mb-4">
            <!-- 「顯示全部」按鈕 -->
            <button 
                id="btn-college-all" 
                class="btn btn-Faculty college-filter active" 
                data-college="all"
                onclick="filterByCollege('all', this)">
                顯示全部
            </button>

            <?php
            // 先拿一次所有 departments 表裡非空的 college_name，去重複之後輸出
            try {
                $cstmt = $pdo->query("
                SELECT DISTINCT `college_name`
                FROM `departments`
                WHERE `college_name` <> ''
                ORDER BY `college_name` ASC
                ");
                $allCols = $cstmt->fetchAll(PDO::FETCH_COLUMN);
            } catch (PDOException $e) {
                $allCols = [];
            }

            foreach ($allCols as $colg):
            ?>
                <button 
                    class="btn btn-Faculty college-filter" 
                    data-college="<?= htmlspecialchars($colg) ?>"
                    onclick="filterByCollege('<?= htmlspecialchars($colg) ?>', this)">
                    <?= htmlspecialchars($colg) ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- ============================ -->
        <!-- 2. 關鍵字搜尋 -->
        <!-- ============================ -->
        <div style="text-align: right; margin-bottom: 1rem;">
            <input
                type="text"
                id="searchInput"
                placeholder="輸入關鍵字搜尋"
                style="padding: 0.5rem; width: 200px; border: 1px solid #ccc; border-radius: 4px;"
                onkeyup="filterTable()"
            >
        </div>

        <!-- ============================ -->
        <!-- 3. 時程列表表格 -->
        <!-- ============================ -->
        <table id="scheduleTable" style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
            <thead>
                <tr style="background-color: rgb(140, 140, 140); color: white;">
                    <th style="border: 1px solid #ccc; padding: 0.75rem;">系所</th>
                    <th style="border: 1px solid #ccc; padding: 0.75rem;">學院</th>
                    <th style="border: 1px solid #ccc; padding: 0.75rem;">種類</th>
                    <th style="border: 1px solid #ccc; padding: 0.75rem;">日期</th>
                    <th style="border: 1px solid #ccc; padding: 0.75rem;">時間</th>
                    <th style="border: 1px solid #ccc; padding: 0.75rem;">地點</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($schedules)): ?>
                    <?php foreach ($schedules as $row): ?>
                    <tr data-college="<?= htmlspecialchars($row['college_name']) ?>" style="border-bottom: 1px solid #ddd;">
                        <td style="border: 1px solid #ccc; padding: 0.75rem; text-align: center;">
                            <?= htmlspecialchars($row['department_name']) ?>
                        </td>
                        <td style="border: 1px solid #ccc; padding: 0.75rem; text-align: center;">
                            <?= htmlspecialchars($row['college_name']) ?>
                        </td>
                        <td style="border: 1px solid #ccc; padding: 0.75rem; text-align: center;">
                            <?= htmlspecialchars($row['schedule_type']) ?>
                        </td>
                        <td style="border: 1px solid #ccc; padding: 0.75rem; text-align: center;">
                            <?= htmlspecialchars($row['date']) ?>
                        </td>
                        <td style="border: 1px solid #ccc; padding: 0.75rem; text-align: center;">
                            <?= htmlspecialchars($row['time']) ?>
                        </td>
                        <td style="border: 1px solid #ccc; padding: 0.75rem; text-align: center;">
                            <?= htmlspecialchars($row['location']) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="border: 1px solid #ccc; padding: 1rem; text-align: center;">
                            目前尚無任何時程資料
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- ============================ -->
    <!-- 4. JavaScript：學院篩選 + 關鍵字搜尋 -->
    <!-- ============================ -->
    <script>
        // 當前選到的學院 (預設 "all")
        let currentCollege = 'all';

        // 點按任一學院按鈕時：先切換 active，再呼叫 filterTable()
        function filterByCollege(college, btn) {
            currentCollege = college;
            // 把所有按鈕的 active 樣式移除
            document.querySelectorAll('.college-filter').forEach(el => el.classList.remove('active'));
            // 再把當前這顆按鈕加上 active
            btn.classList.add('active');
            // 最後更新一次篩選
            filterTable();
        }

        // 同時依「學院」和「關鍵字」做篩選
        function filterTable() {
            const keyword = document.getElementById('searchInput').value.trim().toLowerCase();
            document.querySelectorAll('#scheduleTable tbody tr').forEach(row => {
                const rowCollege = row.dataset.college.trim();
                const matchesCollege = (currentCollege === 'all' || rowCollege === currentCollege);

                const rowText = Array.from(row.cells)
                                    .map(cell => cell.textContent.toLowerCase())
                                    .join(' ');
                const matchesKeyword = rowText.includes(keyword);

                row.style.display = (matchesCollege && matchesKeyword) ? '' : 'none';
            });
        }
    </script>
</body>

<?php include 'footer.php'; ?>

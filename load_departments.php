<?php
require_once 'config.php';

$college = isset($_GET['college']) ? $_GET['college'] : null;

if ($college) {
    $conditions = [];
    switch($college) {
        case '文學院':
            $conditions = ["department_name LIKE '%中國文學%'", "department_name LIKE '%歷史%'", "department_name LIKE '%哲學%'"];
            break;
        case '藝術學院':
            $conditions = ["department_name LIKE '%音樂%'", "department_name LIKE '%應用美術%'", "department_name LIKE '%景觀%'"];
            break;
        case '傳播學院':
            $conditions = ["department_name LIKE '%新聞%'", "department_name LIKE '%廣告%'", "department_name LIKE '%大眾傳播%'"];
            break;
        case '教育與運動學院':
            $conditions = ["department_name LIKE '%體育%'", "department_name LIKE '%教育%'"];
            break;
        case '醫學院':
            $conditions = ["department_name LIKE '%醫學%'", "department_name LIKE '%護理%'", "department_name LIKE '%公共衛生%'"];
            break;
        case '理工學院':
            $conditions = ["department_name LIKE '%數學%'", "department_name LIKE '%物理%'", "department_name LIKE '%化學%'", "department_name LIKE '%資訊%'"];
            break;
        case '外國語文學院':
            $conditions = [
                "department_name LIKE '%英國語文%'",
                "department_name LIKE '%英文%'",
                "department_name LIKE '%日本語文%'",
                "department_name LIKE '%日文%'",
                "department_name LIKE '%德語%'",
                "department_name LIKE '%德文%'",
                "department_name LIKE '%法國語文%'",
                "department_name LIKE '%法文%'",
                "department_name LIKE '%西班牙語文%'",
                "department_name LIKE '%義大利語文%'",
                "department_name LIKE '%外國語文%'",
                "department_name LIKE '%應用外語%'"
            ];
            break;
        case '民生學院':
            $conditions = ["department_name LIKE '%織品%'", "department_name LIKE '%食品%'", "department_name LIKE '%營養%'"];
            break;
        case '法律學院':
            $conditions = ["department_name LIKE '%法律%'", "department_name LIKE '%財經法律%'"];
            break;
        case '社會科學院':
            $conditions = ["department_name LIKE '%社會%'", "department_name LIKE '%心理%'", "department_name LIKE '%社工%'"];
            break;
        case '管理學院':
            $conditions = ["department_name LIKE '%企管%'", "department_name LIKE '%會計%'", "department_name LIKE '%統計%'", "department_name LIKE '%金融%'"];
            break;
    }
    
    if (!empty($conditions)) {
        try {
            $sql = "SELECT d.department_name, dept.intro_summary as department_intro 
                    FROM DepartmentTransfer d
                    LEFT JOIN departments dept ON d.department_name = dept.name
                    WHERE " . implode(" OR ", $conditions) . " 
                    ORDER BY d.department_name";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
        } catch (PDOException $e) {
            // 如果發生錯誤，使用原始查詢
            $sql = "SELECT department_name, '' as department_intro 
                    FROM DepartmentTransfer 
                    WHERE " . implode(" OR ", $conditions) . " 
                    ORDER BY department_name";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
        }
    }
} else {
    try {
        $stmt = $pdo->query("
            SELECT d.department_name, dept.intro_summary as department_intro 
            FROM DepartmentTransfer d
            LEFT JOIN departments dept ON d.department_name = dept.name
            ORDER BY d.department_name");
    } catch (PDOException $e) {
        // 如果發生錯誤，使用原始查詢
        $stmt = $pdo->query("
            SELECT department_name, '' as department_intro 
            FROM DepartmentTransfer 
            ORDER BY department_name");
    }
}

$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<tbody id="department-table-body">
    <?php if (empty($departments)): ?>
        <tr>
            <td colspan="3" class="text-center py-4">
                <i class="bi bi-info-circle text-muted"></i> 沒有找到符合的系所
            </td>
        </tr>
    <?php else: ?>
        <?php foreach ($departments as $dept): ?>
        <tr>
            <td class="text-dark">
                <?= htmlspecialchars($dept['department_name']) ?>
            </td>
            <td>
                <p class="mb-0 text-muted">
                    <?php 
                    $intro = isset($dept['department_intro']) ? $dept['department_intro'] : '暫無簡介';
                    echo nl2br(htmlspecialchars(mb_strimwidth($intro, 0, 150, '...'))); 
                    ?>
                </p>
            </td>
            <td>
                <div class="d-flex gap-2 justify-content-start ps-5">
                    <a href="department_detail.php?name=<?= urlencode($dept['department_name']) ?>" 
                       class="btn-more w-40 text-center py-2"
                       onclick="recordView('<?= htmlspecialchars($dept['department_name']) ?>')">
                        點我詳情
                    </a>
                    <button class="btn-add w-40 text-center py-2 toggle-compare-btn" 
                            data-dept="<?= htmlspecialchars($dept['department_name']) ?>"
                            data-action="add">
                        加入比較
                    </button>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</tbody> 
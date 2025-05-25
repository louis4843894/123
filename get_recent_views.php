<?php
session_start();
require_once 'config.php';
header('Content-Type: text/html; charset=utf-8');

if (!isset($_SESSION['user_id'])) {
    echo '<li class="nav-item"><span class="nav-link text-muted py-1">請先登入</span></li>';
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT DISTINCT page_id as department_name, viewed_at 
        FROM browse_history 
        WHERE user_id = :user_id 
        AND page_type = 'department' 
        ORDER BY viewed_at DESC 
        LIMIT 5
    ");
    
    $stmt->execute([':user_id' => $_SESSION['user_id']]);
    $recentDepts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($recentDepts)) {
        echo '<li class="nav-item"><span class="nav-link text-muted py-1">暫無瀏覽記錄</span></li>';
    } else {
        foreach ($recentDepts as $dept) {
            echo '<li class="nav-item">
                    <a href="department_detail.php?name=' . urlencode($dept['department_name']) . '" 
                       class="nav-link text-dark py-1"
                       onclick="recordDeptView(\'' . htmlspecialchars($dept['department_name'], ENT_QUOTES) . '\', this); return false;">
                        ▸ ' . htmlspecialchars($dept['department_name']) . '
                    </a>
                  </li>';
        }
        echo '<li class="nav-item mt-2">
                <a href="#" class="nav-link d-flex align-items-center text-secondary py-1" id="showAllHistory" style="text-decoration: none; font-size: 0.9rem;">
                    <i class="bi bi-chevron-down"></i><span class="ms-1">查看更多瀏覽記錄</span>
                </a>
              </li>';
    }
} catch (PDOException $e) {
    error_log("Error in get_recent_views.php: " . $e->getMessage());
    echo '<li class="nav-item"><span class="nav-link text-muted py-1">載入瀏覽記錄時發生錯誤</span></li>';
} 
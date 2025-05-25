<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] <= 0) {
    echo '<li class="nav-item"><span class="nav-link text-muted py-1">請先登入</span></li>';
    exit;
}

$recentDepts = getRecentViewedDepartments(5);

if (empty($recentDepts)) {
    echo '<li class="nav-item"><span class="nav-link text-muted py-1">暫無瀏覽記錄</span></li>';
} else {
    foreach ($recentDepts as $dept) {
        echo '<li class="nav-item">';
        echo '<a href="department_detail.php?name=' . urlencode($dept['department_name']) . '" ';
        echo 'class="nav-link text-dark py-1" ';
        echo 'onclick="recordView(\'' . htmlspecialchars($dept['department_name'], ENT_QUOTES) . '\')">';
        echo '▸ ' . htmlspecialchars($dept['department_name']);
        echo '</a>';
        echo '</li>';
    }
    
    echo '<li class="nav-item mt-2">';
    echo '<a href="#" class="nav-link text-primary" id="showAllHistory">';
    echo '<small>查看更多瀏覽記錄 <i class="bi bi-chevron-down"></i></small>';
    echo '</a>';
    echo '</li>';
} 
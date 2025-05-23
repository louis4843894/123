<?php
session_start();

// 如果沒有登入，導回 login.php，並附上錯誤訊息
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = '請先登入才能使用此功能';
    header("Location: login.php");
    exit();
}

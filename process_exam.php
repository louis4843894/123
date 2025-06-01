<?php
session_start();
require_once 'config.php';

// 僅允許管理員使用
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => '無權限']);
    exit;
}

// 1. AJAX GET: 取得單筆（action=get&id=XXX），回傳 JSON
if (isset($_GET['action']) && $_GET['action'] === 'get' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    try {
        $stmt = $pdo->prepare("
            SELECT id, `date`, `event`, `type`, `time`, `location`
            FROM exam_schedule
            WHERE id = ?
            LIMIT 1
        ");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            echo json_encode(['success' => true, 'data' => $row]);
        } else {
            echo json_encode(['success' => false, 'message' => '找不到該筆資料']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => '資料庫錯誤：' . $e->getMessage()]);
    }
    exit;
}

// 2. 處理 POST：action=add|edit|delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    try {
        if ($_POST['action'] === 'add') {
            $stmt = $pdo->prepare("
                INSERT INTO exam_schedule
                    (`date`, `event`, `type`, `time`, `location`)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $_POST['date'],
                $_POST['event'],   // 這裡的欄位叫 event
                $_POST['type'],
                $_POST['time'],
                $_POST['location']
            ]);
            $_SESSION['success_message'] = '新增時程成功';

        } elseif ($_POST['action'] === 'edit') {
            $stmt = $pdo->prepare("
                UPDATE exam_schedule
                SET 
                    `date`     = ?,
                    `event`    = ?,    -- 更新「系所名稱」的欄位 event
                    `type`     = ?,
                    `time`     = ?,
                    `location` = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $_POST['date'],
                $_POST['event'],
                $_POST['type'],
                $_POST['time'],
                $_POST['location'],
                $_POST['id']
            ]);
            $_SESSION['success_message'] = '更新時程成功';

        } elseif ($_POST['action'] === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM exam_schedule WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $_SESSION['success_message'] = '刪除時程成功';
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = '操作失敗：' . $e->getMessage();
    }

    header('Location: manage_exam_schedule.php');
    exit;
}

// 如果不是支援的請求，回傳錯誤
echo json_encode(['success' => false, 'message' => '不支援的請求']);
exit;

<?php
session_start();
require_once 'config.php';

// 僅允許管理員使用
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => '無權限']);
    exit;
}

// 1) 處理 AJAX GET?action=get&id=XXX → 回傳 JSON
if (isset($_GET['action']) && $_GET['action'] === 'get' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    try {
        $stmt = $pdo->prepare("
            SELECT id, `date`, `event`, `time`, `location`
            FROM transfer_schedule
            WHERE id = ?
            LIMIT 1
        ");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            echo json_encode(['success' => true, 'data' => $row]);
        } else {
            echo json_encode(['success' => false, 'message' => '找不到對應的時程']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => '資料庫錯誤：' . $e->getMessage()]);
    }
    exit;
}

// 2) 處理表單 POST：add / edit / delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    try {
        if ($_POST['action'] === 'add') {
            $stmt = $pdo->prepare("
                INSERT INTO transfer_schedule (`date`, `event`, `time`, `location`)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $_POST['date'],
                $_POST['event'],
                $_POST['time'],
                $_POST['location']
            ]);
            $_SESSION['success_message'] = '新增成功';

        } elseif ($_POST['action'] === 'edit') {
            $stmt = $pdo->prepare("
                UPDATE transfer_schedule
                SET `date` = ?, `event` = ?, `time` = ?, `location` = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $_POST['date'],
                $_POST['event'],
                $_POST['time'],
                $_POST['location'],
                $_POST['id']
            ]);
            $_SESSION['success_message'] = '更新成功';

        } elseif ($_POST['action'] === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM transfer_schedule WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $_SESSION['success_message'] = '刪除成功';
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = '操作失敗：' . $e->getMessage();
    }

    header('Location: manage_transfer_schedule.php');
    exit;
}

// 如果到這裡，表示既不是 GET?action=get，也不是正確的 POST → 回傳錯誤
echo json_encode(['success' => false, 'message' => '不支援的請求']);
exit;

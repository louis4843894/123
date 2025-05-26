<?php
require_once 'config.php';

// 獲取維護訊息
try {
    $stmt = $pdo->query("SELECT maintenance_message FROM system_settings WHERE id = 1");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $maintenance_message = $result ? $result['maintenance_message'] : '系統維護中，請稍後再試。';
} catch (PDOException $e) {
    $maintenance_message = '系統維護中，請稍後再試。';
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>系統維護中</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .maintenance-container {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            max-width: 600px;
            width: 90%;
        }
        .maintenance-icon {
            font-size: 4rem;
            color: #dc3545;
            margin-bottom: 1rem;
        }
        .maintenance-title {
            font-size: 2rem;
            color: #343a40;
            margin-bottom: 1rem;
        }
        .maintenance-message {
            color: #6c757d;
            margin-bottom: 2rem;
        }
        .login-link {
            color: #0d6efd;
            text-decoration: none;
        }
        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <i class="bi bi-tools maintenance-icon"></i>
        <h1 class="maintenance-title">系統維護中</h1>
        <p class="maintenance-message">
            <?php echo htmlspecialchars($maintenance_message); ?>
        </p>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <p>
                <a href="login.php" class="login-link">管理員登入</a>
            </p>
        <?php endif; ?>
    </div>
</body>
</html> 
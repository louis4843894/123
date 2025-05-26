<?php
session_start();
$pageTitle = '系統維護中';
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - 轉系資訊平台</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .maintenance-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }
        .maintenance-card {
            max-width: 600px;
            width: 90%;
            padding: 2rem;
            text-align: center;
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .maintenance-icon {
            font-size: 4rem;
            color: #ffc107;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="maintenance-card">
            <i class="bi bi-tools maintenance-icon"></i>
            <h1 class="mb-4">系統維護中</h1>
            <p class="lead mb-4">
                <?php 
                echo isset($_SESSION['maintenance_message']) 
                    ? htmlspecialchars($_SESSION['maintenance_message']) 
                    : '系統正在進行維護，請稍後再試。';
                ?>
            </p>
            <p class="text-muted">
                我們正在努力改善系統，以提供更好的服務。
            </p>
        </div>
    </div>
</body>
</html> 
<?php $pageTitle = $pageTitle ?? '轉系系統'; ?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - 轉系資訊平台</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Microsoft JhengHei', Arial, sans-serif;
        }

        .navbar {
            background: linear-gradient(90deg, rgb(168, 170, 173) 0%, rgb(114, 115, 116) 100%);
            padding: 10px 20px;
            font-weight: bold;
        }

        .navbar a {
            color: white;
        }

        .navbar a:hover {
            color: rgb(212, 215, 217);
        }

        .card {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .card-header {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            background-color: rgb(104, 128, 151);
            color: white
        }

        .info-item {
            margin-bottom: 1rem;
        }

        .info-label {
            font-weight: 600;
            color: #6c757d;
        }

        .compare-badge {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 1000;
        }

        .btn-add {
            padding: 0.5rem 1.5rem;
            background-color: rgb(87, 148, 100);
            text-decoration: none;
            color: white;
            border: none !important;
            outline: none !important;
            border-radius: 0.5rem;
        }

        .btn-add:hover {
            color: white;
            background-color: rgb(100, 170, 115);
        }

        .btn-remove {
            padding: 0.5rem 1.5rem;
            background-color: rgb(203, 82, 66);
            text-decoration: none;
            color: white;
            border: none !important;
            outline: none !important;
            border-radius: 0.5rem;
        }

        .btn-remove:hover {
            color: white;
            background-color: rgb(207, 109, 96);
        }

        .btn-go {
            padding: 0.5rem 1rem;
            background-color: rgb(75, 100, 158);
            text-decoration: none;
            color: white;
            border: none !important;
            outline: none !important;
            border-radius: 0.5rem;
        }

        .btn-go:hover {
            color: white;
            background-color: rgb(91, 120, 189);
        }

        .table th {
            background-color: rgb(223, 225, 228);
            font-weight: 600;
            position: sticky;
            top: 0;
        }

        .best-option {
            background-color: #d4edda;
        }

        .table-responsive {
            max-height: 80vh;
            overflow-y: auto;
            border-radius: 1.5rem;
        }

        .remove-btn {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            z-index: 1;
        }

        .department-header {
            position: relative;
        }

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <!-- 導航欄 -->
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left"></i> 返回首頁
            </a>
            <div class="d-flex">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="dropdown">
                        <button class="btn btn-outline-light dropdown-toggle" type="button" id="accountDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            帳號管理
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                            <li><a class="dropdown-item" href="account_settings.php">修改資料</a></li>
                            <li><a class="dropdown-item" href="logout.php">登出</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-light me-2">登入</a>
                    <a href="register.php" class="btn btn-outline-light">註冊</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
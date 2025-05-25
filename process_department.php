<?php
session_start();
require_once 'config.php';

// 檢查是否為管理員
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

// 處理POST請求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add':
            // 新增系所
            try {
                $pdo->beginTransaction();

                // 新增系所基本資料
                $stmt = $pdo->prepare("
                    INSERT INTO departments (
                        name, 
                        college_name, 
                        url, 
                        intro, 
                        careers
                    ) VALUES (
                        :name, 
                        :college_name,
                        :url, 
                        :intro, 
                        :careers
                    )
                ");
                
                $stmt->execute([
                    'name' => $_POST['name'],
                    'college_name' => $_POST['college_name'],
                    'url' => $_POST['url'],
                    'intro' => $_POST['intro'],
                    'careers' => $_POST['careers']
                ]);

                // 新增轉系相關資料
                $stmt = $pdo->prepare("
                    INSERT INTO DepartmentTransfer (
                        department_name,
                        year_2_enrollment, 
                        year_3_enrollment, 
                        year_4_enrollment,
                        exam_subjects, 
                        data_review_ratio
                    ) VALUES (
                        :department_name,
                        :year_2_enrollment, 
                        :year_3_enrollment, 
                        :year_4_enrollment,
                        :exam_subjects, 
                        :data_review_ratio
                    )
                ");
                
                $stmt->execute([
                    'department_name' => $_POST['name'],
                    'year_2_enrollment' => intval($_POST['year_2_enrollment']),
                    'year_3_enrollment' => intval($_POST['year_3_enrollment']),
                    'year_4_enrollment' => intval($_POST['year_4_enrollment']),
                    'exam_subjects' => $_POST['exam_subjects'],
                    'data_review_ratio' => $_POST['data_review_ratio']
                ]);

                $pdo->commit();
                $_SESSION['success_message'] = "系所新增成功！";
            } catch (PDOException $e) {
                $pdo->rollBack();
                $_SESSION['error_message'] = "系所新增失敗：" . $e->getMessage();
            }
            break;
            
        case 'edit':
            // 編輯系所
            try {
                $pdo->beginTransaction();

                // 更新系所基本資料
                $stmt = $pdo->prepare("
                    UPDATE departments 
                    SET name = :name,
                        college_name = :college_name,
                        url = :url,
                        intro = :intro,
                        careers = :careers
                    WHERE id = :id
                ");
                
                $stmt->execute([
                    'id' => $_POST['id'],
                    'name' => $_POST['name'],
                    'college_name' => $_POST['college_name'],
                    'url' => $_POST['url'],
                    'intro' => $_POST['intro'],
                    'careers' => $_POST['careers']
                ]);

                // 更新轉系相關資料
                $stmt = $pdo->prepare("
                    UPDATE DepartmentTransfer 
                    SET year_2_enrollment = :year_2_enrollment,
                        year_3_enrollment = :year_3_enrollment,
                        year_4_enrollment = :year_4_enrollment,
                        exam_subjects = :exam_subjects,
                        data_review_ratio = :data_review_ratio
                    WHERE department_name = :department_name
                ");
                
                $stmt->execute([
                    'department_name' => $_POST['name'],
                    'year_2_enrollment' => intval($_POST['year_2_enrollment']),
                    'year_3_enrollment' => intval($_POST['year_3_enrollment']),
                    'year_4_enrollment' => intval($_POST['year_4_enrollment']),
                    'exam_subjects' => $_POST['exam_subjects'],
                    'data_review_ratio' => $_POST['data_review_ratio']
                ]);

                $pdo->commit();
                $_SESSION['success_message'] = "系所更新成功！";
            } catch (PDOException $e) {
                $pdo->rollBack();
                $_SESSION['error_message'] = "系所更新失敗：" . $e->getMessage();
            }
            break;
            
        case 'delete':
            // 刪除系所
            try {
                $pdo->beginTransaction();

                // 先獲取系所名稱
                $stmt = $pdo->prepare("SELECT name FROM departments WHERE id = :id");
                $stmt->execute(['id' => $_POST['id']]);
                $department_name = $stmt->fetchColumn();

                // 先刪除轉系相關資料
                $stmt = $pdo->prepare("DELETE FROM DepartmentTransfer WHERE department_name = :department_name");
                $stmt->execute(['department_name' => $department_name]);

                // 再刪除系所基本資料
                $stmt = $pdo->prepare("DELETE FROM departments WHERE id = :id");
                $stmt->execute(['id' => $_POST['id']]);
                
                $pdo->commit();
                $_SESSION['success_message'] = "系所刪除成功！";
            } catch (PDOException $e) {
                $pdo->rollBack();
                $_SESSION['error_message'] = "系所刪除失敗：" . $e->getMessage();
            }
            break;
            
        default:
            $_SESSION['error_message'] = "無效的操作！";
            break;
    }
    
    header('Location: department_manage.php');
    exit;
}

// 處理AJAX請求
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    header('Content-Type: application/json');
    
    switch ($_GET['action']) {
        case 'get_department':
            // 獲取系所資料
            if (isset($_GET['id'])) {
                try {
                    $stmt = $pdo->prepare("
                        SELECT 
                            d.*,
                            dt.year_2_enrollment,
                            dt.year_3_enrollment,
                            dt.year_4_enrollment,
                            dt.exam_subjects,
                            dt.data_review_ratio
                        FROM departments d
                        LEFT JOIN DepartmentTransfer dt ON d.name = dt.department_name
                        WHERE d.id = :id
                    ");
                    $stmt->execute(['id' => $_GET['id']]);
                    $department = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($department) {
                        echo json_encode(['success' => true, 'data' => $department]);
                    } else {
                        echo json_encode(['success' => false, 'message' => '找不到該系所']);
                    }
                } catch (PDOException $e) {
                    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => '缺少系所ID']);
            }
            break;
            
        case 'get_requirements':
            // 獲取轉系要求
            if (isset($_GET['id'])) {
                try {
                    $stmt = $pdo->prepare("SELECT requirements FROM departments WHERE id = :id");
                    $stmt->execute(['id' => $_GET['id']]);
                    $requirements = $stmt->fetchColumn();
                    
                    if ($requirements !== false) {
                        echo json_encode(['success' => true, 'data' => $requirements]);
                    } else {
                        echo json_encode(['success' => false, 'message' => '找不到該系所的轉系要求']);
                    }
                } catch (PDOException $e) {
                    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => '缺少系所ID']);
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => '無效的操作']);
            break;
    }
    exit;
}

// 如果不是POST或GET請求，重定向到系所管理頁面
header('Location: department_manage.php');
exit; 
-- 創建轉系資料表
CREATE TABLE IF NOT EXISTS departmenttransfer (
    id INT AUTO_INCREMENT PRIMARY KEY,
    department_id INT NOT NULL,
    year_2_enrollment INT DEFAULT 0,
    year_3_enrollment INT DEFAULT 0,
    year_4_enrollment INT DEFAULT 0,
    exam_subjects TEXT,
    data_review_ratio VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 
-- 檢查是否存在 departments 表格，如果不存在則創建
CREATE TABLE IF NOT EXISTS departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    college VARCHAR(50) NOT NULL,
    degree_system VARCHAR(20) NOT NULL,
    quota INT NOT NULL,
    requirements TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 檢查並添加缺少的欄位
ALTER TABLE departments
ADD COLUMN IF NOT EXISTS college VARCHAR(50) NOT NULL AFTER name,
ADD COLUMN IF NOT EXISTS degree_system VARCHAR(20) NOT NULL AFTER college,
ADD COLUMN IF NOT EXISTS quota INT NOT NULL AFTER degree_system,
ADD COLUMN IF NOT EXISTS requirements TEXT AFTER quota; 
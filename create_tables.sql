-- 建立 departments 表格（作為主表）
CREATE TABLE IF NOT EXISTS `departments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL UNIQUE,
    `intro_summary` TEXT,
    `careers` TEXT NOT NULL DEFAULT '',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 建立 DepartmentTransfer 表格（參照 departments）
CREATE TABLE IF NOT EXISTS `DepartmentTransfer` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `department_name` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`department_name`) REFERENCES `departments`(`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 建立 department_details 表格（參照 departments）
CREATE TABLE IF NOT EXISTS `department_details` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `department_name` VARCHAR(255) NOT NULL,
    `course_features` TEXT,
    `future_development` TEXT,
    `faculty` TEXT,
    `transfer_requirements` TEXT,
    `phone` VARCHAR(50),
    `email` VARCHAR(255),
    `address` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`department_name`) REFERENCES `departments`(`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; 
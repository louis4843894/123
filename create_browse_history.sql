-- 先刪除舊表（如果存在）
DROP TABLE IF EXISTS `browse_history`;

-- 建立瀏覽歷史表
CREATE TABLE `browse_history` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `page_type` VARCHAR(50) NOT NULL,
    `page_id` VARCHAR(255) NOT NULL,
    `page_title` VARCHAR(255) NOT NULL,
    `viewed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_user_viewed` (`user_id`, `viewed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; 
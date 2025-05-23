USE fju;

-- 文章標籤表
CREATE TABLE IF NOT EXISTS discussion_tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

-- 討論區文章表
CREATE TABLE IF NOT EXISTS discussion_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    department_name VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    author_id INT NOT NULL,
    author_type ENUM('student', 'admin') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status ENUM('active', 'closed') DEFAULT 'active',
    FOREIGN KEY (department_name) REFERENCES DepartmentTransfer(department_name),
    FOREIGN KEY (author_id) REFERENCES users(id)
);

-- 討論區回覆表
CREATE TABLE IF NOT EXISTS discussion_replies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    content TEXT NOT NULL,
    author_id INT NOT NULL,
    author_type ENUM('student', 'admin') NOT NULL,
    parent_id INT DEFAULT NULL,
    level INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES discussion_posts(id),
    FOREIGN KEY (author_id) REFERENCES users(id),
    FOREIGN KEY (parent_id) REFERENCES discussion_replies(id) ON DELETE CASCADE
);

-- 文章-標籤關聯表
CREATE TABLE IF NOT EXISTS post_tags (
    post_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (post_id, tag_id),
    FOREIGN KEY (post_id) REFERENCES discussion_posts(id),
    FOREIGN KEY (tag_id) REFERENCES discussion_tags(id)
);

-- 插入預設標籤
INSERT INTO discussion_tags (name) VALUES 
('轉系經驗'),
('考試準備'),
('面試技巧'),
('學分抵免'); 
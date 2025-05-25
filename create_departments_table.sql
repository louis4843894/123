CREATE TABLE IF NOT EXISTS departments (
    name VARCHAR(255) PRIMARY KEY,
    introduction TEXT,
    features TEXT,
    career TEXT,
    requirements TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
); 
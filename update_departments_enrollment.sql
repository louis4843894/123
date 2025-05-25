-- 添加招生名額欄位
ALTER TABLE departments
ADD COLUMN year_2_enrollment INT DEFAULT 0,
ADD COLUMN year_3_enrollment INT DEFAULT 0,
ADD COLUMN year_4_enrollment INT DEFAULT 0; 
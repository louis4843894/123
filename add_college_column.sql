-- 添加 college_name 欄位
ALTER TABLE DepartmentTransfer ADD COLUMN college_name VARCHAR(50) DEFAULT NULL;

-- 更新現有資料的學院名稱
UPDATE DepartmentTransfer 
SET college_name = 
    CASE 
        WHEN department_name LIKE '%中國文學%' OR department_name LIKE '%歷史%' OR department_name LIKE '%哲學%' THEN '文學院'
        WHEN department_name LIKE '%音樂%' OR department_name LIKE '%應用美術%' OR department_name LIKE '%景觀%' THEN '藝術學院'
        WHEN department_name LIKE '%新聞%' OR department_name LIKE '%廣告%' OR department_name LIKE '%大眾傳播%' THEN '傳播學院'
        WHEN department_name LIKE '%體育%' OR department_name LIKE '%教育%' THEN '教育與運動學院'
        WHEN department_name LIKE '%醫學%' OR department_name LIKE '%護理%' OR department_name LIKE '%公共衛生%' THEN '醫學院'
        WHEN department_name LIKE '%數學%' OR department_name LIKE '%物理%' OR department_name LIKE '%化學%' OR department_name LIKE '%資訊%' THEN '理工學院'
        WHEN department_name LIKE '%英文%' OR department_name LIKE '%日文%' OR department_name LIKE '%德文%' OR department_name LIKE '%法文%' THEN '外國語文學院'
        WHEN department_name LIKE '%織品%' OR department_name LIKE '%食品%' OR department_name LIKE '%營養%' THEN '民生學院'
        WHEN department_name LIKE '%法律%' OR department_name LIKE '%財經法律%' THEN '法律學院'
        WHEN department_name LIKE '%社會%' OR department_name LIKE '%心理%' OR department_name LIKE '%社工%' THEN '社會科學院'
        WHEN department_name LIKE '%企管%' OR department_name LIKE '%會計%' OR department_name LIKE '%統計%' OR department_name LIKE '%金融%' THEN '管理學院'
        ELSE '其他'
    END; 
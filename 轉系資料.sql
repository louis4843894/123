CREATE DATABASE IF NOT EXISTS University;
USE University;

DROP TABLE IF EXISTS DepartmentTransfer;
CREATE TABLE DepartmentTransfer (
    department_name VARCHAR(255) NOT NULL,
    year_2_enrollment INT,
    year_3_enrollment INT,
    year_4_enrollment INT,
    exam_subjects TEXT,
    data_review_ratio VARCHAR(255),
    PRIMARY KEY(department_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO DepartmentTransfer 
(department_name, year_2_enrollment, year_3_enrollment, year_4_enrollment, exam_subjects, data_review_ratio)
VALUES
('中國文學系',5, 5, NULL, '口試；歷年成績單',NULL),
('歷史學系',10, 5, NULL, '口試',NULL),
('哲學系',10, 5, 3, '口試',NULL),
('人文與社區創新學士學位學程', 5, NULL, NULL, '口試',NULL),
('音樂學系',3, NULL, NULL, '和聲學／聽寫／視唱（主修術科）',NULL),
('應用美術學系',4, NULL, NULL, '基礎素描（術科100%）',NULL),
('景觀設計學系',5, NULL, NULL, '口試（100%）',NULL),
('新聞傳播學系',3, NULL, NULL, '口試',NULL),
('影像傳播學系',3, NULL, NULL, '口試70%；資料審查30%',NULL),
('廣告傳播學系', 4, NULL, NULL, '口試',NULL),
('圖書資訊學系', 5, 5, NULL, '口試',NULL),
('體育學系（體育學組）', 3, 3, 3, '資料審查（100%）',NULL),
('體育學系（運動競技組）', 3, 3, 3, '資料審查（100%）',NULL),
('體育學系（運動健康管理組）', 3, 3, 3, '資料審查（100%）',NULL),
('教育領導與科技發展學士學位學程', 7, 7, NULL, '口試60%；資料審查40%',NULL),
('公共衛生學系', 2, 2, NULL, '口試','成績單、自傳 (含轉系動機)、讀書計畫'),
('醫學系', 2, NULL, NULL, '普通化學／普通生物／英文，筆試成績須達本系招生試務委員會審定之最低標準分數始得參加面試','歷年成績單(含名次)、自傳 (含讀書計畫、大學期間之社會服務證明及其他有利審查資料)。(書面審查請於醫學系網頁下載表格填寫)'),
('護理學系', 5, NULL, NULL, '口試40%；資料審查60%','1.歷年成績單（含國文及英文成績）2.讀書計畫'),
('職能治療學系', 5, NULL, NULL, '口試（含轉系動機與生涯規劃或英文測驗）','1.成績單 2.自傳'),
('臨床心理學系', 4, NULL, NULL, '口試','1.歷年成績單 (含名次) 2.自傳 (含轉系動機)'),
('呼吸治療學系', 2, NULL, NULL, '口試40%；資料審查60%','1.歷年成績單 (含名次) 2.自傳（含讀書計畫、大學期間之社會服務證明及其他有利審查資料)'),
('英國語文學系', 3, NULL, NULL, '英文作文／英語面試',NULL),
('法國語文學系', 3, NULL, NULL, '口試',NULL),
('西班牙語文學系', 3, NULL, NULL, '西語短文閱讀與寫作／口試',NULL),
('日本語文學系', 6, NULL, NULL, '基礎日文30%／口試70%',NULL),
('德語語文學系', 11, NULL, NULL, '資料審查（100%）',NULL),
('義大利語文學系', 8, NULL, NULL, '面試',NULL),
('國際溝通與科技創新學士學位學程', 5, NULL, NULL, '英文筆試／口試',NULL),
('織品服裝學系（服飾行銷組）', 6, NULL, NULL, '口試',NULL),
('織品服裝學系（服飾設計組）', 3, NULL, NULL, '口試',NULL),
('織品服裝學系（織品設計組）', 3, NULL, NULL, '口試',NULL),
('餐旅管理學系', 6, NULL, NULL, '口試60%；資料審查40%',NULL),
('食品科學系', 9, NULL, NULL, '口試','1.歷年成績單(含名次) 2.食科系轉系生專用申請表'),
('兒童與家庭學系', 3, NULL, NULL, '口試','1.學生個人資料表。(請於兒家系網頁中下載)2.成績單(含成績百分比)'),
('營養科學系', 6, NULL, NULL, '口試','1.歷年成績單2.營養系轉系生專用資料表'),
('法律學系', 6, NULL, NULL, '民法總則／成績單','歷年成績單正本(含名次)'),
('財經法律學系', 3, NULL, NULL, '民法總則／成績單','歷年成績單正本(含名次)'),
('心理學系', 3, NULL, NULL, '普通心理學50%／口試50%',NULL),
('社會工作學系', 3, NULL, NULL, '口試50%／資料審查50%',NULL),
('天主教研修學士學位學程', 2, 2, NULL, '資料審查（100%）',NULL),
('社會學系', 3, NULL, NULL, '資料審查（100%）','含排名之歷年成績單正本、自傳、讀書計畫'),
('經濟學系', 5, NULL, NULL, '口試70%／資料審查30%',NULL),
('宗教學系', 6, 6, NULL, '口試','自傳、讀書計畫'),
('金融與國際企業學系', 6, NULL, NULL, '口試50%／資料審查50%',NULL),
('企業管理學系', 10, NULL, NULL, '口試50%／資料審查50%',NULL),
('會計學系', 5, NULL, NULL, '口試70%／資料審查30%',NULL),
('資訊管理學系', 5, NULL, NULL, '口試50%／資料審查50%',NULL),
('統計資訊學系', 5, NULL, NULL, '統計學40%／口試60%',NULL),
('醫學資訊與創新應用學士學位學程', 10, 1, NULL, NULL,'1.附歷年成績單正本一份。2.附自傳一份 (含申請動機)。3.附讀 書計畫一份。'),
('電機工程學系', 5, 2, NULL, NULL,'1.歷年成績單(含班排名表)正本一份。2.自傳讀書計畫(詳述大一至今求學經驗、轉系動機、學習規劃及未來生涯規劃)。'),
('數學系資訊數學組', 11, 11, NULL, '口試','歷年成績單(含排名)'),
('數學系應用數學組', 10, 9, NULL, '口試','歷年成績單(含排名)'),
('生命科學系', 21, 19, 19, '口試','歷年成績單(含排名)'),
('物理學系物理組', 0, 9, 7, '口試','歷年成績單(含排名)'),
('物理學系光電物理組', 12, 12, 11, '口試','歷年成績單(含排名)'),
('物理學系電子物理組', 2, NULL,NULL, '口試','歷年成績單(含排名)'),
('化學系', 6, NULL,NULL, '口試','歷年成績單(含排名)'),
('資訊工程學系', 6, 6, NULL, '口試','歷年成績單(含排名)'),
('人工智慧與資訊安全學士學位學程', 10, 1, NULL, NULL,'1.附歷年成績單正本一份。2.附自傳一份(含申請動機)。3.附讀書計畫一份'),
('跨領域全英語學士學位學程', 10, 10, NULL, '口試','1.英文自傳(不超過A4一頁)2.英文讀書計畫3.英文版歷年成績單4.英文能力證明(例如語言檢定成績、學期報告或其它有利審查之文件)');

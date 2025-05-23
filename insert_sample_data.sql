-- 首先插入系所基本資料到 departments 表格（主表）
INSERT INTO `departments` (`name`, `intro_summary`, `careers`) VALUES 
('中國文學系', '本系致力於培養具有深厚中國文學素養的人才，課程涵蓋古典文學、現代文學、語言學等領域。', '教師、作家、編輯、文化工作者'),
('歷史學系', '培養學生具備歷史研究與史料分析能力，著重中西方歷史發展脈絡的探討。', '歷史研究員、教師、文化工作者、博物館從業人員'),
('資訊工程學系', '培育具備軟硬體專業知識的資訊人才，課程包含程式設計、資料結構、演算法等。', '軟體工程師、系統分析師、資料科學家、研發工程師'),
('心理學系', '結合理論與實務，培養具備心理學專業知識與研究能力的人才。', '臨床心理師、諮商心理師、人力資源專員、研究人員'),
('企業管理學系', '培養具備現代管理知識與實務經驗的專業經理人，著重理論與實務的結合。', '企業經理人、行銷企劃、人力資源管理師、創業家'),
('新聞傳播學系', '培育具有新聞專業素養與傳播技能的人才，重視實務操作與理論結合。', '記者、編輯、主播、媒體企劃');

-- 然後插入到 DepartmentTransfer 表格
INSERT INTO `DepartmentTransfer` (`department_name`) VALUES 
('中國文學系'),
('歷史學系'),
('資訊工程學系'),
('心理學系'),
('企業管理學系'),
('新聞傳播學系');

-- 最後插入系所詳細資料到 department_details 表格
INSERT INTO `department_details` 
(`department_name`, `course_features`, `future_development`, `faculty`, `transfer_requirements`, `phone`, `email`, `address`) VALUES 
('中國文學系', 
'1. 古典文學研究\n2. 現代文學創作\n3. 文字學與聲韻學\n4. 詩詞寫作實務', 
'1. 教育工作者\n2. 文字工作者\n3. 出版社編輯\n4. 文化創意產業', 
'本系師資陣容堅強，專任教師皆具博士學位，研究領域涵蓋各個面向。', 
'1. 學業成績平均75分以上\n2. 國文成績80分以上\n3. 須附讀書計畫', 
'02-2905-2000', 'chinese@mail.fju.edu.tw', '新北市新莊區中正路510號');

INSERT INTO `department_details` 
(`department_name`, `course_features`, `future_development`, `faculty`, `transfer_requirements`, `phone`, `email`, `address`) VALUES 
('資訊工程學系', 
'1. 程式設計與軟體工程\n2. 資料結構與演算法\n3. 人工智慧與機器學習\n4. 資料庫系統', 
'1. 軟體工程師\n2. 系統分析師\n3. 資料科學家\n4. 研究開發人員', 
'本系教師均具有國內外知名大學博士學位，專長領域完整。', 
'1. 學業成績平均70分以上\n2. 數學及英文成績75分以上\n3. 需繳交轉系動機說明', 
'02-2905-3000', 'csie@mail.fju.edu.tw', '新北市新莊區中正路510號');

INSERT INTO `department_details` 
(`department_name`, `course_features`, `future_development`, `faculty`, `transfer_requirements`, `phone`, `email`, `address`) VALUES 
('心理學系', 
'1. 實驗心理學\n2. 發展心理學\n3. 臨床心理學\n4. 社會心理學', 
'1. 臨床心理師\n2. 諮商心理師\n3. 人力資源專員\n4. 研究人員', 
'本系教師專長多元，包含臨床、諮商、實驗等各領域。', 
'1. 學業成績平均75分以上\n2. 心理學概論成績80分以上\n3. 需參加面試', 
'02-2905-4000', 'psy@mail.fju.edu.tw', '新北市新莊區中正路510號'); 
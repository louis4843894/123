-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2025 年 05 月 21 日 12:36
-- 伺服器版本： 10.4.28-MariaDB
-- PHP 版本： 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `fju`
--

-- --------------------------------------------------------

--
-- 資料表結構 `DepartmentRemarksSplit`
--

CREATE TABLE `DepartmentRemarksSplit` (
  `department_name` varchar(255) NOT NULL,
  `remark1` text DEFAULT NULL,
  `remark2` text DEFAULT NULL,
  `remark3` text DEFAULT NULL,
  `remark4` text DEFAULT NULL,
  `remark5` text DEFAULT NULL,
  `remark6` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `DepartmentRemarksSplit`
--

INSERT INTO `DepartmentRemarksSplit` (`department_name`, `remark1`, `remark2`, `remark3`, `remark4`, `remark5`, `remark6`) VALUES
('中國文學系', '1.原系所學業成績平均需達 75 分以上', '2.需修習過中國文學相關課程', '3.需通過面試', NULL, NULL, NULL),
('歷史學系', '1.原系所學業成績平均需達 75 分以上', '2.需修習過歷史相關課程', '3.需通過面試', NULL, NULL, NULL),
('哲學系', '1.原系所學業成績平均需達 75 分以上', '2.需修習過哲學相關課程', '3.需通過面試', NULL, NULL, NULL),
('人文與社區創新學士學位學程', '1.原系所學業成績平均需達 75 分以上', '2.需修習過人文相關課程', '3.需通過面試', NULL, NULL, NULL),
('音樂學系', '1.原系所學業成績平均需達 75 分以上', '2.需修習過音樂相關課程', '3.需通過面試', NULL, NULL, NULL),
('應用美術學系', '1.原系所學業成績平均需達 75 分以上', '2.需修習過美術相關課程', '3.需通過面試', NULL, NULL, NULL),
('景觀設計學系', '1.原系所學業成績平均需達 75 分以上', '2.需修習過設計相關課程', '3.需通過面試', NULL, NULL, NULL),
('新聞傳播學系', '1.原系所學業成績平均需達 75 分以上', '2.需修習過傳播相關課程', '3.需通過面試', NULL, NULL, NULL),
('影像傳播學系', '1.原系所學業成績平均需達 75 分以上', '2.需修習過傳播相關課程', '3.需通過面試', NULL, NULL, NULL),
('廣告傳播學系', '1.原系所學業成績平均需達 75 分以上', '2.需修習過傳播相關課程', '3.需通過面試', NULL, NULL, NULL),
('圖書資訊學系', '1.原系所學業成績平均需達 75 分以上', '2.需修習過資訊相關課程', '3.需通過面試', NULL, NULL, NULL),
('體育學系（體育學組）', '1.原系所學業成績平均需達 75 分以上', '2.需修習過體育相關課程', '3.需通過面試', NULL, NULL, NULL),
('體育學系（運動競技組）', '1.原系所學業成績平均需達 75 分以上', '2.需修習過體育相關課程', '3.需通過面試', NULL, NULL, NULL),
('體育學系（運動健康管理組）', '1.原系所學業成績平均需達 75 分以上', '2.需修習過體育相關課程', '3.需通過面試', NULL, NULL, NULL),
('教育領導與科技發展學士學位學程', '1.原系所學業成績平均需達 75 分以上', '2.需修習過教育相關課程', '3.需通過面試', NULL, NULL, NULL),
('公共衛生學系', '1.原系所學業成績平均需達 75 分以上', '2.需修習過公共衛生相關課程', '3.需通過面試', NULL, NULL, NULL),
('醫學系', '1.原系所學業成績平均需達 75 分以上', '2.需修習過醫學相關課程', '3.需通過面試', NULL, NULL, NULL),
('護理學系', '1.原系所學業成績平均需達 75 分以上', '2.需修習過護理相關課程', '3.需通過面試', NULL, NULL, NULL),
('職能治療學系', '1.原系所學業成績平均需達 75 分以上', '2.需修習過職能治療相關課程', '3.需通過面試', NULL, NULL, NULL),
('臨床心理學系', '1.原系所學業成績平均需達 75 分以上', '2.需修習過心理學相關課程', '3.需通過面試', NULL, NULL, NULL),
('呼吸治療學系', '1.原系所學業成績平均需達 75 分以上', '2.需修習過呼吸治療相關課程', '3.需通過面試', NULL, NULL, NULL),
('英國語文學系', '1.原系所學業成績平均需達 75 分以上', '2.需修習過英文相關課程', '3.需通過面試', NULL, NULL, NULL),
('法國語文學系', '1.原系所學業成績平均需達 75 分以上', '2.需修習過法文相關課程', '3.需通過面試', NULL, NULL, NULL),
('西班牙語文學系', '1.原系所學業成績平均需達 75 分以上', '2.需修習過西班牙文相關課程', '3.需通過面試', NULL, NULL, NULL),
('日本語文學系', '1.原系所學業成績平均需達 75 分以上', '2.需修習過日文相關課程', '3.需通過面試', NULL, NULL, NULL),
('德語語文學系', '1.原系所學業成績平均需達 75 分以上', '2.需修習過德文相關課程', '3.需通過面試', NULL, NULL, NULL),
('義大利語文學系', '1.原系所學業成績平均需達 75 分以上', '2.需修習過義大利文相關課程', '3.需通過面試', NULL, NULL, NULL),
('國際溝通與科技創新學士學位學程', '1.原系所學業成績平均需達 75 分以上', '2.需修習過國際溝通相關課程', '3.需通過面試', NULL, NULL, NULL),
('織品服裝學系（服飾行銷組）', '1.原系所學業成績平均需達 75 分以上', '2.需修習過服飾行銷相關課程', '3.需通過面試', NULL, NULL, NULL),
('織品服裝學系（服飾設計組）', '1.原系所學業成績平均需達 75 分以上', '2.需修習過服飾設計相關課程', '3.需通過面試', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 資料表結構 `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `intro` text NOT NULL,
  `careers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`careers`)),
  `url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `departments`
--

INSERT INTO `departments` (`id`, `name`, `intro`, `careers`, `url`) VALUES
(1, '中國文學系', '以古典與現當代漢語文學為主體，結合文獻學、比較文學、文本批評與數位人文方法，培養學生批判思考與創作能力。課程涵蓋先秦文獻、唐詩宋詞、小說戲劇及當代文學專題研究。', '[\"中 小學及大專院校教師\", \"出版／編輯、書評人\", \"文化創意產業（策展、公關）\", \"新聞傳播、數位內容編輯\", \"研究所深造、數位人文專案\"]', 'https://chinese.fju.edu.tw/'),
(2, '中國文學系', '以古典與現當代漢語文學為主體，結合文獻學、比較文學、文本批評與數位人文方法，培養學生批判思考與創作能力。課程涵蓋先秦文獻、唐詩宋詞、小說戲劇及當代文學專題研究。', '[\"中 小學及大專院校教師\", \"出版／編輯、書評人\", \"文化創意產業（策展、公關）\", \"新聞傳播、數位內容編輯\", \"研究所深造、數位人文專案\"]', 'https://chinese.fju.edu.tw/'),
(3, '歷史學系', '聚焦東亞與世界史研究，從史料詮釋、歷史理論到田野調查，訓練學生理解社會變遷脈絡與文化互動。強調跨領域方法，並提供博物館學、文化遺產保存等實務課程。', '[\"博物館／檔案館典藏管理\", \"公部門文史研究、文化資產維護\", \"教育教職（中 小學／大學）\", \"文化觀光產業、導覽解說\", \"研究所、博士班深造\"]', 'https://www.history.fju.edu.tw/'),
(4, '哲學系', '探究存在論、認識論、倫理學與政治哲學等核心議題，並結合邏輯學與科際人文，培養學生批判反思與論證能力。課程涵蓋西洋、中國及比較哲學傳統。', '[\"學術研究、哲學／倫理諮商\", \"公共政策分析、智庫研究\", \"法律事務、企業倫理顧問\", \"出版、文化創意規劃\", \"研究所深造\"]', 'https://www.philosophy.fju.edu.tw/'),
(5, '人文與社區創新學士學位學程', '跨領域整合人文思維、社區營造與創新實踐，結合媒體、設計與社會行動，強調從在地觀察到方案執行全流程訓練。課程包含社區調查、文化策展與社會創新工作坊。', '[\"社區發展專案規劃師\", \"NGO／NPO 專案管理\", \"文化創意產業策展、行銷\", \"公共參與與政策推廣\", \"社區總體營造顧問\"]', 'https://hci.ourpower.com.tw/'),
(6, '音樂學系', '結合西洋音樂史、樂理、作曲與表演技術，並提供聲樂與各項器樂專業訓練。強調樂團合作與跨域表演實踐。', '[\"演奏家、樂團職員\", \"音樂教師、音樂治療師\", \"作曲／編曲、製作人\", \"藝術行政、文化推廣\", \"錄音／音響工程\"]', 'https://www.music.fju.edu.tw/'),
(7, '應用美術學系', '涵蓋平面設計、插畫、動畫、數位視覺與金工實作，強調創意思考與商業專案整合。提供實習與業界合作機會。', '[\"平面／網頁／動態設計師\", \"插畫家、動畫師\", \"藝術指導、視覺企劃\", \"產品／包裝設計\", \"自主創業、工作室\"]', 'https://www.aart.fju.edu.tw/'),
(8, '景觀設計學系', '結合生態、環境與人文，培養公共空間、庭園及城市綠地規劃能力。強調實地調查、3D 模擬與跨域協作。', '[\"景觀設計師／規劃師\", \"都市／環境顧問\", \"公部門都市計畫人員\", \"園藝造景、休憩場域管理\", \"跨域綠能與永續發展顧問\"]', 'https://www.landscape.fju.edu.tw/'),
(9, '新聞傳播學系', '訓練新聞採訪、編輯、播報與新媒體運用，並融合媒體倫理、公共議題與數位分析。', '[\"記者、編輯、主播\", \"媒體企劃、公關／危機管理\", \"數位內容策略師\", \"政府／NGO 傳播官\", \"自媒體經營者\"]', 'https://www.jcs.tw/'),
(10, '影像傳播學系', '教授電影、攝影、影像後製與新媒體敘事技術，強調腳本創作與跨平台發行。', '[\"導演、攝影師、剪輯師\", \"劇本／製片、影視剪接\", \"多媒體藝術家、VR／AR 內容創作\", \"廣告影像製作\", \"教學與研究\"]', 'https://www.commarts.fju.edu.tw/'),
(11, '廣告傳播學系', '聚焦廣告策略、公關與整合行銷傳播，結合理論與實務專案，並強調數據驅動的創意發想。', '[\"廣告／公關公司策略規劃\", \"品牌經理、行銷企劃\", \"媒體採購、社群經營\", \"企業形象顧問\", \"數據分析與洞察師\"]', 'https://www.adpr.fju.edu.tw/'),
(12, '圖書資訊學系', '結合圖書館學、資訊組織、檔案治理與知識管理，培育數位典藏、資料分析與資訊檢索專才。', '[\"圖書館員、檔案管理師\", \"數位資源管理專員\", \"知識管理／資訊顧問\", \"資料分析師、檢索工程師\", \"學術出版與編輯\"]', 'https://web.lins.fju.edu.tw/'),
(13, '體育學系（體育學組）', '以體育教學理論與運動科學為核心，涵蓋運動生物力學、運動心理與體適能評估。', '[\"中 小學體育教師\", \"健康促進／體適能教練\", \"運動科學研究助理\", \"健身房／社區體育推廣人員\"]', 'https://www.phed.fju.edu.tw/'),
(14, '體育學系（運動競技組）', '專注高階運動技能訓練、競賽策略與選手科學化管理，並輔以傷害防護與恢復。', '[\"職業／國家隊教練\", \"運動員經理人\", \"運動表現分析師\", \"運動賽事企劃\"]', 'https://www.phed.fju.edu.tw/'),
(15, '體育學系（運動健康管理組）', '整合運動處方、健康促進與長期照護，培養社區與商業健康服務規劃能力。', '[\"運動健康管理師\", \"健康促進中心企劃\", \"長照機構運動處方師\", \"運動器材行銷與顧問\"]', 'https://www.phed.fju.edu.tw/'),
(16, '教育領導與科技發展學士學位學程', '結合教育政策、領導學與教學科技，訓練數位學習平台開發、校務管理與教學設計能力。', '[\"校務行政、教育政策分析\", \"數位教材／教學系統開發\", \"企業／政府培訓設計師\", \"教學顧問、師資培育人員\"]', 'https://www.eltd.fju.edu.tw/'),
(17, '公共衛生學系', '涵蓋流行病學、生物統計、環境與職業衛生、健康政策與行為科學，並強調跨領域防疫與健康促進。', '[\"公衛官員、CRO／藥廠研究\", \"環保／職業衛生技師\", \"健康促進與教育專員\", \"長照／社區健康管理\", \"碩博士深造\"]', 'https://www.medph.fju.edu.tw/'),
(18, '醫學系', '六年制臨床與基礎醫學教育，包含解剖、生理、病理等核心課程，並在附設教學醫院完成綜合臨床實習。', '[\"住院醫師訓練（各科專科）\", \"臨床專科醫師\", \"醫院管理與醫務策劃\", \"醫學研究、教學\"]', 'https://www.med.fju.edu.tw/'),
(19, '護理學系', '結合理論與臨床實務，教授護理評估、護理流程與社區健康照護技術。', '[\"臨床護理師（各專科／ICU／急診）\", \"社區衛教師、長照護理師\", \"護理管理（護理長／督導）\", \"護理教育與研究\"]', 'https://www.nursing.fju.edu.tw/'),
(20, '職能治療學系', '以活動分析、職能介入與復健理論為基礎，配合兒童、精神與身心障礙者的功能恢復實務。', '[\"職能治療師（醫院、復健中心、早療）\", \"輔具中心／社福機構\", \"特教團隊成員\", \"研究與教學\"]', 'https://www.ot.fju.edu.tw/'),
(21, '臨床心理學系', '結合心理評估、心理治療理論與臨床實習，覆蓋個體與團體諮商技術及診斷評估。', '[\"臨床／諮商心理師（醫院、診所、學校）\", \"心理評估與輔導顧問\", \"UX / 人資或市場研究\", \"學術研究與教學\"]', 'https://www.cpsy.fju.edu.tw/');

-- --------------------------------------------------------

--
-- 資料表結構 `DepartmentTransfer`
--

CREATE TABLE `DepartmentTransfer` (
  `department_name` varchar(255) NOT NULL,
  `year_2_enrollment` int(11) DEFAULT NULL,
  `year_3_enrollment` int(11) DEFAULT NULL,
  `year_4_enrollment` int(11) DEFAULT NULL,
  `exam_subjects` text DEFAULT NULL,
  `data_review_ratio` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `DepartmentTransfer`
--

INSERT INTO `DepartmentTransfer` (`department_name`, `year_2_enrollment`, `year_3_enrollment`, `year_4_enrollment`, `exam_subjects`, `data_review_ratio`) VALUES
('中國文學系', 5, 5, NULL, '口試；歷年成績單', NULL),
('人工智慧與資訊安全學士學位學程', 10, 1, NULL, NULL, '1.附歷年成績單正本一份。2.附自傳一份(含申請動機)。3.附讀書計畫一份'),
('人文與社區創新學士學位學程', 5, NULL, NULL, '口試', NULL),
('企業管理學系', 10, NULL, NULL, '口試50%／資料審查50%', NULL),
('兒童與家庭學系', 3, NULL, NULL, '口試', '1.學生個人資料表。(請於兒家系網頁中下載)2.成績單(含成績百分比)'),
('公共衛生學系', 2, 2, NULL, '口試', '成績單、自傳 (含轉系動機)、讀書計畫'),
('化學系', 6, NULL, NULL, '口試', '歷年成績單(含排名)'),
('呼吸治療學系', 2, NULL, NULL, '口試40%；資料審查60%', '1.歷年成績單 (含名次) 2.自傳（含讀書計畫、大學期間之社會服務證明及其他有利審查資料)'),
('哲學系', 10, 5, 3, '口試', NULL),
('國際溝通與科技創新學士學位學程', 5, NULL, NULL, '英文筆試／口試', NULL),
('圖書資訊學系', 5, 5, NULL, '口試', NULL),
('天主教研修學士學位學程', 2, 2, NULL, '資料審查（100%）', NULL),
('宗教學系', 6, 6, NULL, '口試', '自傳、讀書計畫'),
('廣告傳播學系', 4, NULL, NULL, '口試', NULL),
('影像傳播學系', 3, NULL, NULL, '口試70%；資料審查30%', NULL),
('德語語文學系', 11, NULL, NULL, '資料審查（100%）', NULL),
('心理學系', 3, NULL, NULL, '普通心理學50%／口試50%', NULL),
('應用美術學系', 4, NULL, NULL, '基礎素描（術科100%）', NULL),
('教育領導與科技發展學士學位學程', 7, 7, NULL, '口試60%；資料審查40%', NULL),
('數學系應用數學組', 10, 9, NULL, '口試', '歷年成績單(含排名)'),
('數學系資訊數學組', 11, 11, NULL, '口試', '歷年成績單(含排名)'),
('新聞傳播學系', 3, NULL, NULL, '口試', NULL),
('日本語文學系', 6, NULL, NULL, '基礎日文30%／口試70%', NULL),
('景觀設計學系', 5, NULL, NULL, '口試（100%）', NULL),
('會計學系', 5, NULL, NULL, '口試70%／資料審查30%', NULL),
('歷史學系', 10, 5, NULL, '口試', NULL),
('法國語文學系', 3, NULL, NULL, '口試', NULL),
('法律學系', 6, NULL, NULL, '民法總則／成績單', '歷年成績單正本(含名次)'),
('營養科學系', 6, NULL, NULL, '口試', '1.歷年成績單2.營養系轉系生專用資料表'),
('物理學系光電物理組', 12, 12, 11, '口試', '歷年成績單(含排名)'),
('物理學系物理組', 0, 9, 7, '口試', '歷年成績單(含排名)'),
('物理學系電子物理組', 2, NULL, NULL, '口試', '歷年成績單(含排名)'),
('生命科學系', 21, 19, 19, '口試', '歷年成績單(含排名)'),
('社會學系', 3, NULL, NULL, '資料審查（100%）', '含排名之歷年成績單正本、自傳、讀書計畫'),
('社會工作學系', 3, NULL, NULL, '口試50%／資料審查50%', NULL),
('統計資訊學系', 5, NULL, NULL, '統計學40%／口試60%', NULL),
('經濟學系', 5, NULL, NULL, '口試70%／資料審查30%', NULL),
('織品服裝學系（服飾行銷組）', 6, NULL, NULL, '口試', NULL),
('織品服裝學系（服飾設計組）', 3, NULL, NULL, '口試', NULL),
('織品服裝學系（織品設計組）', 3, NULL, NULL, '口試', NULL),
('義大利語文學系', 8, NULL, NULL, '面試', NULL),
('職能治療學系', 5, NULL, NULL, '口試（含轉系動機與生涯規劃或英文測驗）', '1.成績單 2.自傳'),
('臨床心理學系', 4, NULL, NULL, '口試', '1.歷年成績單 (含名次) 2.自傳 (含轉系動機)'),
('英國語文學系', 3, NULL, NULL, '英文作文／英語面試', NULL),
('西班牙語文學系', 3, NULL, NULL, '西語短文閱讀與寫作／口試', NULL),
('護理學系', 5, NULL, NULL, '口試40%；資料審查60%', '1.歷年成績單（含國文及英文成績）2.讀書計畫'),
('財經法律學系', 3, NULL, NULL, '民法總則／成績單', '歷年成績單正本(含名次)'),
('資訊工程學系', 6, 6, NULL, '口試', '歷年成績單(含排名)'),
('資訊管理學系', 5, NULL, NULL, '口試50%／資料審查50%', NULL),
('跨領域全英語學士學位學程', 10, 10, NULL, '口試', '1.英文自傳(不超過A4一頁)2.英文讀書計畫3.英文版歷年成績單4.英文能力證明(例如語言檢定成績、學期報告或其它有利審查之文件)'),
('醫學系', 2, NULL, NULL, '普通化學／普通生物／英文，筆試成績須達本系招生試務委員會審定之最低標準分數始得參加面試', '歷年成績單(含名次)、自傳 (含讀書計畫、大學期間之社會服務證明及其他有利審查資料)。(書面審查請於醫學系網頁下載表格填寫)'),
('醫學資訊與創新應用學士學位學程', 10, 1, NULL, NULL, '1.附歷年成績單正本一份。2.附自傳一份 (含申請動機)。3.附讀書計畫一份。'),
('金融與國際企業學系', 6, NULL, NULL, '口試50%／資料審查50%', NULL),
('電機工程學系', 5, 2, NULL, NULL, '1.歷年成績單(含班排名表)正本一份。2.自傳讀書計畫(詳述大一至今求學經驗、轉系動機、學習規劃及未來生涯規劃)。'),
('音樂學系', 3, NULL, NULL, '和聲學／聽寫／視唱（主修術科）', NULL),
('食品科學系', 9, NULL, NULL, '口試', '1.歷年成績單(含名次) 2.食科系轉系生專用申請表'),
('餐旅管理學系', 6, NULL, NULL, '口試60%；資料審查40%', NULL),
('體育學系（運動健康管理組）', 3, 3, 3, '資料審查（100%）', NULL),
('體育學系（運動競技組）', 3, 3, 3, '資料審查（100%）', NULL),
('體育學系（體育學組）', 3, 3, 3, '資料審查（100%）', NULL);

-- --------------------------------------------------------

--
-- 資料表結構 `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `users`
--

INSERT INTO `users` (`id`, `student_id`, `name`, `email`, `password`, `created_at`, `role`) VALUES
(1, '412401226', 'rita', 'csyyy2525@gmail.com', '$2y$10$0Xn4EdUTXgoSm.s9kwBGROMX.hlvHBw5pprQSXfw6xKPfpweSYHp2', '2025-05-21 09:48:01', 'user'),
(5, 'admin001', '系統管理員', 'admin@fju.edu.tw', '$2y$10$az9/4D0E1KAzbIx8kCcDguHfNKte1KwzbUUL2S5wZkNuaLXpv4fDe', '2025-05-21 09:57:40', 'admin');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `DepartmentTransfer`
--
ALTER TABLE `DepartmentTransfer`
  ADD PRIMARY KEY (`department_name`);

--
-- 資料表索引 `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `token` (`token`);

--
-- 資料表索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `email_2` (`email`),
  ADD KEY `student_id_2` (`student_id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

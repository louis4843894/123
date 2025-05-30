<?php
require_once 'config.php';

$host = 'localhost';
$username = 'root';
$password = '';

try {
    // 創建資料庫連接
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 創建資料庫
    $pdo->exec("CREATE DATABASE IF NOT EXISTS fju CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    $pdo->exec("USE fju");
    
    // ===== 系所相關資料表 =====
    
    // 創建系所基本資料表
    $sql = "CREATE TABLE IF NOT EXISTS departments (
        id INT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        intro TEXT,
        careers JSON,
        url VARCHAR(255),
        college VARCHAR(100)
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $pdo->exec($sql);
    
    // 創建系所詳細資料表
    $sql = "CREATE TABLE IF NOT EXISTS department_details (
        id INT AUTO_INCREMENT PRIMARY KEY,
        department_id INT NOT NULL,
        detail_type VARCHAR(50) NOT NULL,
        content TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $pdo->exec($sql);
    
    // 創建轉系資料表 (整合了考試類型和備註功能)
    $sql = "CREATE TABLE IF NOT EXISTS DepartmentTransfer (
        id INT AUTO_INCREMENT PRIMARY KEY,
        department_name VARCHAR(255) NOT NULL,
        year_2_enrollment VARCHAR(50),
        year_3_enrollment VARCHAR(50),
        year_4_enrollment VARCHAR(50),
        exam_subjects TEXT,
        data_review_ratio VARCHAR(50),
        exam_type VARCHAR(100),
        remarks TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $pdo->exec($sql);
    
    // ===== 使用者相關資料表 =====
    
    // 創建使用者表
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id VARCHAR(20) NOT NULL UNIQUE,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX (email),
        INDEX (student_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
    $pdo->exec($sql);
    
    // 創建密碼重設表
    $sql = "CREATE TABLE IF NOT EXISTS password_resets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL,
        token VARCHAR(64) NOT NULL,
        expires_at DATETIME NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX (email),
        INDEX (token)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
    $pdo->exec($sql);
    
    // ===== 討論區相關資料表 =====
    
    // 創建討論文章表
    $sql = "CREATE TABLE IF NOT EXISTS discussion_posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $pdo->exec($sql);
    
    // 創建討論回覆表
    $sql = "CREATE TABLE IF NOT EXISTS discussion_replies (
        id INT AUTO_INCREMENT PRIMARY KEY,
        post_id INT NOT NULL,
        user_id INT NOT NULL,
        content TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (post_id) REFERENCES discussion_posts(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $pdo->exec($sql);
    
    // 創建文章按讚表
    $sql = "CREATE TABLE IF NOT EXISTS post_likes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        post_id INT NOT NULL,
        user_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_like (post_id, user_id),
        FOREIGN KEY (post_id) REFERENCES discussion_posts(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $pdo->exec($sql);
    
    // 創建文章收藏表
    $sql = "CREATE TABLE IF NOT EXISTS post_favorites (
        id INT AUTO_INCREMENT PRIMARY KEY,
        post_id INT NOT NULL,
        user_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_favorite (post_id, user_id),
        FOREIGN KEY (post_id) REFERENCES discussion_posts(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $pdo->exec($sql);
    
    // ===== 其他功能資料表 =====
    
    // 創建考試時程表
    $sql = "CREATE TABLE IF NOT EXISTS exam_schedule (
        id INT AUTO_INCREMENT PRIMARY KEY,
        date DATE NOT NULL,
        event VARCHAR(100) NOT NULL,
        time VARCHAR(50) NOT NULL,
        location VARCHAR(100) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $pdo->exec($sql);
    
    // 創建系所比較清單表
    $sql = "CREATE TABLE IF NOT EXISTS compare_list (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        department_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_compare (user_id, department_id),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $pdo->exec($sql);
    
    // 插入預設管理員帳號
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT, ['cost' => 10]);
    $stmt = $pdo->prepare("INSERT IGNORE INTO users (student_id, name, email, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute(['admin001', '系統管理員', 'admin@fju.edu.tw', $admin_password, 'admin']);
    
    // 插入預設考試時程
    $sql = "INSERT INTO exam_schedule (date, event, time, location) VALUES 
    ('2025-06-01', '考試', '09:00 - 11:00', '教室 A'),
    ('2025-06-02', '面試', '13:00 - 15:00', '教室 B'),
    ('2025-06-03', '考試', '10:00 - 12:00', '教室 C')";
    $pdo->exec($sql);
    
    // 插入系所詳細資料
    $sql = "INSERT INTO departments (id, name, intro, careers, url, college) VALUES
(1, '中國文學系', '以古典與現當代漢語文學為主體，結合文獻學、比較文學、文本批評與數位人文方法，培養學生批判思考與創作能力。課程涵蓋先秦文獻、唐詩宋詞、小說戲劇及當代文學專題研究。', '[\"中 小學及大專院校教師\", \"出版／編輯、書評人\", \"文化創意產業（策展、公關）\", \"新聞傳播、數位內容編輯\", \"研究所深造、數位人文專案\"]', 'https://chinese.fju.edu.tw/', '文學院'),
(2, '歷史學系', '聚焦東亞與世界史研究，從史料詮釋、歷史理論到田野調查，訓練學生理解社會變遷脈絡與文化互動。強調跨領域方法，並提供博物館學、文化遺產保存等實務課程。', '[\"博物館／檔案館典藏管理\", \"公部門文史研究、文化資產維護\", \"教育教職（中 小學／大學）\", \"文化觀光產業、導覽解說\", \"研究所、博士班深造\"]', 'https://www.history.fju.edu.tw/', '文學院'),
(3, '哲學系', '探究存在論、認識論、倫理學與政治哲學等核心議題，並結合邏輯學與科際人文，培養學生批判反思與論證能力。課程涵蓋西洋、中國及比較哲學傳統。', '[\"學術研究、哲學／倫理諮商\", \"公共政策分析、智庫研究\", \"法律事務、企業倫理顧問\", \"出版、文化創意規劃\", \"研究所深造\"]', 'https://www.philosophy.fju.edu.tw/', '文學院'),
(4, '人文與社區創新學士學位學程', '跨領域整合人文思維、社區營造與創新實踐，結合媒體、設計與社會行動，強調從在地觀察到方案執行全流程訓練。課程包含社區調查、文化策展與社會創新工作坊。', '[\"社區發展專案規劃師\", \"NGO／NPO 專案管理\", \"文化創意產業策展、行銷\", \"公共參與與政策推廣\", \"社區總體營造顧問\"]', 'https://hci.ourpower.com.tw/', '文學院'),
(5, '音樂學系', '結合西洋音樂史、樂理、作曲與表演技術，並提供聲樂與各項器樂專業訓練。強調樂團合作與跨域表演實踐。', '[\"演奏家、樂團職員\", \"音樂教師、音樂治療師\", \"作曲／編曲、製作人\", \"藝術行政、文化推廣\", \"錄音／音響工程\"]', 'https://www.music.fju.edu.tw/', '藝術學院'),
(6, '應用美術學系', '涵蓋平面設計、插畫、動畫、數位視覺與金工實作，強調創意思考與商業專案整合。提供實習與業界合作機會。', '[\"平面／網頁／動態設計師\", \"插畫家、動畫師\", \"藝術指導、視覺企劃\", \"產品／包裝設計\", \"自主創業、工作室\"]', 'https://www.aart.fju.edu.tw/', '藝術學院'),
(7, '景觀設計學系', '結合生態、環境與人文，培養公共空間、庭園及城市綠地規劃能力。強調實地調查、3D 模擬與跨域協作。', '[\"景觀設計師／規劃師\", \"都市／環境顧問\", \"公部門都市計畫人員\", \"園藝造景、休憩場域管理\", \"跨域綠能與永續發展顧問\"]', 'https://www.landscape.fju.edu.tw/', '藝術學院'),
(8, '新聞傳播學系', '訓練新聞採訪、編輯、播報與新媒體運用，並融合媒體倫理、公共議題與數位分析。', '[\"記者、編輯、主播\", \"媒體企劃、公關／危機管理\", \"數位內容策略師\", \"政府／NGO 傳播官\", \"自媒體經營者\"]', 'https://www.jcs.tw/', '傳播學院'),
(9, '影像傳播學系', '教授電影、攝影、影像後製與新媒體敘事技術，強調腳本創作與跨平台發行。', '[\"導演、攝影師、剪輯師\", \"劇本／製片、影視剪接\", \"多媒體藝術家、VR／AR 內容創作\", \"廣告影像製作\", \"教學與研究\"]', 'https://www.commarts.fju.edu.tw/', '傳播學院'),
(10, '廣告傳播學系', '聚焦廣告策略、公關與整合行銷傳播，結合理論與實務專案，並強調數據驅動的創意發想。', '[\"廣告／公關公司策略規劃\", \"品牌經理、行銷企劃\", \"媒體採購、社群經營\", \"企業形象顧問\", \"數據分析與洞察師\"]', 'https://www.adpr.fju.edu.tw/', '傳播學院'),
(11, '圖書資訊學系', '結合圖書館學、資訊組織、檔案治理與知識管理，培育數位典藏、資料分析與資訊檢索專才。', '[\"圖書館員、檔案管理師\", \"數位資源管理專員\", \"知識管理／資訊顧問\", \"資料分析師、檢索工程師\", \"學術出版與編輯\"]', 'https://web.lins.fju.edu.tw/', '文學院'),
(12, '體育學系（體育學組）', '以體育教學理論與運動科學為核心，涵蓋運動生物力學、運動心理與體適能評估。', '[\"中 小學體育教師\", \"健康促進／體適能教練\", \"運動科學研究助理\", \"健身房／社區體育推廣人員\"]', 'https://www.phed.fju.edu.tw/', '教育與運動學院'),
(13, '體育學系（運動競技組）', '專注高階運動技能訓練、競賽策略與選手科學化管理，並輔以傷害防護與恢復。', '[\"職業／國家隊教練\", \"運動員經理人\", \"運動表現分析師\", \"運動賽事企劃\"]', 'https://www.phed.fju.edu.tw/', '教育與運動學院'),
(14, '體育學系（運動健康管理組）', '整合運動處方、健康促進與長期照護，培養社區與商業健康服務規劃能力。', '[\"運動健康管理師\", \"健康促進中心企劃\", \"長照機構運動處方師\", \"運動器材行銷與顧問\"]', 'https://www.phed.fju.edu.tw/', '教育與運動學院'),
(15, '教育領導與科技發展學士學位學程', '結合教育政策、領導學與教學科技，訓練數位學習平台開發、校務管理與教學設計能力。', '[\"校務行政、教育政策分析\", \"數位教材／教學系統開發\", \"企業／政府培訓設計師\", \"教學顧問、師資培育人員\"]', 'https://www.eltd.fju.edu.tw/', '教育與運動學院'),
(16, '公共衛生學系', '涵蓋流行病學、生物統計、環境與職業衛生、健康政策與行為科學，並強調跨領域防疫與健康促進。', '[\"公衛官員、CRO／藥廠研究\", \"環保／職業衛生技師\", \"健康促進與教育專員\", \"長照／社區健康管理\", \"碩博士深造\"]', 'https://www.medph.fju.edu.tw/', '醫學院'),
(17, '醫學系', '六年制臨床與基礎醫學教育，包含解剖、生理、病理等核心課程，並在附設教學醫院完成綜合臨床實習。', '[\"住院醫師訓練（各科專科）\", \"臨床專科醫師\", \"醫院管理與醫務策劃\", \"醫學研究、教學\"]', 'https://www.med.fju.edu.tw/', '醫學院'),
(18, '護理學系', '結合理論與臨床實務，教授護理評估、護理流程與社區健康照護技術。', '[\"臨床護理師（各專科／ICU／急診）\", \"社區衛教師、長照護理師\", \"護理管理（護理長／督導）\", \"護理教育與研究\"]', 'https://www.nursing.fju.edu.tw/', '醫學院'),
(19, '職能治療學系', '以活動分析、職能介入與復健理論為基礎，配合兒童、精神與身心障礙者的功能恢復實務。', '[\"職能治療師（醫院、復健中心、早療）\", \"輔具中心／社福機構\", \"特教團隊成員\", \"研究與教學\"]', 'https://www.ot.fju.edu.tw/', '醫學院'),
(20, '臨床心理學系', '結合心理評估、心理治療理論與臨床實習，覆蓋個體與團體諮商技術及診斷評估。', '[\"臨床／諮商心理師（醫院、診所、學校）\", \"心理評估與輔導顧問\", \"UX / 人資或市場研究\", \"學術研究與教學\"]', 'https://www.cpsy.fju.edu.tw/', '醫學院'),
(21, '呼吸治療學系', '專注呼吸生理、呼吸器操作、重症照護與居家呼吸管理技術，並含睡眠醫學與肺功能檢測。', '[\"呼吸治療師（ICU、急診、睡眠中心）\", \"高壓氧技術員\", \"醫療器材研發／行銷\", \"居家療養服務\"]', 'https://www.drt.fju.edu.tw/', '醫學院'),
(22, '英國語文學系', '以英美文學、語言學與跨文化溝通為主軸，結合文學賞析、語言教學法與專業翻譯訓練。強化口語、書寫與批判思維。', '[\"英語教師、補習班講師\", \"翻譯／口譯員\", \"國際企業／客服專員\", \"出版編輯、文化交流企劃\", \"研究所深造\"]', 'https://english.fju.edu.tw/', '外國語文學院'),
(23, '法國語文學系', '聚焦法語文學、法國文化與法語教學，並輔以歐洲研究、跨文化溝通與翻譯實務課程。', '[\"法語教師、翻譯／口譯\", \"外貿／駐外商務人員\", \"文化活動策劃／導覽\", \"出版、傳播媒體\", \"進修法國研究所\"]', 'https://www.fren.fju.edu.tw/', '外國語文學院'),
(24, '西班牙語文學系', '涵蓋西語文學、拉美文化與語言學理論，並提供翻譯、觀光與國際事務方向課程。', '[\"西語教師、翻譯／口譯\", \"觀光導遊、國貿專員\", \"多語內容編輯\", \"NGO／國際救援組織\", \"進修西語相關研究\"]', 'https://www.span.fju.edu.tw/', '外國語文學院'),
(25, '日本語文學系', '聚焦日本文學、文化研究與媒體語言，同時開設商務日語、翻譯與跨文化溝通課程。', '[\"日語教師、翻譯／口譯\", \"日系企業／貿易專員\", \"文化導覽、旅遊規劃\", \"媒體編輯、出版\", \"深造日本文學或跨域研究\"]', 'https://www.jp.fju.edu.tw/', '外國語文學院'),
(26, '德語語文學系', '研究德國文學、思想史與語言學，並提供翻譯實務、歐盟事務與雙聯學程機會。', '[\"德語教師、翻譯／口譯\", \"歐盟事務助理、國際交流\", \"德系企業商務人員\", \"出版、學術助理\", \"深造歐洲研究\"]', 'https://www.de.fju.edu.tw/', '外國語文學院'),
(27, '義大利語文學系', '以義大利文學藝術、文化史與語言教學為主，並開設設計、旅遊與跨文化課程。', '[\"義語教師、翻譯／口譯\", \"藝術文化導覽、博物館遊程策劃\", \"義系企業／貿易專員\", \"出版策展、學術研究\"]', 'https://www.italy.fju.edu.tw/', '外國語文學院'),
(28, '國際溝通與科技創新學士學位學程', '結合企業管理、國際行銷與科技創新，並強調跨文化溝通與專案管理實務。', '[\"國際業務／行銷專員\", \"科技產品企劃與管理\", \"數位轉型顧問\", \"進修MBA或管理類研究所\"]', 'https://fjucflvd.wixsite.com/', '外國語文學院'),
(29, '織品服裝學系（織品服飾行銷組）', '從紡織材料、流行趨勢到品牌行銷，培養服飾產業市場研究與行銷策劃能力。', '[\"服飾品牌行銷／企劃\", \"流行趨勢分析師\", \"零售營運、貿易專員\", \"自主品牌創業\"]', 'https://www.tc.fju.edu.tw/', '民生學院'),
(30, '織品服裝學系（服飾設計組）', '強調服裝設計原理、立體裁剪與視覺呈現，並結合數位製圖與成衣打版實作。', '[\"服裝設計師、打版師\", \"成衣開發、設計顧問\", \"時尚攝影助理\", \"自主設計工作室\"]', 'https://www.tc.fju.edu.tw/', '民生學院'),
(31, '織品服裝學系（織品設計組）', '聚焦紡織品開發與創新印染技術，從纖維化學到數位織染，並融入跨域藝術實踐。', '[\"紡織品研發工程師\", \"印染／織機技術員\", \"面料設計與測試\", \"時尚與家用紡織品牌顧問\"]', 'https://www.tc.fju.edu.tw/', '民生學院'),
(32, '餐旅管理學系', '涵蓋餐旅業營運管理、服務設計、廚務與住宿管理，並強調跨文化服務與數位化經營。', '[\"飯店／餐廳經營管理\", \"旅遊規劃／票務特派\", \"餐飲顧問、宴會企劃\", \"OTA 平台／旅遊科技業\", \"導遊／領隊\"]', 'https://www.rhim.fju.edu.tw/', '民生學院'),
(33, '食品科學系', '從食品化學、微生物、安全檢驗到食品加工技術，培養產品開發與品質管控專才。', '[\"食品研發／品管工程師\", \"食品安全檢驗人員\", \"食品營養／標示顧問\", \"食品生產／包裝技術主管\", \"研究所深造\"]', 'https://www.fs.fju.edu.tw/', '民生學院'),
(34, '兒童與家庭學系', '從家庭與兒童發展理論、社會政策到實務工作，培養學生掌握兒童福利、家庭研究與社會干預技術。課程包含心理評估、社區訪談與專案管理。', '[\"兒童福利專員\", \"家庭輔導員\", \"社工師\", \"早期教育工作者\", \"研究所深造\"]', 'https://www.cfs.fju.edu.tw/', '民生學院'),
(35, '營養科學系', '結合人體營養、生理生化與公共衛生，以臨床營養與社區健康為導向，並含運動營養與餐飲規劃。', '[\"臨床營養師（醫院、診所）\", \"社區健康促進師\", \"餐飲營養顧問\", \"食品公司／保健產業研發\", \"營養學研究所\"]', 'https://www.ns.fju.edu.tw/', '民生學院'),
(36, '法律學系', '涵蓋民法、刑法、憲法與行政法等基礎法學理論與實務，並提供模擬法庭與法律診所實作。', '[\"律師、檢察官、法官\", \"法務專員、企業法務\", \"公職（司法體系）\", \"法律研究與教學\"]', 'https://www.laws.fju.edu.tw/', '法律學院'),
(37, '財經法律學系', '結合法律與商業實務，聚焦公司法、證券法、稅法與金融監管，並強調案例分析與模擬實務。', '[\"企業法務、證券／投資銀行法務\", \"金融監理、稅務顧問\", \"合規（compliance）專員\", \"律所或內部法律顧問\"]', 'https://www.financelaw.fju.edu.tw/', '法律學院'),
(38, '心理學系', '以心理學理論、實驗方法與統計分析為基礎，涵蓋認知、發展、社會與臨床心理學。', '[\"一般諮商／工業組織心理師\", \"市場研究與使用者體驗（UX）\", \"人資顧問、組織發展專員\", \"學術研究與教育\"]', 'https://psy.fju.edu.tw/', '理工學院'),
(39, '社會工作學系', '訓練社會福利政策、個案管理與社區工作實務，並強調田野實習與多元服務系統合作。', '[\"社工督導、個案管理師\", \"社福／長照機構專員\", \"NGO／NPO 項目經理\", \"社會政策分析與研究\"]', 'https://www.soci.fju.edu.tw/', '民生學院'),
(40, '天主教研修學士學位學程', '以天主教文化、神學與社會教義為核心，並結合社會實踐與志工服務。', '[\"教會／社福機構服務人員\", \"教育／宗教文化推廣\", \"NGO／志工服務管理\", \"進修神學或宗教研究\"]', 'https://bpcs.fju.edu.tw/', '社會科學院'),
(41, '社會學系', '研究社會結構、文化、階層與變遷，並運用定性與定量研究方法解析當代社會議題。', '[\"社會調查與研究分析師\", \"政府／智庫研究人員\", \"品牌與市場洞察專員\", \"NGO／社團企劃\"]', 'https://www.soci.fju.edu.tw/', '社會科學院'),
(42, '經濟學系', '涵蓋微觀、宏觀、計量經濟與產業經濟學，並強調實證研究與政策評估。', '[\"金融分析師、經濟研究員\", \"政府經建／央行／公營事業\", \"企業策略與市場分析\", \"研究所、博士班深造\"]', 'https://www.economics.fju.edu.tw/', '社會科學院'),
(43, '宗教學系', '從宗教史、宗教哲學到東西方宗教比較與當代宗教社會學，訓練跨文化理解與宗教對話能力。', '[\"宗教研究、文化策展\", \"NGO／人權組織專員\", \"教育與出版\", \"跨宗教對話與諮詢\"]', 'https://www.rsd.fju.edu.tw/', '社會科學院'),
(44, '金融與國際企業學系', '聚焦金融市場、投資組合管理與跨國企業策略，結合風險管理與財務工程工具。', '[\"投資銀行／證券分析師\", \"風險管理／資產管理\", \"跨國企業財務專員\", \"研究所深造\"]', 'https://www.fib.fju.edu.tw/', '社會科學院'),
(45, '企業管理學系', '涵蓋組織行為、戰略管理、行銷與運營管理，並輔以實習與企業個案研究。', '[\"企業策略／行銷／人資專員\", \"管理顧問、專案經理\", \"企業家／新創團隊核心\", \"研究所或MBA\"]', 'https://www.management.fju.edu.tw/subweb/mba/', '管理學院'),
(46, '會計學系', '聚焦財務會計、管理會計、審計與稅務，並注重法規遵循與資訊系統應用。', '[\"公（勤）會計師、內部稽核\", \"企業會計／財務分析師\", \"稅務顧問、財務顧問\", \"研究所深造\"]', 'https://www.management.fju.edu.tw/subweb/acct/', '管理學院'),
(47, '資訊管理學系', '結合資訊系統分析、資料庫、電子商務與企業資源規劃，培養IT與管理雙重能力。', '[\"系統分析師、專案經理\", \"資料庫管理／BI 分析師\", \"電子商務／數位行銷\", \"IT 咨詢顧問\"]', 'https://www.im.fju.edu.tw/', '管理學院'),
(48, '統計資訊學系', '以機率統計理論、大數據分析與資料挖掘為基礎，並應用於金融、生醫與行銷研究。', '[\"資料科學家、統計分析師\", \"風險模型開發、精算師\", \"市調／市場分析\", \"研究所深造\"]', 'https://www.stat.fju.edu.tw/', '管理學院'),
(49, '醫學資訊與創新應用學士學位學程', '融合醫學、資訊技術與創新應用，涵蓋醫療大數據、AI診療輔助、數位病歷與遠距醫療系統設計。', '[\"醫療資訊系統開發／管理\", \"數位健康產品企劃\", \"醫療大數據分析師\", \"遠距醫療與智慧醫院規劃\"]', 'https://www.miia.fju.edu.tw/', '理工學院'),
(50, '電機工程學系', '教授電路、電子、通訊與控制系統，並結合軟硬體整合與嵌入式系統開發實務。', '[\"通信／半導體／電力工程師\", \"控制系統／自動化工程師\", \"嵌入式軟硬體開發\", \"研究所與產學合作\"]', 'https://www.ee.fju.edu.tw/', '理工學院'),
(51, '數學系（資訊數學組）', '結合數學理論與計算方法，涵蓋演算法分析、密碼學與金融數學等應用領域。', '[\"演算法工程師、資料科學家\", \"金融工程／風險管理\", \"學術研究與教學\", \"進修應用數學或資訊相關研究\"]', 'https://www.math.fju.edu.tw/', '理工學院'),
(52, '數學系（應用數學組）', '以數值分析、優化方法與科學計算為主，並應用於物理、工程與社會科學問題模型。', '[\"科學計算工程師\", \"最佳化顧問、數值模擬分析\", \"學術研究與產學合作\", \"進修應用領域博士班\"]', 'https://www.math.fju.edu.tw/', '理工學院'),
(53, '生命科學系', '涵蓋分子生物、細胞生物、基因體學與生物技術，並提供跨實驗室研究與產學合作。', '[\"生技研發／品管工程師\", \"醫藥公司研發、CRO\", \"生物資訊與基因體研究\", \"研究所、博士班深造\"]', 'https://www.bio.fju.edu.tw/', '理工學院'),
(54, '物理學系（物理組）', '以理論物理與實驗物理為基礎，強調經典力學、量子力學與電磁學研究訓練。', '[\"基礎研究助理\", \"儀器研發工程師\", \"學術研究與教學\", \"產業研發單位\"]', 'https://www.phy.fju.edu.tw/', '理工學院'),
(55, '物理學系（光電物理組）', '聚焦光學、光電材料與雷射技術，並結合實驗操作與產業應用。', '[\"光電元件研發工程師\", \"通信／光纖／感測器技術員\", \"半導體製程研發\", \"國家實驗室與教學\"]', 'https://www.phy.fju.edu.tw/', '理工學院'),
(56, '物理學系（電子物理組）', '研究半導體物理、奈米電子與微電子製程，並涵蓋材料科學與儀器開發。', '[\"半導體研發工程師\", \"晶片設計／測試工程師\", \"國家實驗室／研究所\", \"教學與產學合作\"]', 'https://www.phy.fju.edu.tw/', '理工學院'),
(57, '化學系', '涵蓋有機、無機、高分子與分析化學，並強調實驗操作與化學工程基礎。', '[\"化工／材料研發工程師\", \"分析檢驗、品質管控\", \"製藥／生技公司研發\", \"研究所深造\"]', 'https://ch.fju.edu.tw/', '理工學院'),
(58, '資訊工程學系', '以程式設計、演算法、作業系統與人工智慧為主軸，並強調大規模系統與雲端應用。', '[\"軟體工程師、系統架構師\", \"人工智慧／機器學習工程師\", \"資料庫／後端開發\", \"研究所或新創技術研發\"]', 'https://csie2.fju.edu.tw/', '理工學院'),
(59, '人工智慧與資訊安全學士學位學程', '結合機器學習、深度學習與資安理論，涵蓋入侵偵測、區塊鏈與數位鑑識實務。', '[\"資安工程師、滲透測試師\", \"AI 產品開發與研究\", \"區塊鏈應用開發\", \"研究所深造\"]', 'https://www.ais.fju.edu.tw/', '理工學院'),
(60, '跨領域全英語學士學位學程', '以英語為授課語言，整合管理、科技、社會科學與人文議題，強調全球視野與跨文化交流。', '[\"國際業務／專案經理\", \"全球 NGO／國際組織\", \"跨國企業／諮詢顧問\", \"海外深造與交換\"]', 'https://bpis.fju.edu.tw/', '國際學院');
    ";
    $pdo->exec($sql);
    
    echo "資料庫初始化成功！";
    
} catch(PDOException $e) {
    echo "錯誤：" . $e->getMessage();
}
?> 
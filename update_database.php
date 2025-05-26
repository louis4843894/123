<?php
require_once 'config.php';

try {
    // 添加 college_name 欄位
    $pdo->exec("ALTER TABLE DepartmentTransfer ADD COLUMN IF NOT EXISTS college_name VARCHAR(50) DEFAULT NULL");

    // 添加 notes 欄位
    $pdo->exec("ALTER TABLE DepartmentTransfer ADD COLUMN IF NOT EXISTS notes TEXT DEFAULT NULL");

    // 更新現有資料的學院名稱
    $pdo->exec("UPDATE DepartmentTransfer 
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
        END");

    // 更新系所備註
    $pdo->exec("UPDATE DepartmentTransfer 
    SET notes = 
        CASE 
            WHEN department_name LIKE '%中國文學%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：中國文學史、中國思想史、文字學\n3. 考試科目：國文、中國文學史、中國思想史\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%歷史%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：中國通史、世界通史、史學方法\n3. 考試科目：國文、中國通史、世界通史\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%哲學%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：哲學概論、邏輯學、倫理學\n3. 考試科目：國文、哲學概論、邏輯學\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%音樂%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：音樂理論、和聲學、音樂史\n3. 考試科目：音樂理論、和聲學、音樂史\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%應用美術%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：美術史、設計概論、色彩學\n3. 考試科目：美術史、設計概論、色彩學\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%景觀%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：景觀設計、環境規劃、植物學\n3. 考試科目：景觀設計、環境規劃、植物學\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%新聞%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：新聞學、傳播理論、新聞寫作\n3. 考試科目：新聞學、傳播理論、新聞寫作\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%廣告%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：廣告學、行銷學、創意設計\n3. 考試科目：廣告學、行銷學、創意設計\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%大眾傳播%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：傳播理論、媒體研究、傳播倫理\n3. 考試科目：傳播理論、媒體研究、傳播倫理\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%體育%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：體育理論、運動生理學、運動心理學\n3. 考試科目：體育理論、運動生理學、運動心理學\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%教育%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：教育學、教育心理學、教育行政\n3. 考試科目：教育學、教育心理學、教育行政\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%醫學%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：解剖學、生理學、病理學\n3. 考試科目：解剖學、生理學、病理學\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%護理%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：護理學、解剖學、生理學\n3. 考試科目：護理學、解剖學、生理學\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%公共衛生%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：公共衛生學、流行病學、衛生行政\n3. 考試科目：公共衛生學、流行病學、衛生行政\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%數學%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：微積分、線性代數、機率論\n3. 考試科目：微積分、線性代數、機率論\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%物理%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：普通物理、電磁學、量子力學\n3. 考試科目：普通物理、電磁學、量子力學\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%化學%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：普通化學、有機化學、物理化學\n3. 考試科目：普通化學、有機化學、物理化學\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%資訊%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：程式設計、資料結構、計算機概論\n3. 考試科目：程式設計、資料結構、計算機概論\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%英文%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：英文寫作、英文會話、英文文學\n3. 考試科目：英文寫作、英文會話、英文文學\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%日文%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：日文寫作、日文會話、日本文學\n3. 考試科目：日文寫作、日文會話、日本文學\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%德文%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：德文寫作、德文會話、德國文學\n3. 考試科目：德文寫作、德文會話、德國文學\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%法文%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：法文寫作、法文會話、法國文學\n3. 考試科目：法文寫作、法文會話、法國文學\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%織品%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：織品設計、紡織材料、服裝設計\n3. 考試科目：織品設計、紡織材料、服裝設計\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%食品%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：食品化學、食品微生物、食品加工\n3. 考試科目：食品化學、食品微生物、食品加工\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%營養%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：營養學、食品化學、人體生理學\n3. 考試科目：營養學、食品化學、人體生理學\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%法律%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：民法、刑法、憲法\n3. 考試科目：民法、刑法、憲法\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%財經法律%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：民法、刑法、憲法、經濟學\n3. 考試科目：民法、刑法、憲法、經濟學\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%社會%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：社會學、社會研究方法、社會心理學\n3. 考試科目：社會學、社會研究方法、社會心理學\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%心理%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：普通心理學、發展心理學、社會心理學\n3. 考試科目：普通心理學、發展心理學、社會心理學\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%社工%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：社會工作概論、社會福利、社會政策\n3. 考試科目：社會工作概論、社會福利、社會政策\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%企管%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：管理學、會計學、經濟學\n3. 考試科目：管理學、會計學、經濟學\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%會計%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：會計學、成本會計、審計學\n3. 考試科目：會計學、成本會計、審計學\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%統計%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：統計學、機率論、數理統計\n3. 考試科目：統計學、機率論、數理統計\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            WHEN department_name LIKE '%金融%' THEN '1. 學業成績：平均成績達 75 分以上\n2. 必修科目：金融學、投資學、財務管理\n3. 考試科目：金融學、投資學、財務管理\n4. 面試：口試（佔總成績 30%）\n5. 其他要求：需提交讀書計畫與研究計畫'
            ELSE NULL
        END");

    echo "資料庫更新成功！";
} catch (PDOException $e) {
    echo "資料庫更新失敗：" . $e->getMessage();
} 
CREATE TABLE DepartmentRemarksSplit (
  department_name VARCHAR(255) NOT NULL,
  remark1 TEXT,
  remark2 TEXT,
  remark3 TEXT,
  remark4 TEXT,
  remark5 TEXT,
  remark6 TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO DepartmentRemarksSplit (department_name, remark1, remark2, remark3, remark4, remark5, remark6) VALUES
('中國文學系',
 '1. 學業成績總平均60分以上',
 '2. 一年級國文成績須達60分以上',
 '3. 口試成績均需達到60分，按成績高低依序錄取',
 '4. 口試時間地點由系辦公室主動聯繫考生',
 '5. 學生成績未達錄取標準，本系保有可不足額錄取',
 '6. 經錄取後，不得提高編級'),
('歷史學系',
 '1. 須附自傳(含申請動機)',
 '2. 口試時間地點由系辦公室主動聯繫考生',
 NULL, NULL, NULL, NULL),
('哲學系',
 '口試時間地點由系辦公室主動聯繫考生',
 NULL, NULL, NULL, NULL, NULL),
('人文與社區創新學士學位學程',
 '1. 須附自轉',
 '2. 口試時間地點由學程辦公室主動聯繫考生',
 NULL, NULL, NULL, NULL),
('音樂學系',
 '1. 限主修鋼琴、聲樂、弦樂、管樂、理論作曲報名，主修考試內容請洽音樂系辦公室',
 '2. 需先通過術科鑑定考後始能上線申請.音樂系申請日期：3月10日(一)至3月14日(五)截止，申請表格洽音樂系辦公室(藝術學院AM206室)',
 '第一階段筆試考試日期：3月17日(一)',
 '第二階段面試日期：3月20日(四)，詳細時間及地點屆時請至音樂系網站查詢',
 '3. 第一階段考試，每科須滿80分(含)以上，始可進入第二階段考',
 '4.需繳交術科筆試考試報名費 800 元'),
('應用美術學系',
 '1. 考試時間為114年4月23日(星期三)',
 '基礎素描（術科13:30至15:00，考試地點：考試當日公告於藝術學院三樓）',
 '2. 基礎素描限用黑色鉛筆、可使用美工刀、筆刨，禁用會發生聲響之手(電)動削鉛筆機，禁用炭筆、噴膠、畫板',
 '3. 考生須於報名期間至藝術學院應美系AA315繳交測驗費500元',
 '4. 學生成績未達錄取標準得不足額錄取',Null),
('景觀設計學系',
 '1. 口試時間為114年4月21日(星期一)上午10:00開始',
 '2. 攜帶審查資料：1. 讀書計畫 2. 成績單 3. 補充資料，如作品集',
 NULL, NULL, NULL, NULL),
('新聞傳播學系',
 '1. 繳交歷年成績單、自傳及讀書計畫，經本系招生委員會審核後，取前9名參加口試，以口試成績擇優錄取',
 '2. 獲錄取學生不得以任何理由要求提高編級',
 NULL, NULL, NULL, NULL),
('影像傳播學系',
 '1. 前一學期學業成績名次在該班前30%',
 '2. 資料審查內容：(一式兩份)：
  (1) 自傳（詳述申請動機）
  (2) 修課計畫（請說明預計修讀的課程領域及規劃）
  (3) 含排名之歷年成績單
  (4) 其他有助於審查之資料：如英語檢定成績、校內外參賽記錄或足以支持就讀本系能力之個人相關作品等',
 '3. 本系保有不足額錄取',
 '4.經錄取後不得提高編級',
 '5.口試時間及地點請至影傳系網頁查詢',Null),
('廣告傳播學系',
 '1. 前學期學業成績排名全班前20%',
 '2. 繳交自傳及讀書計畫，含名次之歷年成績單',
 '3. 參加本系舉辦之口試',
 '4. 本系保有不足額錄取權利',
 '5. 口試日程：114年3月28日(五)起於系網頁公告口試時間',Null),
('圖書資訊學系',
 '1. 上學期學業成績總平均60分以上，或全班排名前50%者',
 '2. 繳交自傳及歷年成績單',
 '3. 可繳交足以展現學習成果之作品',
 '4. 學生成績未達取標準，得不足額錄取',
 '5. 口試時間及地點請至圖資系網頁查詢',Null),
('體育學系體育學組',
 '依本系「輔仁大學學生轉入體育學系（組）審查標準」審查：1. 報考本組專長生限選以下運動種類（限選其一）：舞蹈、競技啦啦隊、桌球、羽球、硬式網球、柔道、舉重、拳擊、其他(無專長生)',
 '2. 申請人備妥自傳、成績單、運動經歷及證明(無則免附)',
 '3. 由系主任召集本系專任教師二名組成審查小組',
 NULL, NULL, NULL),
('體育學系運動競技組',
 '依本系「輔仁大學學生轉入體育學系（組）審查標準」第二條規定-轉系生審查標準：1.報考本組限選以下運動種類（限選其一，請於自傳內說明）： 田徑、籃球、棒球、足球、跆拳道、划船、射箭、擊劍、游泳',
 '2. 申請人備妥自傳、成績單、運動經歷及證明(無則免附)',
 '3. 由系主任召集本系專任教師二名組成審查小組',
 NULL, NULL, NULL),
('體育學系運動健康管理組',
 '依本系「輔仁大學學生轉入體育學系（組）審查標準」第二條規定-轉系生審查標準：1.申請人備妥(1)自傳、(2)歷年成績單、(3)運動相關經歷及成績證明(無則免附)等，提出申請',
'2.由系主任為召集人聘請本系專任教師二名為審查小組委員，進行審查或',
 NULL, NULL, NULL, NULL),
('教育領導與科技發展學士學位學程',
 '1. 歷年成績單正本一份',
 '2. 自傳（含申請動機與讀書計畫）一份，字數1000–1500字',
 '3. 學生成績未達錄取標準，得不足額錄取',
 NULL, NULL, NULL),
('公共衛生學系',
 '口試時間及地點請至公衛系網頁查詢',
 NULL,NULL, NULL, NULL, NULL),
('醫學系',
 '1. 報名審查費 500 元，筆試費 1000 元，面試費1000 元，分階段繳交費用。低收入戶考生費用全免，惟申請時請檢具各級政府開立之證明文件',
'2. 申請資格：(1).操行成績需達 86 分以上，不得有懲誡紀錄。(2).學業成績：其在學期間每學年每學期學業平均成績均須達全班排名前 10%，不得有不及格科目。外校所修科目成績，不得作為申請轉入本系審核之依據。(3).英文能力證明：二年內全民英檢中高級初試通過或相當等級之其他英語檢定考試。經審查合乎上列標準者始有資格參加轉系考試',
'3. 總成績計算：書面審查佔總成績之 10%，筆試平均成績佔總成績之 60%，面試佔總成績之30%，各項成績採計至小數點第二位。考試日期及地點另行訂定',
'4. 總成績未達錄取標準時，得不足額錄取，如總成績相同時，依「面試成績」、「普通生物學成績」、「普通化學成績」、「英文成績」之比較順序擇優依序錄取',
'5. 學期成績審查或申請時成績未到齊，而後轉系考試通過者，學期審查成績如未達申請資格所規定之標準則不予錄取',
'6. 轉系後，因補修學分必須延長修業年限者，則依本校學則規定辦理。經核准轉系者，其應修學分數及必修科目，應依轉入年級學生入學學年度課程科目學分表之規定
7. 課程學習過程及畢業後臨床工作，需具有適當之視覺、聽覺、口語表達溝通、情緒管理及肢體活動能力'),
('護理學系',
 '1.修畢一上必修國文、英文，且前一學期學業總成績名次在該班前百分之十者',
'2.按考試總成績高低依序錄取因實習組數安排等因素，可不足額錄取。總成績相同者依口試成績高低錄取',
'3.經錄取後，不得提高編級',
'4.實習課程需要在醫院從事醫護工作，具有與人直接互動的特性，須具備護理知能、相當之視覺、聽覺、口語表達及肢體迅速反應與移動能力，能控管自身情緒與與相當之抗壓能力',
'5.大一英文抵免或免修者請另行提供英文能力證明(如英檢證明等)）',Null),
('職能治療學系',
 '1.資料審查條件：(1)成績單：前一學期總成績為全班前 10%；英文 75 分以上，操行 80 分以上(2)自傳，含轉系動機和生涯規劃',
'2.申請轉入本系就讀經核准後，不得要求提高編級',
'3. 學生成績未達錄取標準，本系保有可不足額錄取',
 NULL,NULL, NULL),
('臨床心理學系',
 '1. 附歷年成績單（含名次），前一學期成績排名前25%',
 '2. 國文及外國語文成績須在60分以上',
 '3. 口試時間及地點請至臨心系網頁查詢',
 '4. 學生成績未達錄取標準，本系保有不足額錄取',
 NULL, NULL),
('呼吸治療學系',
 '1.學業成績總平均達 85(含)分；英文成績 85(含)分以上，且無不及格者',
'2.口試時間及地點請至本系網頁查詢',
'3.課程學習過程及畢業後臨床工作，需具有適當之視覺、聽覺、口語表達溝通、情緒管理及肢體活動能力',
'4.學生成績未達錄取表準，本系保有可不足額錄取',
'5.經錄取後，不得提高編級',Null),
('英國語文學系',
 '1. 英語文課程平均80分以上，請附英文版歷年成績單',
 '2. 英文作文筆試：60分鐘，不得攜帶字典；114年3月27日(四)12:30，FG308教室',
 '3. 英語面試：114年4月16日13:40起，請預留時間；通過筆試者方可參加面試，名單請自行上網瀏覽',
 '4. 學生成績未達錄取標準，本系保有不足額錄取',
 NULL, NULL),
('法國語文學系',
 '1. 須自一年級課程循序修讀（須延畢一年）',
 '2. 請附歷年成績單（附排名）、動機說明書、語言檢定證明（無則免）',
 '3. 通過法語鑑定文憑(DELF)B1 以上始得畢業',
 '4. 口試時間及地點報名結束後請至法國語文學系網頁查詢',
 NULL, NULL),
('西班牙語文學系',
 '1. 大學國文及外國語文成績須在70分以上',
 '2. 學生成績未達錄取標準，本系可不足額錄取',
 '3. 須自一年級課程循序修讀（可能延畢）',
 '4. 請附歷年成績單（含排名）、動機說明書（含原系級、學號、姓名、手機、EMAIL、電腦打字格式自訂）、語言檢定證明（無則免）',
 '5. 口試時間及地點報名結束後請至西班牙語文學系網頁查詢',Null),
('日本語文學系',
 '1. 歷年成績平均75分以上且無不及格',
 '2. 日僑及日籍生不得申請',
 '3. 申請時檢附中文歷年排名成績單正本一份、轉系報告書一份（含轉系理由及讀書計畫），電腦打字 A4 一頁內為限，未繳交者不得參加考試',
 '4. 若通過日語能力檢定考試，亦可檢附証書影本',
 '5. 筆試、口試時間及地點於報名結束後請至日本語文學系網頁查詢',Null),
('德語語文學系',
 '1. 歷年學期平均成績皆須達70分（含）',
 '2. 附含名次之歷年成績單正本',
 '3. 轉系後須自德語系一年級課程循序修讀',
 '4. 畢業條件：108 學年度（含）起入學生，於畢業前需依本系「學習成果展示實施辦法」完成學習成果展示，始有畢業資格',
 NULL, NULL),
('義大利語文學系',
 '1. 請附歷年成績單（含排名）',
 '2. 通過等同CEFR B1級（含）以上之義語檢定考試始得畢業',
 '3. 面試時間：114年4月18日12:30-13:30，時間及名單請自行上網瀏覽',
 NULL, NULL, NULL),
('國際溝通與科技創新學士學位學程',
 '1. 大學國文及外國語文成績須在70分以上（英文組須80分以上）',
 '2. 學生成績未達錄取標準，本系保有不足額錄取',
 '3. 須自一年級課程循序修讀（可能延畢）',
 '4. 請附歷年成績單（含排名）、動機說明書（含原系級、學號、姓名、手機、EMAIL、打字格式）、語言檢定證明（無則免）',
 '5. 口試時間及地點報名結束後請至科創學程網頁查詢',
 '6. 語文分組方式將依學生考試成績、語言學習背景與學生語組志願進行分配。(日文組1名、英文組2名、義大利文組2名，錄取各分組人數學程有權作名額調整)'),
('織品服裝學系（織品服飾行銷組）',
 '請附自傳、讀書計畫、歷年成績單、作品集（A4規格）',
 '1.本系為應用科系，課程包含理論與實作(操作檢驗設備及實驗課程等)，教學方式多元且生動活潑',
'2.部分課程有擋修和設備限制，同學可彈性運用選課時間，修讀外系、通識或進修部課程補足日間空堂時段',
'3.系方將邀請業師協助指導學生完成畢業製作，有助於學生求職並培訓提案實力',
'4.本系提供五年一貫機制，申請通過之學生可提前修讀碩士班課程，經碩士班錄取之學生，有機會於第五年取得碩士學位',
'5.本系設有多項獎學金可供申請 6.歷年來均有學生獲教育部藝術與菁英還外培訓計畫公費出國就讀海外學校'),
('織品服裝學系（服飾設計組）',
 '請附自傳、讀書計畫、歷年成績單、作品集（A4規格）',
 '1.本系為應用科系，課程包含理論與實作(操作檢驗設備及實驗課程等)，教學方式多元且生動活潑',
'2.部分課程有擋修和設備限制，同學可彈性運用選課時間，修讀外系、通識或進修部課程補足日間空堂時段',
'3.系方將邀請業師協助指導學生完成畢業製作，有助於學生求職並培訓提案實力',
'4.本系提供五年一貫機制，申請通過之學生可提前修讀碩士班課程，經碩士班錄取之學生，有機會於第五年取得碩士學位',
'5.本系設有多項獎學金可供申請
6.歷年來均有學生獲教育部藝術與菁英還外培訓計畫公費出國就讀海外學校
7. 轉系生於原系修讀之專業必修及專業選修課程，不列入外系選修學分中，但轉入本系前選修本系以外之學分則不在此限'),
('織品服裝學系（織品設計組）',
 '請附自傳、讀書計畫、歷年成績單、作品集（A4規格）',
 '1.本系為應用科系，課程包含理論與實作(操作檢驗設備及實驗課程等)，教學方式多元且生動活潑',
'2.部分課程有擋修和設備限制，同學可彈性運用選課時間，修讀外系、通識或進修部課程補足日間空堂時段',
'3.系方將邀請業師協助指導學生完成畢業製作，有助於學生求職並培訓提案實力',
'4.本系提供五年一貫機制，申請通過之學生可提前修讀碩士班課程，經碩士班錄取之學生，有機會於第五年取得碩士學位',
'5.本系設有多項獎學金可供申請'),
('餐旅管理學系',
 '1. 備審資料：歷年排名成績單正本、自傳、修讀機畫書（含轉系動機及讀書計畫）.',
 '2. 口試時間及地點報名結束後請至餐旅管理學系網頁查詢',
 NULL, NULL, NULL, NULL),
('食品科學系',
 '1.食科系轉系生專用請表請至食科系系網頁最新消息下載',
'2.口試時間及地點報名結束後請至食科系系網頁最新消息查詢',
'3.學生成績未達錄取標準，本系可不足額錄取',
'4.本系網頁 https://www.fs.fju.edu.tw', NULL, NULL),
('兒童與家庭學系',
 '口試時間及地點報名結束後請至兒童與家庭學系網頁查詢',
 NULL, NULL, NULL, NULL, NULL),
('營養科學系',
 '1.營養系轉系生專用資料表請至本系系網頁下載(http://www.ns.fju.edu.tw/Index/4/46)',
'2. 配合本系實習要點及實習醫院規定：「實習生實習前須修畢營養師先修科目」，非相關科系轉入者，宜審慎排課。先修科目未完備者，不得以「避免延畢」為由，要求提前安排醫院實習',
'3.口試時間及地點報名結束後請至營養科學學系網頁查詢',
 NULL, NULL, NULL),
('法律學系',
 '1. 申請時前一學期學業總成績占該班前50%',
 '2. 轉入後不得提高編級',
 '3. 筆試時間：114年4月23日13:40-15:30，考試地點及注意事項將於考前一週公告，請自行至本系網頁查詢',
 '4. (1)本系學生須於畢業前符合法律學院學生學習護照之學習認證標準。(2)畢業學分中須含 1 門 2學分之全英語課程。(3)其餘畢業條件請參閱本系修業規則。(不同意者請勿申請)',
 NULL, NULL),
('財經法律學系',
 '1. 申請人原系國文及外國語文成績須達60分以上',
 '2. 申請時前一學期學業總成績占該班前50%',
 '3. 轉入後不得提高編級',
 '4. 筆試時間訂於 114 年 4 月 23 日(三)13:40-15:30，考試地點及注意事項將於考前一週公告，請自行至本系網頁查詢',
 '5. (1)本系學生須於畢業前符合法律學院學生學習護照之學習認證標準。(2)畢業學分中須含 1 門 2學分之全英語課程。(3)其餘畢業條件請參閱本系修業規則。(不同意者請勿申請)',NULL),
('心理學系',
 '1. 申請時請附：轉系申請書、中文歷年排名成績單、轉系專用個人資料表',
 '2. 通過筆試始得參加口試',
 '3. 口試名額至多10名',
 '4.學生成績未達錄取標準，本系可不足額錄取',
'5.筆試、口試時間及地點請至心理系網頁查詢',
'6.欲申請提高編級者，需具有本系輔系或雙主修身分，且達可提高編級標準'),
('社會工作學系',
 '1. 申請人前一學年平均成績須在班上前50%（一年級前一學期需前50%）',
 '2. 一年級學生上學期國文及英文成績須在70分以上',
 '3. 經錄取後不得提高編級',
 '4. 資料審查、口試資訊請至社會工作學系網頁最新消息查詢',
 '5. 轉系生請至社工系網頁查詢英檢門檻及實習規定；本系於「社會工作實習：導論」課程進行實習協調，請修畢實習協調比序科目，並注意實習擋修課程與實習領域先修課程之規定',Null),
('天主教研修學士學位學程',
 '1.資料審查內容如下，請於申請一併繳交：(1)含排名之歷年成績單。(2)個人資料表內含個人基本資料、家庭成員概況、學習規劃、自傳(招生用個人資料表格請參閱本學程網站 bpcs.fju.edu.tw)',
 NULL, NULL, NULL, NULL, NULL),
('社會學系',
 '申請前一學期學業總平均須達70分以上',
 '以原就讀學系之成績擇優錄取，必要時本系得訂定配分比率或轉以口試方式決定錄取人選',
 '申請轉入本系就讀經核准後，不得要求提高編級',
 NULL, NULL, NULL),
('經濟學系',
 '1. 資料審查內容：含排名之歷年成績單、簡歷、自傳（詳述申請動機、學測成績、卓越能力及得獎紀錄）',
 '2. 口試之詳細時間、地點公布於經濟系網頁',
 '3. 學生成績未達取標準，可不足額錄取',
 '4. 錄取後不得提高編級',
 NULL, NULL),
('宗教學系',
'1.考試科目：口試(每人二十分鐘)',
'2.繳交文件：自傳-說明轉系原由(申請時繳交)',
'3.報到地點：宗教學系辦公室(羅耀拉三樓 339 室)',
'4.報到時間：114 年 4 月 16 日(三)中午 12:10
5.考試時間：114 年 4 月 16 日(三)中午 12:10',
'6.考試地點：羅耀拉三樓 340 室',
'7.準備方向：宗教學基本知識、讀書計畫'),
('金融與國際企業學系',
 '1. 資料審查內容：含排名之歷年成績單（前一學期前30%）、簡歷、自傳（詳述卓越能力及得獎紀錄）、讀書計畫（2–4頁）、全民英檢中級或同等檢定證明',
 '2. 口試時間：114年4月23日（詳細時間、地點 114 年 4 月 18 日(五)公布於金融國企系網頁）',
 NULL, NULL, NULL, NULL),
('企業管理學系',
 '1. 含排名之歷年成績單（前一學期前50%）',
 '2. 自傳',
 '3. 經資料審查符合者始得參加口試',
 '4. 依總成績高低錄取，可不足額錄取',
 '5. 口試時間及地點請於報名申請期間至企管系網頁查詢', NULL),
('會計學系',
 '1. 資料審查內容：含排名之歷年成績單（前一學期前40%）、簡歷、自傳（詳述學測成績、卓越能力及得獎紀錄）',
 '2. 經審查通過後始得參加口試',
 '3. 依總成績高低依序錄取，可不足額錄取',
 '4.  114 年 4 月 17 日(四)中午 12 點 40 分開始面試。面試報到地點及順序將於 114 年 4 月 11 日公告於本系網站',
 NULL, NULL),
('資訊管理學系',
 '1. 資料審查內容：自傳（說明轉系動機，A4 1–2頁）、歷年成績單（含排名）',
 '2. 依總成績高低依序錄取，可不足額錄取',
 '3. 面試時間及地點請至資訊管理學系網頁查詢',
 '4. 經錄取後，不得以學分抵免之原因，要求提高編級',
 '5. 本系修業規則請務必上網查詢並詳閱',Null),
('統計資訊學系',
 '1. 成績未達錄取標準，可不足額錄取',
 '2. 經錄取後，不得以學分抵免之原因，要求提高編級',
 '3. 筆試、口試時間及地點請至統計資訊學系網頁最新消息查詢',
 NULL, NULL, NULL),
('醫學資訊與創新應用學士學位學程',
 NULL, NULL, NULL,NULL, NULL, NULL),
('電機工程學系',
 NULL, NULL,NULL, NULL, NULL, NULL),
('數學系資訊數學組',
 NULL,NULL, NULL, NULL, NULL, NULL),
('數學系應用數學組',
 NULL, NULL, NULL, NULL, NULL, NULL),
('生命科學系',
 NULL, NULL, NULL, NULL, NULL, NULL),
('物理學系物理組',
 NULL, NULL, NULL, NULL, NULL, NULL),
('物理學系光電物理組',
  NULL, NULL, NULL, NULL, NULL, NULL),
('物理學系電子物理組',
  NULL, NULL, NULL, NULL, NULL, NULL),
('化學系',
  NULL, NULL, NULL, NULL, NULL, NULL),
('資訊工程學系',
  NULL, NULL, NULL, NULL, NULL, NULL),
('人工智慧與資訊安全學士學位學程',
  NULL, NULL, NULL, NULL, NULL, NULL),
('跨領域全英語學士學位學程',
 '1、本學程僅招收境外轉系生(僑生、港澳生、國際學生)',
'2、申請轉入本學程就讀經核准後，不得要求提高編級',
'3、 學生成績未達錄取標準，本學程保有可不足額錄取', NULL, NULL, NULL);
-- C.1 系所主表
CREATE TABLE Department (
  department_id   INT AUTO_INCREMENT PRIMARY KEY,
  department_name VARCHAR(255) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- C.2 備註從表
CREATE TABLE DepartmentRemark (
  remark_id     INT AUTO_INCREMENT PRIMARY KEY,
  department_id INT NOT NULL,
  remark_order  TINYINT NOT NULL,
  remark_text   TEXT NOT NULL,
  FOREIGN KEY (department_id)
    REFERENCES Department(department_id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- C.3 標籤表（包含考試與其他審查／門檻標籤）
CREATE TABLE ExamType (
  exam_type_id   INT AUTO_INCREMENT PRIMARY KEY,
  exam_type_name VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- C.4 系所 vs 標籤 多對多
CREATE TABLE DepartmentExamType (
  department_id INT NOT NULL,
  exam_type_id  INT NOT NULL,
  PRIMARY KEY (department_id, exam_type_id),
  FOREIGN KEY (department_id) REFERENCES Department(department_id) ON DELETE CASCADE,
  FOREIGN KEY (exam_type_id)  REFERENCES ExamType(exam_type_id)  ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------------
-- D. 正規化：搬資料並自動打標籤
-- D.1 匯入 Department
INSERT IGNORE INTO Department (department_name)
SELECT DISTINCT department_name
FROM DepartmentRemarksSplit;

-- D.2 拆欄位搬入 DepartmentRemark
INSERT INTO DepartmentRemark (department_id, remark_order, remark_text)
SELECT d.department_id, 1, s.remark1
  FROM DepartmentRemarksSplit s
  JOIN Department d USING (department_name)
 WHERE s.remark1 IS NOT NULL
UNION ALL
SELECT d.department_id, 2, s.remark2
  FROM DepartmentRemarksSplit s
  JOIN Department d USING (department_name)
 WHERE s.remark2 IS NOT NULL
UNION ALL
SELECT d.department_id, 3, s.remark3
  FROM DepartmentRemarksSplit s
  JOIN Department d USING (department_name)
 WHERE s.remark3 IS NOT NULL
UNION ALL
SELECT d.department_id, 4, s.remark4
  FROM DepartmentRemarksSplit s
  JOIN Department d USING (department_name)
 WHERE s.remark4 IS NOT NULL
UNION ALL
SELECT d.department_id, 5, s.remark5
  FROM DepartmentRemarksSplit s
  JOIN Department d USING (department_name)
 WHERE s.remark5 IS NOT NULL
UNION ALL
SELECT d.department_id, 6, s.remark6
  FROM DepartmentRemarksSplit s
  JOIN Department d USING (department_name)
 WHERE s.remark6 IS NOT NULL
;

-- D.3 插入所有標籤
INSERT IGNORE INTO ExamType (exam_type_name) VALUES
  ('口試'),
  ('筆試'),
  ('書面審查'),
  ('國文平均成績'),
  ('英文平均成績'),
  ('成績排名')
;

-- D.4 自動打標籤
-- 口試
INSERT IGNORE INTO DepartmentExamType (department_id, exam_type_id)
SELECT DISTINCT d.department_id, et.exam_type_id
  FROM Department d
  JOIN ExamType et ON et.exam_type_name = '口試'
  JOIN DepartmentRemark r ON r.department_id = d.department_id
 WHERE r.remark_text LIKE '%口試%' OR r.remark_text LIKE '%面試%';
-- 筆試
INSERT IGNORE INTO DepartmentExamType (department_id, exam_type_id)
SELECT DISTINCT d.department_id, et.exam_type_id
  FROM Department d
  JOIN ExamType et ON et.exam_type_name = '筆試'
  JOIN DepartmentRemark r ON r.department_id = d.department_id
 WHERE r.remark_text LIKE '%筆試%';
-- 書面審查
INSERT IGNORE INTO DepartmentExamType (department_id, exam_type_id)
SELECT DISTINCT d.department_id, et.exam_type_id
  FROM Department d
  JOIN ExamType et ON et.exam_type_name = '書面審查'
  JOIN DepartmentRemark r ON r.department_id = d.department_id
 WHERE r.remark_text LIKE '%審查%' AND r.remark_text NOT LIKE '%作品集%';
-- 國文平均成績
INSERT IGNORE INTO DepartmentExamType (department_id, exam_type_id)
SELECT DISTINCT d.department_id, et.exam_type_id
  FROM Department d
  JOIN ExamType et ON et.exam_type_name = '國文平均成績'
  JOIN DepartmentRemark r ON r.department_id = d.department_id
 WHERE r.remark_text LIKE '%國文%' AND (r.remark_text LIKE '%平均%' OR r.remark_text LIKE '%成績%');
-- 英文平均成績
INSERT IGNORE INTO DepartmentExamType (department_id, exam_type_id)
SELECT DISTINCT d.department_id, et.exam_type_id
  FROM Department d
  JOIN ExamType et ON et.exam_type_name = '英文平均成績'
  JOIN DepartmentRemark r ON r.department_id = d.department_id
 WHERE r.remark_text LIKE '%英文%' AND (r.remark_text LIKE '%平均%' OR r.remark_text LIKE '%成績%');
-- 成績排名
INSERT IGNORE INTO DepartmentExamType (department_id, exam_type_id)
SELECT DISTINCT d.department_id, et.exam_type_id
  FROM Department d
  JOIN ExamType et ON et.exam_type_name = '成績排名'
  JOIN DepartmentRemark r ON r.department_id = d.department_id
 WHERE r.remark_text LIKE '%排名%';
DROP TABLE DepartmentRemarksSplit;
/*
-- 列出所有可用標籤
SELECT exam_type_id, exam_type_name
  FROM ExamType
ORDER BY exam_type_name;

-- 列出每個系所目前擁有的標籤
SELECT d.department_id,
       d.department_name,
       GROUP_CONCAT(e.exam_type_name ORDER BY e.exam_type_name SEPARATOR ', ') AS tags
  FROM Department d
  LEFT JOIN DepartmentExamType det
    ON d.department_id = det.department_id
  LEFT JOIN ExamType e
    ON det.exam_type_id = e.exam_type_id
 GROUP BY d.department_id, d.department_name;

-- 任一標籤（OR）搜尋：只要包含所選標籤中任意一個
-- 假設使用者勾選了「口試」和「筆試」
SELECT DISTINCT d.department_id,
       d.department_name
  FROM Department d
  JOIN DepartmentExamType det
    ON d.department_id = det.department_id
  JOIN ExamType e
    ON det.exam_type_id = e.exam_type_id
 WHERE e.exam_type_name IN ('口試', '筆試')
 ORDER BY d.department_name;

-- 同時符合多標籤（AND）搜尋：必須同時擁有所有選定標籤
-- 假設要同時「口試」＆「筆試」
SELECT d.department_id,
       d.department_name
  FROM Department d
  JOIN DepartmentExamType det
    ON d.department_id = det.department_id
  JOIN ExamType e
    ON det.exam_type_id = e.exam_type_id
 WHERE e.exam_type_name IN ('口試', '筆試')
 GROUP BY d.department_id, d.department_name
 HAVING COUNT(DISTINCT e.exam_type_name) = 2
 ORDER BY d.department_name;*/

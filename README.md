# 輔仁大學轉系系統

這是一個用於輔仁大學學生查詢和比較不同科系轉系資訊的系統。

## 功能特點

- 科系資訊查詢
- 科系比較功能
- 用戶註冊和登入
- 密碼重置功能
- 響應式設計

## 系統需求

- PHP >= 7.4.0
- MySQL >= 5.7
- Composer
- Apache/Nginx 網頁伺服器
- Mailgun 帳號（用於發送郵件）

## 安裝步驟

1. 克隆專案：
```bash
git clone https://github.com/your-username/SA.git
cd SA
```

2. 安裝依賴：
```bash
composer install
```

3. 配置環境：
   - 複製 `config.example.php` 為 `config.php`
   - 修改 `config.php` 中的資料庫配置
   - 設置 Mailgun API 金鑰和域名

4. 初始化資料庫：
   - 確保 MySQL 服務正在運行
   - 訪問 `http://localhost/SA/init_db.php` 創建資料庫和表

5. 配置網頁伺服器：
   - 將網站根目錄指向專案的 `public` 目錄
   - 確保 `.htaccess` 文件被正確加載

## 目錄結構

```
SA/
├── config.php           # 配置文件
├── init_db.php         # 資料庫初始化
├── index.php           # 主頁
├── login.php           # 登入頁面
├── register.php        # 註冊頁面
├── reset_password.php  # 密碼重置頁面
├── department_detail.php # 科系詳情頁面
├── compare.php         # 科系比較頁面
├── includes/           # 包含文件
│   ├── header.php
│   ├── footer.php
│   └── functions.php
├── css/               # 樣式文件
├── js/                # JavaScript 文件
└── vendor/            # Composer 依賴
```

## 使用說明

1. 訪問首頁：
   - 瀏覽 `http://localhost/SA/index.php`
   - 可以查看所有科系列表
   - 使用搜索功能查找特定科系

2. 查看科系詳情：
   - 點擊科系名稱進入詳情頁面
   - 查看招生人數、考試科目等信息
   - 可以將科系加入比較列表

3. 比較科系：
   - 選擇 2-3 個科系進行比較
   - 點擊「開始比較」按鈕
   - 查看詳細的比較結果

4. 用戶功能：
   - 註冊新帳號
   - 登入系統
   - 重置密碼

## 安全措施

- 使用 PDO 預處理語句防止 SQL 注入
- 密碼使用 bcrypt 加密
- 實現 CSRF 防護
- 使用安全的會話管理
- 限制文件上傳大小
- 隱藏敏感配置文件

## 開發者

- 您的名字
- 聯繫方式

## 授權

MIT License 
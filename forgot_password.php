<?php
session_start();
$pageTitle = '忘記密碼';
include 'header.php';
?>


<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card w-100" style="max-width: 500px;">
        <div class="card-body">
            <h4 class="card-title text-center mb-4">忘記密碼</h4>

            <form method="POST" action="send_reset_email.php">
                <div class="mb-3">
                    <label for="email" class="form-label">請輸入註冊 Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <button type="submit" class="btn w-100" style="background-color:rgb(148, 164, 189);">寄送重設連結</button>
            </form>

         
            <div class="mt-3 text-center">
                <a href="login.php" class="btn btn-outline-secondary btn-sm">返回登入</a>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

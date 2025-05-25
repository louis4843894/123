<?php
session_start();
$pageTitle = '忘記密碼';
include 'header.php';
?>

<div class="container-fluid vh-100 d-flex align-items-center justify-content-center" style="margin-top: -60px;">
    <div class="row justify-content-center w-100">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-body px-5 py-4">
                    <h2 class="card-title mb-4 text-center">忘記密碼</h2>

                    <form method="POST" action="send_reset_email.php" class="mb-3">
                        <div class="mb-3">
                            <label for="email" class="form-label">請輸入註冊 Email</label>
                            <input type="email" class="form-control mb-3" name="email" required>
                        </div>
                        <button type="submit" class="btn w-100 mb-3 text-white" style="background-color:rgb(148, 164, 189);">寄送重設連結</button>
                    </form>

                    <div class="d-flex justify-content-center">
                        <a href="login.php" class="btn" style="background-color: #E9ECEF; color: #333; border: 1px solid #DDD;">
                            返回登入
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn:hover {
    background-color: #DDE0E3 !important;
    color: #333 !important;
}
.btn.text-white:hover {
    background-color: rgb(133, 148, 171) !important;
    color: white !important;
}
</style>

<?php include 'footer.php'; ?>

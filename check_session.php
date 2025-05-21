<?php
session_start();
echo "Session 內容：<br>";
echo "user_id: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '未設定') . "<br>";
echo "name: " . (isset($_SESSION['name']) ? $_SESSION['name'] : '未設定') . "<br>";
echo "role: " . (isset($_SESSION['role']) ? $_SESSION['role'] : '未設定') . "<br>";
?> 
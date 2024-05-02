<?php
session_start(); // بدء الجلسة

// إنهاء الجلسة دون حذف البيانات المخزنة فيها
session_destroy();

// إعادة توجيه المستخدم إلى الصفحة الحالية أو أي صفحة أخرى بعد تسجيل الخروج
//header("Location: " . $_SERVER['HTTP_REFERER']);
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>


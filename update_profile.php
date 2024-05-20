<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'contc.php'; // توصيل قاعدة البيانات

// التحقق من وجود معرف المستخدم في الجلسة
if (!isset($_SESSION['UserID'])) {
    header('Location: login1.php'); // توجيه إلى صفحة الدخول إذا لم يكن المستخدم مسجلاً
    exit();
}

$user_id = $_SESSION['UserID'];

// استعلام لاستعادة معلومات الحساب
$stmt_user = $conn->prepare("SELECT * FROM users WHERE UserID = ?");
$stmt_user->execute([$user_id]);
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // إذا لم يتم العثور على المستخدم، عرض رسالة الخطأ
    $_SESSION['error_message'] = "المستخدم غير موجود";
    header("Location: about.php");
    exit();
}

// التحقق من كلمة المرور
if (isset($_POST['password'])) {
    $entered_password = $_POST['password'];
    if ($entered_password === $user['Password']) {  // التحقق من كلمة المرور بدون تشفير
        // تحديث البيانات
        $new_username = $_POST['username'] ?? '';
        $new_email = $_POST['email'] ?? '';
        $new_phone = $_POST['phone'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_new_password = $_POST['confirm_new_password'] ?? '';

        if (!empty($new_password) && ($new_password !== $confirm_new_password)) {
            $_SESSION['error_message'] = "كلمة المرور الجديدة وتأكيدها غير متطابقتين";
            header("Location: about.php");
            exit();
        }

        // استعد الاستعلام لتحديث البيانات
        if (!empty($new_password)) {
            $update_stmt = $conn->prepare("UPDATE users SET UserName = ?, Email = ?, Phone = ?, Password = ? WHERE UserID = ?");
            $update_stmt->execute([$new_username, $new_email, $new_phone, $new_password, $user_id]);
        } else {
            $update_stmt = $conn->prepare("UPDATE users SET UserName = ?, Email = ?, Phone = ? WHERE UserID = ?");
            $update_stmt->execute([$new_username, $new_email, $new_phone, $user_id]);
        }

        // تعيين رسالة النجاح في الـ $_SESSION
        $_SESSION['success_message'] = "تم تعديل البيانات بنجاح";

        // إعادة توجيه المستخدم
        header("Location: about.php");
        exit(); // يجب استخدام exit() بعد header() لمنع استمرار تنفيذ النص الحالي من الكود
    } else {
        // في حالة فشل التحقق من كلمة المرور، عرض رسالة الخطأ
        $_SESSION['error_message'] = "كلمة المرور غير صحيحة";
        header("Location: about.php");
        exit(); // يجب استخدام exit() بعد header() لمنع استمرار تنفيذ النص الحالي من الكود
    }
} else {
    $_SESSION['error_message'] = "لم يتم إدخال كلمة المرور";
    header("Location: about.php");
    exit(); // يجب استخدام exit() بعد header() لمنع استمرار تنفيذ النص الحالي من الكود
}
?>

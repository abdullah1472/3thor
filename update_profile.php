<?php
session_start();
require 'contc.php'; // توصيل قاعدة البيانات

// التحقق من وجود معرف المستخدم في الجلسة
if(isset($_SESSION['UserID'])) {
    // استعلام لاستعادة معلومات الحساب
    $user_id = $_SESSION['UserID'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE UserID = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // التحقق من كلمة المرور
    if ($_POST['password-confirm'] === $user['Password']) {
        // تحديث البيانات
        $new_email = $_POST['email'];
        $new_phone = $_POST['phone'];

        // استعد الاستعلام لتحديث البيانات
        $update_stmt = $conn->prepare("UPDATE users SET Email = ?, Phone = ? WHERE UserID = ?");
        $update_stmt->execute([$new_email, $new_phone, $user_id]);

        // تعيين رسالة النجاح في الـ $_SESSION
        $_SESSION['success_message'] = "تم تعديل البيانات بنجاح";
        
        // إعادة توجيه المستخدم
        header("Location: about.php");
        exit; // يجب استخدام exit() بعد header() لمنع استمرار تنفيذ النص الحالي من الكود
    } else {
        // في حالة فشل التحقق من كلمة المرور، عرض رسالة الخطأ
        $_SESSION['error_message'] = "كلمة المرور غير صحيحة";
        
        // إعادة توجيه المستخدم
        header("Location: about.php");
        exit; // يجب استخدام exit() بعد header() لمنع استمرار تنفيذ النص الحالي من الكود
    }
}
?>

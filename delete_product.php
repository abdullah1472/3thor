<?php
session_start();

// التحقق مما إذا كان المستخدم مسجل دخوله
if (!isset($_SESSION['UserID'])) {
    header("Location: login1.php");
    exit();
}

if (isset($_POST['product_id'])) {
    require 'contc.php';

    try {
        // بدء معاملة
        $conn->beginTransaction();

        // حذف الصور المرتبطة
        $stmt_delete_images = $conn->prepare("DELETE FROM image WHERE ProductID = ?");
        $stmt_delete_images->execute([$_POST['product_id']]);

        // حذف المنتج
        $stmt_delete_product = $conn->prepare("DELETE FROM product WHERE ProductID = ? AND UserID = ?");
        $stmt_delete_product->execute([$_POST['product_id'], $_SESSION['UserID']]);

        // إتمام المعاملة
        $conn->commit();

        header("Location: about.php");
        exit();
    } catch (PDOException $e) {
        // التراجع عن المعاملة في حالة حدوث خطأ
        $conn->rollBack();
        error_log("Error: " . $e->getMessage()); // سجل الخطأ بدلاً من عرضها
        echo "An error occurred while deleting the product. Please try again later."; // رسالة خطأ عامة
    }
} else {
    header("Location: about.php");
    exit();
}
?>

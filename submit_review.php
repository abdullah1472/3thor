<?php
require 'contc.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userID = $_POST['userID'];
    $productID = $_POST['productID'];
    $rating = $_POST['rating'];
    $reviewText = $_POST['reviewText'];

    // التحقق من وجود المستخدم والمنتج في قاعدة البيانات
    $sqlCheckUser = "SELECT COUNT(*) FROM users WHERE UserID = ?";
    $stmtCheckUser = $conn->prepare($sqlCheckUser);
    $stmtCheckUser->execute([$userID]);
    $userExists = $stmtCheckUser->fetchColumn();

    $sqlCheckProduct = "SELECT COUNT(*) FROM product WHERE ProductID = ?";
    $stmtCheckProduct = $conn->prepare($sqlCheckProduct);
    $stmtCheckProduct->execute([$productID]);
    $productExists = $stmtCheckProduct->fetchColumn();

    if ($userExists && $productExists) {
        $sqlInsertReview = "INSERT INTO reviews (UserID, ProductID, Rating, ReviewText) VALUES (?, ?, ?, ?)";
        $stmtInsertReview = $conn->prepare($sqlInsertReview);
        if (!$stmtInsertReview) {
            die('Query preparation failed: ' . $conn->errorInfo()[2]);
        }
        $stmtInsertReview->execute([$userID, $productID, $rating, $reviewText]);
        if ($stmtInsertReview->errorCode() !== '00000') {
            die('Query execution failed: ' . $stmtInsertReview->errorInfo()[2]);
        }
        header('Location: work-single.php?id=' . $productID);
        exit();
    } else {
        echo "User or Product not found.";
    }
}
?>

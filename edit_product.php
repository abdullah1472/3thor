<?php
session_start();

if (!isset($_SESSION['UserID'])) {
    header("Location: login1.php");
    exit();
}

if (isset($_POST['product_id']) && isset($_POST['title']) && isset($_POST['description']) && isset($_POST['price']) && isset($_POST['location']) && isset($_POST['category'])) {
    require 'contc.php';

    try {
        $stmt = $conn->prepare("UPDATE product SET Title = ?, Description = ?, Price = ?, Location = ?, Category = ? WHERE ProductID = ? AND UserID = ?");
        $stmt->execute([$_POST['title'], $_POST['description'], $_POST['price'], $_POST['location'], $_POST['category'], $_POST['product_id'], $_SESSION['UserID']]);
        header("Location: about.php");
        exit();
    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
        echo "An error occurred while updating the product. Please try again later.";
    }
} else {
    header("Location: about.php");
    exit();
}
?>

<?php// require 'Functions.php'; ?>
<?php //require 'Sessions.php'; ?>


<?php
require 'contc.php';

//if (isset($_SESSION['UserId'])) {
  //  redirect_to("index1.php");
//}
// بدء الجلسة
session_start();

// التحقق من تقديم بيانات الدخول وتخزينها في الجلسة
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send'])) {

    $user = $_POST['user'];
    $pass = $_POST['pass'];

    // استعلام SQL للتحقق من تطابق المعلمات
    $stmt = $conn->prepare("SELECT * FROM users WHERE UserName=:user AND Password=:pass");
    $stmt->bindParam(':user', $user);
    $stmt->bindParam(':pass', $pass);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // تخزين اسم المستخدم في الجلسة
        $_SESSION['user'] = $user;
        $_SESSION['UserID'] = $result['UserID'];
    
        


        // توجيه المستخدم إلى الصفحة الأخرى
        header("Location: index1.php");
        exit;
    } else {
        echo "لم يتم العثور على مستخدم مطابق!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صفحة إنشاء حساب</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f1f1f1;
        }
        .container {
            width: 300px;
            margin: 100px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        input[type="submit"] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>عثور</h2>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <input type="text" name="user" placeholder="الاسم">
            <input type="password" name="pass" placeholder="كلمة المرور">
            <input type="submit" name="send" value=" دخول">
        </form>
        <button class="btn-login" onclick="window.location.href='/MyPortfolio'">العودة إلى صفحة تسجيل الدخول</button>
    </div>
</body>
</html>

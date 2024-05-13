<?php //require 'Functions.php'; ?>
<?php //require 'Sessions.php'; ?>


<?php


session_start(); // بدء الجلسة

require 'contc.php';

if (isset($_POST['send'])) {
    // جلب البيانات المدخلة من النموذج
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // التحقق من وجود بيانات مدخلة
    if (empty($user) || empty($pass) || empty($email) || empty($phone)) {
        echo 'يرجى تعبئة جميع الحقول.';
        exit; // إيقاف التنفيذ في حالة عدم وجود بيانات
    }

    // التحقق من عدم وجود اسم مستخدم مماثل في قاعدة البيانات
    $sql_check_user = "SELECT * FROM users WHERE UserName = ?";
    $stmt_check_user = $conn->prepare($sql_check_user);
    $stmt_check_user->execute([$user]);
    if ($stmt_check_user->rowCount() > 0) {
        echo 'اسم المستخدم موجود مسبقاً.';
        exit; // إيقاف التنفيذ في حالة وجود اسم مستخدم مماثل
    }

    // التحقق من عدم وجود بريد إلكتروني مماثل في قاعدة البيانات
    $sql_check_email = "SELECT * FROM users WHERE Email = ?";
    $stmt_check_email = $conn->prepare($sql_check_email);
    $stmt_check_email->execute([$email]);
    if ($stmt_check_email->rowCount() > 0) {
        echo 'البريد الإلكتروني مستخدم مسبقاً.';
        exit; // إيقاف التنفيذ في حالة وجود بريد إلكتروني مماثل
    }

    // بدء الجلسة
    session_start();

    // استعداد الاستعلام لإدراج البيانات في قاعدة البيانات
    $sql_insert_user = "INSERT INTO users (UserName, Password, Email, Phone) VALUES (?, ?, ?, ?)";
    $stmt_insert_user = $conn->prepare($sql_insert_user);

    // تنفيذ عملية الإدراج
    $data = $stmt_insert_user->execute([$user, $pass, $email, $phone]);
    if ($data) {
        $_SESSION['user'] = $user; // تخزين اسم المستخدم في الجلسة
        
        header("Location: login1.php"); // توجيه المستخدم إلى الصفحة الثانية
        exit;
    } else {
        echo 'حدث خطأ أثناء إضافة المستخدم.';
    }
}
?>









<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>عثور</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=https://fonts.googleapis.com/css?family=Inconsolata:400,500,600,700|Raleway:400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet"><style>
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
        input[type="text"], input[type="password"], input[type="email"], input[type="tel"] {
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
        <h2>تسجيل الدخول</h2>
        <form action="" method="post">
            <input type="email" name="email" placeholder="البريد الإلكتروني">
            <input type="tel" name="phone" placeholder="رقم الجوال">
            <input type="text" name="user" placeholder="اسم المستخدم">
            <input type="password" name="pass" placeholder="كلمة المرور">
            <input type="submit" name="send" value="إنشاء حساب جدي">
        </form>
        <button class="btn-create-account" onclick="window.location.href='/MyPortfolio/login1.php'"> الدخول </button>
      </div>
      </body>




</html>


<?php
session_start(); // بدء الجلسة

require 'contc.php';

if (isset($_POST['send'])) {
    // جلب البيانات المدخلة من النموذج
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    $repeat_pass = $_POST['repeat_pass'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // التحقق من وجود بيانات مدخلة
    if (empty($user) || empty($pass) || empty($email) || empty($phone)) {
        echo 'يرجى تعبئة جميع الحقول.';
        exit; // إيقاف التنفيذ في حالة عدم وجود بيانات
    }
    
    // التحقق من مطابقة كلمة المرور
    if ($pass !== $repeat_pass) {
        echo 'كلمات المرور غير متطابقة.';
        exit;
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
<html lang="en">

<head>
 <title></title>
</head>

<!-- Font Awesome -->
<link
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
  rel="stylesheet"
/>
<!-- Google Fonts -->
<link
  href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
  rel="stylesheet"
/>
<!-- MDB -->
<link
  href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.min.css"
  rel="stylesheet"
/>
<!-- MDB -->
<script
  type="text/javascript"
  src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.umd.min.js"
></script>
<body>
    
<section class="vh-100" style="background-color: #eee;">
  <div class="container h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-lg-12 col-xl-11">
        <div class="card text-black" style="border-radius: 25px;">
          <div class="card-body p-md-5">
            <div class="row justify-content-center">
              <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">تسجيل حساب</p>

                <form class="mx-1 mx-md-4" method="post">

                  <div class="d-flex flex-row align-items-center mb-4">
                    <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                    <div data-mdb-input-init class="form-outline flex-fill mb-0">
                      <input type="text" name="user" id="form3Example1c" class="form-control" />
                      <label class="form-label" for="form3Example1c">اسمك كامل</label>
                    </div>
                  </div>

                  <div class="d-flex flex-row align-items-center mb-4">
                    <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                    <div data-mdb-input-init class="form-outline flex-fill mb-0">
                      <input type="email" name="email" id="form3Example3c" class="form-control" />
                      <label class="form-label" for="form3Example3c">الايميل</label>
                    </div>
                  </div>

                  <div class="d-flex flex-row align-items-center mb-4">
                  <i class="fas fa-mobile-screen-button fa-lg me-3 fa-fw"></i>
                    
                    <div data-mdb-input-init class="form-outline flex-fill mb-0">
                      <input type="phone" name="phone" id="form3Example4c" class="form-control" />
                      <label class="form-label" for="form3Example4c">رقم الجوال</label>
                    </div>
                  </div>

                  <div class="d-flex flex-row align-items-center mb-4">
                    <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                    <div data-mdb-input-init class="form-outline flex-fill mb-0">
                      <input type="password" name="pass" id="form3Example5c" class="form-control" />
                      <label class="form-label" for="form3Example5c">كلمة المرور</label>
                    </div>
                  </div>

                  <div class="d-flex flex-row align-items-center mb-4">
                    <i class="fas fa-key fa-lg me-3 fa-fw"></i>
                    <div data-mdb-input-init class="form-outline flex-fill mb-0">
                      <input type="password" name="repeat_pass" id="form3Example6c" class="form-control" />
                      <label class="form-label" for="form3Example6c">تأكيد كلمة المرور</label>
                    </div>
                  </div>

                  <div class="form-check d-flex justify-content-center mb-5">
                    <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3c" />
                    <label class="form-check-label" for="form2Example3">
                      I agree all statements in <a href="#!">Terms of service</a>
                    </label>
                  </div>

                  <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                    <button type="submit" name="send" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg">تسجيل</button>
                    <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                    <button type="submit" name="send" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg">دخول</button>
                  </div>
                  </div>
                  

                </form>

              </div>
              <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">

                <img src="assets/img/logoname.png"
                  class="img-fluid" alt="Sample image">

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</body>

</html>

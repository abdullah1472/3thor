<?php
require 'contc.php';

// بدء الجلسة
session_start();

// التحقق من تقديم بيانات الدخول وتخزينها في الجلسة
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send'])) {

    $user = $_POST['username']; // تعديل الاسم ليتطابق مع الحقل في النموذج
    $pass = $_POST['password']; // تعديل الاسم ليتطابق مع الحقل في النموذج

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
    <title>Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.min.css" rel="stylesheet" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.umd.min.js"></script>
</head>

<body>
    <section class="vh-100" style="background-color: #eee;">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-12 col-xl-11">
                    <div class="card text-black" style="border-radius: 25px;">
                        <div class="card-body p-md-5">
                            <div class="row justify-content-center">
                                <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
                                    <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Log in</p>
                                    <form class="mx-1 mx-md-4" method="POST" action="">

                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                            <div data-mdb-input-init class="form-outline flex-fill mb-0">
                                                <input type="text" id="form3Example1c" class="form-control" name="username" required />
                                                <label class="form-label" for="form3Example1c">Your Name</label>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                                            <div data-mdb-input-init class="form-outline flex-fill mb-0">
                                                <input type="password" id="form3Example4c" class="form-control" name="password" required />
                                                <label class="form-label" for="form3Example4c">Password</label>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                            <button type="submit" name="send" class="btn btn-primary btn-lg">تسجيل دخول</button>
                                            <div class="d-flex justify-content-center mx-5 mb-3 mb-lg-1">
                                                <button type="button" onclick="window.location.href='reg.php'" class="btn btn-secondary text-dark btn-lg">إنشاء حساب</button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                                <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">
                                    <img src="assets/img/logoname.png" class="img-fluid" alt="Sample image">
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

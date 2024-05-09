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
    if (isset($_POST['password-confirm'])) {
        $entered_password = $_POST['password-confirm'];
        $stored_password = $user['Password'];

        if ($entered_password === $stored_password) {
            // تحديث البيانات
            $new_email = $_POST['email'];
            $new_phone = $_POST['phone'];

            // استعد الاستعلام لتحديث البيانات
            $update_stmt = $conn->prepare("UPDATE users SET Email = ?, Phone = ? WHERE UserID = ?");
            $update_stmt->execute([$new_email, $new_phone, $user_id]);

            // عرض رسالة نجاح
            echo "<script>alert('تم تعديل البيانات بنجاح');</script>";
        } else {
            // في حالة فشل التحقق من كلمة المرور، عرض رسالة خطأ
            echo "<script>alert('كلمة المرور غير صحيحة');</script>";
        }
    }
}

if(isset($_SESSION['success_message'])) {
    echo "<script>alert('" . $_SESSION['success_message'] . "');</script>";
    unset($_SESSION['success_message']); // يجب حذف الرسالة بعد عرضها لعدم عرضها مرة أخرى
} elseif(isset($_SESSION['error_message'])) {
    echo "<script>alert('" . $_SESSION['error_message'] . "');</script>";
    unset($_SESSION['error_message']); // يجب حذف الرسالة بعد عرضها لعدم عرضها مرة أخرى
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>MyPortfolio Bootstrap Template - About</title>
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
    <link href="assets/css/style.css" rel="stylesheet">

    <!-- =======================================================
    * Template Name: MyPortfolio
    * Template URL: https://bootstrapmade.com/myportfolio-bootstrap-portfolio-website-template/
    * Updated: Mar 17 2024 with Bootstrap v5.3.3
    * Author: BootstrapMade.com
    * License: https://bootstrapmade.com/license/
    ======================================================== -->
</head>

<body>

    <!-- ======= Navbar ======= -->
    <div class="collapse navbar-collapse custom-navmenu" id="main-navbar">
        <div class="container py-2 py-md-5">
            <div class="row align-items-start">
                <div class="col-md-2">
                    <ul class="custom-menu">
                        <li><a href="index1.php">Home</a></li>
                        <li class="active"><a href="about.html">About Me</a></li>
                        <li><a href="services.html">Services</a></li>
                        <li><a href="works.html">Works</a></li>
                        <li><a href="contact.html">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-6 d-none d-md-block  mr-auto">
                    <div class="tweet d-flex">
                        <span class="bi bi-twitter text-white mt-2 mr-3"></span>
                        <div>
                            <p><em>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam necessitatibus incidunt ut officiis explicabo inventore. <br> <a href="#">t.co/v82jsk</a></em></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 d-none d-md-block">
                    <h3>Hire Me</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam necessitatibus incidunt ut officiisexplicabo inventore. <br> <a href="#">myemail@gmail.com</a></p>
                </div>
            </div>

        </div>
    </div>

    <nav class="navbar navbar-light custom-navbar">
        <div class="container">
            <a class="navbar-brand" href="index1.php">عثور</a>
            <a href="#" class="burger" data-bs-toggle="collapse" data-bs-target="#main-navbar">
                <span></span>
            </a>
        </div>
    </nav>

    <main id="main">

        <section class="section pb-5">
            <div class="container">
                <div class="row mb-5 align-items-end">
                    <div class="col-md-6" data-aos="fade-up">
                        <h2>About Me</h2>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-4 ml-auto order-2" data-aos="fade-up">
                        <div class="form-group">
                            <form method="POST" action="update_profile.php">

                                <label for="username">اسم المستخدم:</label>
                                <input type="text" class="form-control disabled" id="username" name="username" value="<?php echo"" .$user['UserName'].""; ?>" disabled>
                        </div>
                        <div class="d-flex mb-1">
                            <div class="form-group">
                                <label for="email">البريد الإلكتروني:</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['Email']; ?>" <?php if(isset($entered_password) && $entered_password == $stored_password) echo "enabled"; ?>>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phone">رقم الجوال:</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $user['Phone']; ?>" <?php if(isset($entered_password) && $entered_password == $stored_password) echo "enabled"; ?>>
                        </div>
                        <div class="form-group">
                            <label for="password">كلمة المرور:</label>
                            <input type="password" class="form-control" id="password" name="password" value="<?php echo"" . $user['Password'] .""; ?>" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">تأكيد كلمة المرور:</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                            </form>
                        </div>


                        <div class="col-md-3 mb-5 mb-md-0" data-aos="fade-up">
                            <p><img src="assets/img/popo.png" alt="Image" class="img-fluid"></p>

                            <p><a href="#" class="readmore">my</a></p>
                        </div>

                    </div>

                </div>

            </section>

            <!-- Rest of your HTML content -->

        </main><!-- End #main -->

        <!-- Vendor JS Files -->
        <script src="assets/vendor/aos/aos.js"></script>
        <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
        <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
        <script src="assets/vendor/php-email-form/validate.js"></script>

        <!-- Template Main JS File -->
        <script src="assets/js/main.js"></script>

    </body>

    </html>

<?php
session_start(); // بدء الجلسة

require 'contc.php';

$error_message = '';

if (isset($_POST['send'])) {
    // جلب البيانات المدخلة من النموذج
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    $repeat_pass = $_POST['repeat_pass'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // التحقق من وجود بيانات مدخلة
    if (empty($user) || empty($pass) || empty($email) || empty($phone)) {
        $error_message = 'يرجى تعبئة جميع الحقول.';
    } elseif ($pass !== $repeat_pass) {
        // التحقق من مطابقة كلمة المرور
        $error_message = 'كلمات المرور غير متطابقة.';
    } elseif (strlen($pass) < 5 || !preg_match('/[0-9]/', $pass)) {
        // التحقق من تعقيد كلمة المرور
        $error_message = 'كلمة المرور يجب أن تحتوي على الأقل 5 أحرف ورقم واحد على الأقل.';
    }
    elseif (!preg_match('/^0[0-9]{9}$/', $phone)){
      
        // رسالة خطأ أو معالجة الخطأ
        $error_message ='رقم الجوال غير صالح. يجب أن يبدأ بصفر ويتكون من 10 أرقام.';
    
    }
    
    else {
        // التحقق من عدم وجود اسم مستخدم مماثل في قاعدة البيانات
        $sql_check_user = "SELECT * FROM users WHERE UserName = ?";
        $stmt_check_user = $conn->prepare($sql_check_user);
        $stmt_check_user->execute([$user]);
        if ($stmt_check_user->rowCount() > 0) {
            $error_message = 'اسم المستخدم موجود مسبقاً.';
        }

        // التحقق من عدم وجود بريد إلكتروني مماثل في قاعدة البيانات
        $sql_check_email = "SELECT * FROM users WHERE Email = ?";
        $stmt_check_email = $conn->prepare($sql_check_email);
        $stmt_check_email->execute([$email]);
        if ($stmt_check_email->rowCount() > 0) {
            $error_message = 'البريد الإلكتروني مستخدم مسبقاً.';
        }

        // إذا لم تكن هناك أخطاء، متابعة إدخال البيانات
        if (empty($error_message)) {
            // استعداد الاستعلام لإدراج البيانات في قاعدة البيانات
            $sql_insert_user = "INSERT INTO users (UserName, Password, Email, Phone) VALUES (?, ?, ?, ?)";
            $stmt_insert_user = $conn->prepare($sql_insert_user);

            // تنفيذ عملية الإدراج
            $data = $stmt_insert_user->execute([$user, $pass, $email, $phone]);
            if ($data) {
                $_SESSION['user'] = $user; // تخزين اسم المستخدم في الجلسة

                header("Location: login.php"); // توجيه المستخدم إلى الصفحة الثانية
                exit;
            } else {
                $error_message = 'حدث خطأ أثناء إضافة المستخدم.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>تسجيل حساب</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.min.css" rel="stylesheet" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.umd.min.js"></script>
    <style>
        body {
            background-image: url('assets/img/bac5.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .form-outline .form-label.active {
            transform: translateY(-1.5rem) scale(0.8);
        }
        .form-outline .form-label {
            transition: transform 0.2s ease-out;
        }
        .error {
            color: red;
            font-size: 0.9em;
        }
        .alert {
            display: none;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $(".alert").fadeTo(5000, 500).slideUp(500, function () {
                $(".alert").slideUp(500);
            });

            // Ensure that the floating label works correctly
            $('.form-outline input').each(function () {
                if ($(this).val()) {
                    $(this).siblings('label').addClass('active');
                }
            });

            $('.form-outline input').on('input', function () {
                if ($(this).val()) {
                    $(this).siblings('label').addClass('active');
                } else {
                    $(this).siblings('label').removeClass('active');
                }
            });

            // Initialize MDB inputs
            document.querySelectorAll('.form-outline').forEach((formOutline) => {
                new mdb.Input(formOutline).init();
            });

            // عرض رسالة الخطأ إذا كانت موجودة
            var errorMessage = "<?php echo $error_message; ?>";
            if (errorMessage) {
                $("#error-alert").text(errorMessage).show();
            } else {
            $('#error-alert').hide();
        }
        
        });
    </script>
    <script>
function validatePhoneNumber(input) {
    // إزالة جميع الأحرف غير الأرقام
    input.value = input.value.replace(/[^0-9]/g, '');

    // إذا كان طول الإدخال أكبر من 10 أرقام، اقتصاص الإدخال إلى 10 أرقام
    if (input.value.length > 10) {
        input.value = input.value.slice(0, 10);
    }
}
</script>
</head>
<body>
    <section class="vh-100">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-12 col-xl-11">
                    <div class="card text-black" style="border-radius: 25px;">
                        <div class="card-body p-md-5">
                            <div class="row justify-content-center">
                                <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
                                    <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">تسجيل حساب</p>
                                    <form class="mx-1 mx-md-4" method="post">
                                        <div id="error-alert" class="alert alert-danger" role="alert">
                                            <!-- سيتم ملء محتوى الخطأ بواسطة جافا سكريبت -->
                                        </div>

                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                            <div class="form-outline flex-fill mb-0">
                                                <input type="text" name="user" id="form3Example1c" class="form-control" value="<?php echo isset($user) ? $user : ''; ?>" required/>
                                                <label class="form-label" for="form3Example1c">اسم المستخدم</label>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                                            <div class="form-outline flex-fill mb-0">
                                            <input type="email" name="email" id="form3Example3c" class="form-control" value="<?php echo isset($email) ? $email : ''; ?>" pattern="^[^\s@]+@[^\s@]+\.[^\s@]+$" required>
                                            <label class="form-label" for="form3Example3c">الايميل</label>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-mobile-screen-button fa-lg me-3 fa-fw"></i>
                                            <div class="form-outline flex-fill mb-0">
                                            <input type="tel" name="phone" id="form3Example4c" class="form-control" maxlength="10" value="<?php echo isset($phone) ? $phone : ''; ?>" required oninput="validatePhoneNumber(this)"/>
                                            <label class="form-label" for="form3Example4c">رقم الجوال</label>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                                            <div class="form-outline flex-fill mb-0">
                                                <input type="password" name="pass" id="form3Example5c" class="form-control" required/>
                                                <label class="form-label" for="form3Example5c">كلمة المرور</label>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-key fa-lg me-3 fa-fw"></i>
                                            <div class="form-outline flex-fill mb-0">
                                                <input type="password" name="repeat_pass" id="form3Example6c" class="form-control" required/>
                                                <label class="form-label" for="form3Example6c">تأكيد كلمة المرور</label>
                                            </div>
                                        </div>

                                        <div class="text-center text-lg-start mt-4 pt-2">
                                            <button type="submit" name="send" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem;">تسجيل</button>
                                            <p class="small fw-bold mt-2 pt-1 mb-0">هل لديك حساب? <a href="login.php" class="link-danger">دخول</a></p>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">
                                    <img src="assets/img/logoname2.png" class="img-fluid" alt="Sample image">
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

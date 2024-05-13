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
        $_SESSION['login_success'] = true;
        header("Location: index.php");
        
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

    <style>
   body {
    background-image: url('assets/img/bac5.jpg'); /* تعيين مسار الصورة */
    background-size: cover; /* تغطية الشاشة بالصورة دون تشويه */
    background-position: center; /* محاذاة الصورة في الوسط */
    background-repeat: no-repeat; /* عدم تكرار الصورة */
    background-attachment: fixed; /* جعل الصورة ثابتة أثناء التمرير */
        }
        </style>
        <link rel="stylesheet" href="mycss2/css22.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
        $(document).ready(function(){
            // عرض الـ alert بعد 3 ثواني
            $(".alert").fadeTo(2000, 500).slideUp(500, function(){
                $(".alert").slideUp(500);
            });
        });
    </script>
</head>

<body>
     <!-- ======= اليرت تسجيل الدخول ======= -->
<?php
            if (isset($_SESSION['reg_success']) && $_SESSION['reg_success']) {
              echo '
              <div class="container">
                      <div class="row justify-content-center mt-5">
                          <div class="col-md-6">
                              <div class="alert alert-success" role="alert">
                                  تم أنشاء حساب جديد!
                              </div>
                          </div>
                      </div>
                  </div>>';
                   // بعد عرض الإنذار، قم بحذفه من الجلسة لعدم عرضه مرة أخرى
                   unset($_SESSION['reg_success']);
            }
?>
    <section class="vh-100" style="">
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

                                        <div class="d-flex justify-content-between align-items-center">
            <!-- Checkbox -->
            <div class="form-check mb-0">
              <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3" />
              <label class="form-check-label" for="form2Example3">
                Remember me
              </label>
            </div>
            <a href="#!" class="text-body">Forgot password?</a>
          </div>

          <div class="text-center text-lg-start mt-4 pt-2">
            <button  type="submit" name="send" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg"
              style="padding-left: 2.5rem; padding-right: 2.5rem;">Login</button>
            <p class="small fw-bold mt-2 pt-1 mb-0">Don't have an account? <a href="reg.php"
                class="link-danger">Register</a></p>
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

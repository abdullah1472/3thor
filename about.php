<?php
session_start();
require 'contc.php'; // توصيل قاعدة البيانات

if (!isset($_SESSION['UserID'])) {
    header('Location: login1.php'); // توجيه إلى صفحة الدخول إذا لم يكن المستخدم مسجلاً
    exit();
}

$user_id = $_SESSION['UserID'];
$defaultImagePath = "uploads/default.png"; // مسار الصورة الافتراضية

// استعلام لاستعادة معلومات الحساب
$stmt_user = $conn->prepare("SELECT * FROM users WHERE UserID = ?");
$stmt_user->execute([$user_id]);
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);

// استعلام لجلب المنتجات الخاصة بالمستخدم
$sql = "SELECT p.ProductID, p.Title, p.Description, p.Price, p.Location, p.Category, p.DatePosted, MIN(i.ImageDescription) AS ImageDescription
        FROM product p
        LEFT JOIN image i ON p.ProductID = i.ProductID
        WHERE p.UserID = :user_id
        GROUP BY p.ProductID
        ORDER BY p.DatePosted DESC";
$stmtt = $conn->prepare($sql);
$stmtt->bindParam(':user_id', $user_id);
$stmtt->execute();
$products = $stmtt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>MyPortfolio Bootstrap Template - About</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <link href="https://fonts.googleapis.com/css?family=Inconsolata:400,500,600,700|Raleway:400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            $(".alert").fadeTo(2000, 500).slideUp(500, function(){
                $(".alert").slideUp(500);
            });
        });

        window.onload = function() {
            window.scrollTo(0, 0);
        }

        function openPopup() {
            document.getElementById("productPopup").style.display = "block";
        }

        function closePopup() {
            document.getElementById("productPopup").style.display = "none";
        }
    </script>
    
    <style>
      body {
    background-image: url('assets/img/bac5.jpg'); /* تعيين مسار الصورة */
    background-size: cover; /* تغطية الشاشة بالصورة دون تشويه */
    background-position: center; /* محاذاة الصورة في الوسط */
    background-repeat: no-repeat; /* عدم تكرار الصورة */
    background-attachment: fixed; /* جعل الصورة ثابتة أثناء التمرير */
        }
        .imgpo {
          background-size: cover; /* تغطية الشاشة بالصورة دون تشويه */
          background-position: center; /* محاذاة الصورة في الوسط */
        }
        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
        }

        .popup-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .product-image img {
            width: 200px;
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>

<body>

<?php
if (isset($_GET['registered']) && $_GET['registered'] === 'true') {
    echo '
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="alert alert-success" role="alert">
                    تم إنشاء الحساب بنجاح!
                </div>
            </div>
        </div>
    </div>';
}
?>




<nav class="navbar navbar-light custom-navbar">
    <div class="container">
    
        <a class="navbar-brand" href="index.php"><img class="navbar-brand" src="assets/img/logoname2.png" width="130" hight="130"></a>
        
        
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
                            <input type="text" class="form-control disabled" id="username" name="username" value="<?php echo htmlspecialchars($user['UserName']); ?>" disabled>
                        </div>
                        <div class="d-flex mb-1">
                            <div class="form-group">
                                <label for="email">البريد الإلكتروني:</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['Email']); ?>" <?php if(isset($entered_password) && $entered_password == $stored_password) echo "enabled"; ?>>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phone">رقم الجوال:</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['Phone']); ?>" <?php if(isset($entered_password) && $entered_password == $stored_password) echo "enabled"; ?>>
                        </div>
                        <div class="form-group">
                            <label for="password">كلمة المرور:</label>
                            <input type="password" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($user['Password']); ?>" required readonly>
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
</main>
  <!-- عرض المنتجات -->
  <div id='portfolio-grid' class='row no-gutter' data-aos='fade-up' data-aos-delay='200'>
            
                <h3>المنتجات</h3>
                <?php if(!empty($products)): ?>
                    <?php foreach($products as $product): ?>
                        <div class='item web col-sm-6 col-md-4 col-lg-2 mb-4'>
                            <a href='work-single.php?id=<?php echo $product['ProductID']; ?>' class='item-wrap fancybox'>
                                <div class='work-info'>
                                    <h3><?php echo htmlspecialchars($user['UserName']); ?></h3> <!-- عرض اسم المستخدم المرتبط بالمنتج -->
                                    <span><?php echo htmlspecialchars($product['Category']); ?></span>
                                </div>
                                <?php
// التأكد من أن الصورة الأولى موجودة وليست فارغة
$images = explode(",", $product['ImageDescription']);
if (count($images) > 0 && $images[0] != '') {
    $imagePath = (strpos($images[0], 'default.png') !== false) ? $images[0] : 'uploads/' . $images[0];
    echo "<div class='product-image'>
            <img class='img-fluid' src='" . $imagePath . "' alt='Product Image'>
        </div>";
} else {
    echo "<div class='product-image'>
            <img class='img-fluid' src='" . $defaultImagePath . "' alt='Product Image'>
            
        </div>";
}
?>

                            </a>
                            <div class='p-1 text-white bg-dark-subtle container text-center'>
                                <div class='row justify-content-around'>
                                    <div class='col-4'>
                                        <?php
                                        if (!empty($product['DatePosted'])) {
                                            $displayTime = strtotime($product['DatePosted']);
                                            $currentTime = time();
                                            $timeDiff = $currentTime - $displayTime;
                                            if ($timeDiff < 60) {
                                                $timeAgo = "الآن";
                                            } elseif ($timeDiff < 3600) {
                                                $timeAgo = "قبل " . floor($timeDiff / 60) . " دقيقة";
                                            } elseif ($timeDiff < 86400) {
                                                $timeAgo = "قبل " . floor($timeDiff / 3600) . " ساعة";
                                            } else {
                                                $timeAgo = "قبل " . floor($timeDiff / 86400) . " يوم";
                                            }
                                            echo $timeAgo;
                                        }
                                        ?>
                                    </div>
                                    <div class='col-4'>
                                        <?php echo htmlspecialchars($product['Title']); ?>
                                    </div>
                                </div>
                                <div class='row justify-content-around'>
                                    <div class='col-4'>
                                        <?php echo htmlspecialchars($product['Location']); ?>
                                    </div>
                                    <div class='col-4'>
                                        <?php echo htmlspecialchars($product['Price']); ?>
                                    </div>
                                </div>
                                <div class='col-16'>  
                                <form method="POST" action="delete_product.php" class="mt-2">
                                    <input type="hidden" name="product_id" value="<?php echo $product['ProductID']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                </form>
                                </div>  
                            </div>
                            
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>لا توجد منتجات.</p>
                <?php endif; ?>
            </div>
        </div>
        </div>  
<!-- Vendor JS Files -->
<script src="assets/vendor/aos/aos.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>
<script src="assets/js/main.js"></script>

</body>
</html>

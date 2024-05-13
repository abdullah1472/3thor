<?php
session_start();
require 'contc.php'; // قم بتغيير 'contc.php' إلى اسم ملف اتصال قاعدة البيانات الخاص بك

// دالة للتحقق من حالة تسجيل الدخول
function isLoggedIn() {
  return isset($_SESSION['UserID']);
}

$defaultImagePath = "uploads/default.png"; // حدد مسار الصورة الافتراضية هنا

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send'])) {
    $userID = $_SESSION['UserID']; // تأكد من تغيير هذا إذا كان اسم المتغير المستخدم لديك مختلفًا

    $productName = $_POST['productName'];
    $productDescription = $_POST['productDescription'];
    $productPrice = $_POST['productPrice'];
    $productLocation = $_POST['productLocation'];
    $productType = $_POST['productType'];
    
    $sql = "INSERT INTO product (Title, Description, Price, Location, Category, UserID) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$productName, $productDescription, $productPrice, $productLocation, $productType, $userID]);
    
    $productID = $conn->lastInsertId();
    $targetDirectory = "uploads/";

    if (!is_dir($targetDirectory)) {
        mkdir($targetDirectory, 0777, true);
    }

    foreach ($_FILES['productImages']['tmp_name'] as $key => $tmp_name) {
        $imageName = $_FILES['productImages']['name'][$key];
        $imageTmpName = $_FILES['productImages']['tmp_name'][$key];
        $imagePath = $targetDirectory . $imageName;

        if (move_uploaded_file($imageTmpName, $imagePath)) {
            $sql = "INSERT INTO image (ImageDescription, ProductID) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$imageName, $productID]);
        } else {
            echo "حدث خطأ أثناء تحميل الصورة.";
        }
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// التحقق من وجود نص للبحث
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT p.ProductID, p.UserID, p.Title, p.Description, p.Price, p.DatePosted, p.Location, p.Category, MIN(i.ImageDescription) as ImageDescription
        FROM product p
        LEFT JOIN image i ON p.ProductID = i.ProductID
        WHERE p.Title LIKE ? OR p.Description LIKE ?
        GROUP BY p.ProductID
        ORDER BY p.DatePosted DESC";

$stmtt = $conn->prepare($sql);
$stmtt->execute(['%' . $searchTerm . '%', '%' . $searchTerm . '%']);
$products = $stmtt->fetchAll(PDO::FETCH_ASSOC);

?>
<?php
//session_start();
// التحقق من وجود متغير الجلسة الذي يحمل معرف المستخدم
//if (!isset($_SESSION['UserID'])) {
    // إذا لم يكن المستخدم قد سجل الدخول، قم بتوجيهه إلى صفحة تسجيل الدخول
//    echo"ablshhhhhhh";
//}
?>

<!DOCTYPE html>
<html lang="en">

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
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: MyPortfolio
  * Template URL: https://bootstrapmade.com/myportfolio-bootstrap-portfolio-website-template/
  * Updated: Mar 17 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
 <!-- Custom JavaScript -->
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script>
    $(document).ready(function(){
        $(".alert").fadeTo(2000, 500).slideUp(500, function(){
            $(".alert").slideUp(500);
        });
    });
</script>

<script>
    window.onload = function() {
        window.scrollTo(0, 0);
    }
</script>

<style>
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
  <!-- ======= اليرت تسجيل الدخول ======= -->
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
                  </div>>';
            }
?>
  <!-- ======= Navbar ======= -->
  <div class="collapse navbar-collapse custom-navmenu" id="main-navbar">
    <div class="container py-2 py-md-5">
      <div class="row align-items-start">
        <div class="col-md-2">
          <ul class="custom-menu">
            <li class="active"><a href="index.html">Home</a></li>
            <li><a href="about.html">About Me</a></li>
            <li><a href="services.html">Services</a></li>
            <li><a href="works.html">Works</a></li>
            <li><a href="contact.html">Contact</a></li>
          </ul>
        </div>
        <div class="col-md-6 d-none d-md-block  mr-auto">
          <div class=" d-flex">
            
            <div>
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
      <a class="navbar-brand" href="#">عثور</a>
      <a href="#" class="burger" data-bs-toggle="collapse" data-bs-target="#main-navbar">
        <span></span>
      </a>
    </div>
  </nav>

  <main id="main">

    <!-- ======= Works Section ======= -->
    <section class="section site-portfolio">
      <div class="container">
        <div class="row mb-5 align-items-center">
          <div class="col-md-12 col-lg-6 mb-4 mb-lg-0" data-aos="fade-up">

          
          
      <div class="row justify-content-start"> 
        <div class="row justify-content-between">
          <div class="col-4">
          <!--     -->
          <?php
    if (isLoggedIn()) {
?>
        <a href="#" onclick="openPopup()" class="readmore">لنشر</a>
<?php
    } else {
        echo '<p>يجب عليك تسجيل الدخول أولاً لنشر المنتجات.</p>';
    }
?>

<div id="productPopup" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closePopup()">&times;</span>
        <h2>تعبئة بيانات المنتج</h2>
        <form  method="post" enctype="multipart/form-data">
            <label for="productName">اسم المنتج:</label>
            <input type="text" id="productName" name="productName" required><br><br>

            <label for="productDescription">وصف المنتج:</label>
            <textarea id="productDescription" name="productDescription" required></textarea><br><br>

            <label for="productPrice">سعر المنتج:</label>
            <input type="text" id="productPrice" name="productPrice" required><br><br>

            <label for="productType">نوع المنتج:</label>
            <select id="productType" name="productType" required>
                <option value="">اختر نوع المنتج</option>
                <option value="إلكترونيات">إلكترونيات</option>
                <option value="ملابس">ملابس</option>
                <option value="أثاث">أثاث</option>
                <!-- Add more options as needed -->
            </select><br><br>

            <label for="productLocation">موقع المنتج:</label>
            <input type="text" id="productLocation" name="productLocation" required><br><br>

            <label for="productImages">صور المنتج:</label> 
           <input type="file" id="productImages" name="productImages[]" multiple ><br><br>
          
    
            <input type="submit" name="send" value="نشر">
        </form>
    </div>
</div>
<script>
    // JavaScript function to open the popup
    function openPopup() {
        document.getElementById("productPopup").style.display = "block";
    }

    // JavaScript function to close the popup
    function closePopup() {
        document.getElementById("productPopup").style.display = "none";
    }
</script>

          <!--     -->
      </div>
      <div class="col-4">
      <?php
// session_start(); // تأكد من بدء الجلسة

function printWelcomeMessage() {
    if (isset($_SESSION['user'])) {
        $username = $_SESSION['user'];
        echo "<p> $username</p>";
        echo '<a href="about.php" class="readmore mb-4">عرض معلومات الحساب</a>';
        echo '<a href="end_session.php" class="readmore border-danger bg-danger-subtle border-2 mb-4">تسجيل خروج</a>';
    } else {
        echo '<a href="login1.php" class="readmore mb-4">سجل دخول</a>';
    }
}

printWelcomeMessage();
?>
</div>

    </div>
  </div>
  <div class="input-group mb-3">
    <form method="GET" action="">
        <input type="text" class="form-control" name="search" placeholder="بحث" aria-label="بحث" aria-describedby="basic-addon2" value="<?php echo htmlspecialchars($searchTerm); ?>">
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="submit">بحث</button>
        </div>
    </form>
</div>


           
          </div>
          <div class="col-md-12 col-lg-6 text-start text-lg-end" data-aos="fade-up" data-aos-delay="100">
          <div class="filters">
    <a href="index1.php" class="<?= !isset($_GET['category']) || $_GET['category'] == '' ? 'active' : '' ?>">الكل</a>
    <a href="index1.php?category=إلكترونيات" class="<?= isset($_GET['category']) && $_GET['category'] == 'إلكترونيات' ? 'active' : '' ?>">إلكترونيات</a>
    <a href="index1.php?category=ملابس" class="<?= isset($_GET['category']) && $_GET['category'] == 'ملابس' ? 'active' : '' ?>">ملابس</a>
    <a href="index1.php?category=أثاث" class="<?= isset($_GET['category']) && $_GET['category'] == 'أثاث' ? 'active' : '' ?>">أثاث</a>
</div>
          </div>
        </div>

        
  
        <?php
// استعلام لجلب بيانات المنتجات مع الصور المرتبطة بها
$sql = "SELECT p.ProductID, p.UserID, p.Title, p.Description, p.Price, p.DatePosted, p.Location, p.Category, GROUP_CONCAT(i.ImageDescription) AS Images, u.username
        FROM product p
        LEFT JOIN image i ON p.ProductID = i.ProductID
        LEFT JOIN users u ON p.UserID = u.UserID"; // ربط مع جدول المستخدمين لجلب اسم المستخدم

$conditions = [];
$params = [];

// إذا تم تحديد تصنيف، قم بإضافة شرط WHERE لتحديد الفئة
if(isset($_GET['category']) && $_GET['category'] != '') {
    $category = $_GET['category'];
    $conditions[] = "p.Category = :category";
    $params[':category'] = $category;
}

// إذا تم تحديد نص بحث، قم بإضافة شرط LIKE لتحديد المنتجات
if(isset($_GET['search']) && $_GET['search'] != '') {
    $searchTerm = '%' . $_GET['search'] . '%';
    $conditions[] = "(p.Title LIKE :search OR p.Description LIKE :search)";
    $params[':search'] = $searchTerm;
}

// دمج الشروط في جملة WHERE إذا كانت هناك شروط
if (count($conditions) > 0) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " GROUP BY p.ProductID
        ORDER BY p.DatePosted DESC"; // ترتيب النتائج حسب تاريخ النشر بتنازلي

// تنفيذ الاستعلام
$stmtt = $conn->prepare($sql);

// ربط القيم بالاستعلام
foreach ($params as $key => &$val) {
    $stmtt->bindParam($key, $val);
}

$stmtt->execute();

// جلب البيانات كمصفوفة
$products = $stmtt->fetchAll(PDO::FETCH_ASSOC);

// عرض البيانات
foreach ($products as $product) {
    // حساب الوقت المنقضي منذ العرض بالثواني
    $displayTime = strtotime($product['DatePosted']);
    $currentTime = time(); // وقت الآن بالثواني
    $timeDiff = $currentTime - $displayTime;

    // حساب الزمن المناسب بالدقائق أو الساعات أو الأيام
    if ($timeDiff < 60) {
        $timeAgo = "الآن";
    } elseif ($timeDiff < 3600) {
        $timeAgo = "قبل " . floor($timeDiff / 60) . " دقيقة";
    } elseif ($timeDiff < 86400) {
        $timeAgo = "قبل " . floor($timeDiff / 3600) . " ساعة";
    } else {
        $timeAgo = "قبل " . floor($timeDiff / 86400) . " يوم";
    }

    // تقسيم الصور إلى مصفوفة
    $images = explode(",", $product['Images']);

    echo "
    <div id='portfolio-grid' class='row no-gutter' data-aos='fade-up' data-aos-delay='200'>
        <div class='item web col-sm-6 col-md-4 col-lg-4 mb-4'>
        <a href='work-single.php?id=" . $product['ProductID'] . "' class='item-wrap fancybox'>
            <div class='work-info'>
            <h3>" . $product['username'] . "</h3> <!-- عرض اسم المستخدم المرتبط بالمنتج -->
                <span>" . $product['Category'] . "</span>
            </div>";

            // عرض الصورة الأولى أو الصورة الافتراضية إذا لم تكن هناك صور
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

        echo "</a>
        <div class='p-1 text-white bg-dark-subtle container text-center'>
            <div class='row justify-content-around'>
                <div class='col-4'>
                    " . $timeAgo . " <!-- عرض الوقت المنقضي بصيغة مختصرة -->
                </div>
                <div class='col-4'>
                    " . $product['Title'] . "     
                </div>            
            </div>
            <div class='row justify-content-around'>
                <div class='col-4'>
                    " . $product['Location'] . "
                </div>
                <div class='col-4'>
                    " . $product['Price'] . "
                </div> 
            </div>         
        </div>
        </div>
    ";
}
?>

              <!-- التاريخ و السعر نهايته-->
          </div>
        </div>
      </div>
    </section><!-- End  Works Section -->

    <!-- ======= Clients Section ======= -->
    <section class="section">
      <div class="container">
        <div class="row justify-content-center text-center mb-4">
          <div class="col-5">
            <h3 class="h3 heading">My Clients</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit explicabo inventore.</p>
          </div>
        </div>
        <div class="row">
          <div class="col-4 col-sm-4 col-md-2">
            <a href="#" class="client-logo"><img src="assets/img/logo-adobe.png" alt="Image" class="img-fluid"></a>
          </div>
          <div class="col-4 col-sm-4 col-md-2">
            <a href="#" class="client-logo"><img src="assets/img/logo-uber.png" alt="Image" class="img-fluid"></a>
          </div>
          <div class="col-4 col-sm-4 col-md-2">
            <a href="#" class="client-logo"><img src="assets/img/logo-apple.png" alt="Image" class="img-fluid"></a>
          </div>
          <div class="col-4 col-sm-4 col-md-2">
            <a href="#" class="client-logo"><img src="assets/img/logo-netflix.png" alt="Image" class="img-fluid"></a>
          </div>
          <div class="col-4 col-sm-4 col-md-2">
            <a href="#" class="client-logo"><img src="assets/img/logo-nike.png" alt="Image" class="img-fluid"></a>
          </div>
          <div class="col-4 col-sm-4 col-md-2">
            <a href="#" class="client-logo"><img src="assets/img/logo-google.png" alt="Image" class="img-fluid"></a>
          </div>

        </div>
      </div>
    </section><!-- End Clients Section -->

    <!-- ======= Services Section ======= -->
    <section class="section services">
      <div class="container">
        <div class="row justify-content-center text-center mb-4">
          <div class="col-5">
            <h3 class="h3 heading">My Services</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit explicabo inventore.</p>
          </div>
        </div>
        <div class="row">

          <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <i class="bi bi-card-checklist"></i>
            <h4 class="h4 mb-2">Web Design</h4>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit explicabo inventore.</p>
            <ul class="list-unstyled list-line">
              <li>Lorem ipsum dolor sit amet consectetur adipisicing</li>
              <li>Non pariatur nisi</li>
              <li>Magnam soluta quod</li>
              <li>Lorem ipsum dolor</li>
              <li>Cumque quae aliquam</li>
            </ul>
          </div>
          <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <i class="bi bi-binoculars"></i>
            <h4 class="h4 mb-2">Mobile Applications</h4>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit explicabo inventore.</p>
            <ul class="list-unstyled list-line">
              <li>Lorem ipsum dolor sit amet consectetur adipisicing</li>
              <li>Non pariatur nisi</li>
              <li>Magnam soluta quod</li>
              <li>Lorem ipsum dolor</li>
              <li>Cumque quae aliquam</li>
            </ul>
          </div>
          <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <i class="bi bi-brightness-high"></i>
            <h4 class="h4 mb-2">Graphic Design</h4>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit explicabo inventore.</p>
            <ul class="list-unstyled list-line">
              <li>Lorem ipsum dolor sit amet consectetur adipisicing</li>
              <li>Non pariatur nisi</li>
              <li>Magnam soluta quod</li>
              <li>Lorem ipsum dolor</li>
              <li>Cumque quae aliquam</li>
            </ul>
          </div>
          <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <i class="bi bi-calendar4-week"></i>
            <h4 class="h4 mb-2">SEO</h4>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit explicabo inventore.</p>
            <ul class="list-unstyled list-line">
              <li>Lorem ipsum dolor sit amet consectetur adipisicing</li>
              <li>Non pariatur nisi</li>
              <li>Magnam soluta quod</li>
              <li>Lorem ipsum dolor</li>
              <li>Cumque quae aliquam</li>
            </ul>
          </div>
        </div>
      </div>
    </section><!-- End Services Section -->

    <!-- ======= Testimonials Section ======= -->
    <section class="section pt-0">
      <div class="container">

        <div class="testimonials-slider swiper" data-aos="fade-up" data-aos-delay="100">
          <div class="swiper-wrapper">

            <div class="swiper-slide">
              <div class="testimonial-wrap">
                <div class="testimonial">
                  <img src="assets/img/person_1.jpg" alt="Image" class="img-fluid">
                  <blockquote>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam necessitatibus incidunt ut officiis
                      explicabo inventore.</p>
                  </blockquote>
                  <p>&mdash; Jean Hicks</p>
                </div>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-wrap">
                <div class="testimonial">
                  <img src="assets/img/person_2.jpg" alt="Image" class="img-fluid">
                  <blockquote>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam necessitatibus incidunt ut officiis
                      explicabo inventore.</p>
                  </blockquote>
                  <p>&mdash; Chris Stanworth</p>
                </div>
              </div>
            </div><!-- End testimonial item -->

          </div>
          <div class="swiper-pagination"></div>
        </div>

      </div>
    </section><!-- End Testimonials Section -->

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer class="footer" role="contentinfo">
    <div class="container">
      <div class="row">
        <div class="col-sm-6">
          <p class="mb-1">&copy; Copyright MyPortfolio. All Rights Reserved</p>
          <div class="credits">
            <!--
            All the links in the footer should remain intact.
            You can delete the links only if you purchased the pro version.
            Licensing information: https://bootstrapmade.com/license/
            Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/buy/?theme=MyPortfolio
          -->
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
          </div>
        </div>
        <div class="col-sm-6 social text-md-end">
          <a href="#"><span class="bi bi-twitter"></span></a>
          <a href="#"><span class="bi bi-facebook"></span></a>
          <a href="#"><span class="bi bi-instagram"></span></a>
          <a href="#"><span class="bi bi-linkedin"></span></a>
        </div>
      </div>
    </div>
  </footer>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

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

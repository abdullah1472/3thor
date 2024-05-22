<?php
session_start();
require 'contc.php'; // قم بتغيير 'contc.php' إلى اسم ملف اتصال قاعدة البيانات الخاص بك

// دالة للتحقق من حالة تسجيل الدخول
function isLoggedIn() {
  return isset($_SESSION['UserID']);
}

$defaultImagePath = "uploads/default1.png"; // حدد مسار الصورة الافتراضية هنا


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send'])) {
    // جلب بيانات المستخدم من الجلسة
    $userID = $_SESSION['UserID']; // تأكد من تغيير هذا إذا كان اسم المتغير المستخدم لديك مختلفًا

    // جلب بيانات المنتج من النموذج
    $productName = $_POST['productName']; // كانت Title في السابق
    $productDescription = $_POST['productDescription']; // كانت Description في السابق
    $productPrice = $_POST['productPrice']; // كانت Price في السابق
    $productLocation = $_POST['productLocation']; // كانت Category في السابق
    $productType = $_POST['productType']; // كانت Location في السابق
    
    // تحضير الاستعلام لإدراج المنتج في قاعدة البيانات
    $sql = "INSERT INTO product (Title, Description, Price, Location, Category, UserID) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$productName, $productDescription, $productPrice, $productLocation, $productType, $userID]);
    
    // جلب معرف المنتج الجديد الذي تم إضافته
    $productID = $conn->lastInsertId();

    // تحديد المسار الذي ستُرفع إليه الصور
    $targetDirectory = "uploads/";

    // التحقق من وجود المجلد "uploads"، وإن لم يكن موجودًا يتم إنشاؤه
    if (!is_dir($targetDirectory)) {
        mkdir($targetDirectory, 0777, true);
    }

    // تحميل كل صورة من النموذج وحفظها في المجلد "uploads"
    foreach ($_FILES['productImages']['tmp_name'] as $key => $tmp_name) {
        $imageName = $_FILES['productImages']['name'][$key];
        $imageTmpName = $_FILES['productImages']['tmp_name'][$key];
        $imagePath = $targetDirectory . $imageName;

        // التحقق من نجاح عملية تحميل الصورة قبل إدراج اسم الصورة في قاعدة البيانات
        if (move_uploaded_file($imageTmpName, $imagePath)) {
            // تحضير الاستعلام لإدراج اسم الصورة ومعرف المنتج المرتبط في جدول الصور
            $sql = "INSERT INTO image (ImageDescription, ProductID) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$imageName, $productID]);
        } else {
            // في حالة فشل نقل الصورة
            echo "حدث خطأ أثناء تحميل الصورة.";
        }
    }

    // إعادة التوجيه بعد إضافة المنتج بنجاح
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



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>عثور</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/logoname2.png" rel="icon">
 

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=https://fonts.googleapis.com/css?family=Inconsolata:400,500,600,700|Raleway:400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <link href="assets/css/style.css" rel="stylesheet">

  
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

 <script>
        $(document).ready(function(){
            // عرض الـ alert بعد 3 ثواني
            $(".alert").fadeTo(2000, 500).slideUp(500, function(){
                $(".alert").slideUp(500);
            });
        });
    </script>
<script>
    // استخدم JavaScript للتمرير إلى الأعلى
    window.onload = function() {
        window.scrollTo(0, 0);
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
  background-size: cover;
  background-position: center;
  height: 100%; /* Set the height of the image container */
}

        /* Add your CSS styles here */
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
        
.product-image.imgpo {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%; /* Ensure the image container takes the full width */
    height: 200px; /* Set the desired height */
}

.product-image.imgpo img {
    max-width: 100%; /* Ensure the image does not exceed the container width */
    max-height: 100%; /* Ensure the image does not exceed the container height */
}

    </style>


</head>

<body>







  <!-- ======= اليرت تسجيل الدخول ======= -->
<?php
            if (isset($_SESSION['login_success']) && $_SESSION['login_success']) {
              echo '
              <div class="container">
                      <div class="row justify-content-center mt-5">
                          <div class="col-md-6">
                              <div class="alert alert-success" role="alert">
                                  تم تسجيل الدخول!
                              </div>
                          </div>
                      </div>
                  </div>';
                   // بعد عرض الإنذار، قم بحذفه من الجلسة لعدم عرضه مرة أخرى
                   unset($_SESSION['login_success']);
            }
?>
  
  <nav class="navbar navbar-light custom-navbar">
    <div class="container">
    <a class="navbar-brand" href="#"><img class="navbar-brand" src="assets/img/logoname2.png" width="230" hight="230"></a>
    <div class="col-md-12 col-lg-6 text-start text-lg-end" data-aos="fade-up" data-aos-delay="100">
          <div class="filters">
    
          <a href="index.php?category=إلكترونيات" style="font-size: 24px;" class="<?= isset($_GET['category']) && $_GET['category'] == 'إلكترونيات' ? 'active' : '' ?>">إلكترونيات</a>
    <a href="index.php?category=ملابس" style="font-size: 24px;" class="<?= isset($_GET['category']) && $_GET['category'] == 'ملابس' ? 'active' : '' ?>">ملابس</a>
    <a href="index.php?category=أجهزة منزلية" style="font-size: 25px;" class="<?= isset($_GET['category']) && $_GET['category'] == 'أجهزة منزلية' ? 'active' : '' ?>">أجهزة منزلية</a>
    <a href="index.php?category=كتب وملازم" style="font-size: 25px;" class="<?= isset($_GET['category']) && $_GET['category'] == 'كتب وملازم' ? 'active' : '' ?>">كتب وملازم</a>
    <a href="index.php?category=عقار" style="font-size: 25px;" class="<?= isset($_GET['category']) && $_GET['category'] == 'عقار' ? 'active' : '' ?>">عقار</a>
    <a href="index.php?category=أثاث" style="font-size: 25px;" class="<?= isset($_GET['category']) && $_GET['category'] == 'أثاث' ? 'active' : '' ?>">أثاث</a>
    <a href="index.php" style="font-size: 25px;"  class="<?= !isset($_GET['category']) || $_GET['category'] == '' ? 'active' : '' ?>">الكل</a>
</div>
          </div>
    <div class="col-lg-1">
        
    <?php
   // session_start(); // بدء الجلسة

    function printWelcomeMessage() {
        if (isset($_SESSION['user'])) {
            $username = $_SESSION['user'];
            
            echo '<div class="col-md-auto">';
echo "<a href='about.php' class='readmore mb-4' style='font-size: 15px;'> $username</a>";
echo '</div>';
echo '<div class="col-md-auto">';
echo '<a href="end_session.php" class="readmore border-danger bg-danger-subtle border-2 mb-4" style="font-size: 15px;">تسجيل خروج</a>';
echo '</div>';
            
    
        }
         else {
            
            echo '<a href="login.php" class="readmore mb-4">سجل دخول</a>';
        }
    }

    printWelcomeMessage();
    ?>
    
    </div>
    <div class="container">
    <div class="row justify-content-md-center">
    <div class="col col-lg-6">

    <form method="GET" action="" class="input-group mb-3" style="display: flex; align-items: stretch;"> <!-- إضافة الخاصية align-items: stretch; لجعل الحقل والزرار بنفس الارتفاع -->
        <input type="text" class="form-control border border-dark"  name="search" placeholder="بحث" aria-label="بحث" style="height: 38px;" aria-describedby="basic-addon2" value="<?php echo htmlspecialchars($searchTerm); ?>">
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="submit" aria-label="Search" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                <img src="assets/img/searchimg.png" alt="صورة البحث" class="img-fluid" style="width: 20px; height: 20px;">
            </button>
        </div>
    </form>
</div>
</div>
</div>
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
        <a href="#" onclick="openPopup()" class="readmore"  style="font-size: 15px;">للنشر</a>
<?php
    } else {
        echo '<p>يجب عليك تسجيل الدخول أولاً لنشر المنتجات.</p>';
    }
?>

<div id="productPopup" class="popup">
    <div class="popup-content-center">
        <span class="close" onclick="closePopup()">&times;</span>
        <div class="row justify-content-center">
        <div class="col-md-4">
        <div class="card">
        <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">تعبئة بيانات المنتج</h2>
                </div>
                <div class="card-body">
        <form id="productForm1" method="post" enctype="multipart/form-data">

        <div class="form-group">
            <label for="productName">اسم المنتج:</label>
            <input type="text" id="productName" name="productName" class="form-control" required><br><br>
            <div id="productNameError" class="invalid-feedback"></div>
            </div>

            <div class="form-group">
            <label for="productDescription">وصف المنتج:</label>
            <textarea id="productDescription" name="productDescription" class="form-control" rows="3" required></textarea>
            <div id="productDescriptionError" class="invalid-feedback"></div>
             </div>


             <div class="form-group">
             <label for="productPrice">سعر المنتج:</label>
             <input type="text" id="productPrice" name="productPrice" class="form-control">
             <div id="productPriceError" class="invalid-feedback"></div>
             </div>

             <div class="form-group">
             <label for="productType">نوع المنتج:</label>
             <select id="productType" name="productType" class="form-control" required>
             <option value="">اختر نوع المنتج</option>
                    <option value="إلكترونيات">إلكترونيات</option>
                    <option value="ملابس">ملابس</option>
                    <option value="أجهزة منزلية">أجهزة منزلية</option>
                    <option value="كتب وملازم">كتب وملازم</option>
                    <option value="عقار">عقار</option>
                    <option value="أثاث">أثاث</option>
                    <!-- Add more options as needed -->
                            </select>
                <div id="productTypeError" class="invalid-feedback"></div>
                        </div>

                        <div class="form-group">
                            <label for="productLocation">موقع المنتج:</label>
                            <input type="text" id="productLocation" name="productLocation" class="form-control" required>
                            <div id="productLocationError" class="invalid-feedback"></div>
                        </div>

            <div class="form-group">
                            <label for="productImages">صور المنتج:</label>
                            <input type="file" id="productImages" name="productImages[]" class="form-control-file" multiple>
                            <div id="productImagesError" class="invalid-feedback"></div>
                        </div>
    
                        <button type="submit" name="send" class="btn btn-primary">نشر</button>
        </form>
        </div>
    </div>
</div>
</div>
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

      </div>
      



    </div>
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
        <div class='item web col-sm-6 col-md-4 col-lg-3 mb-4'>
        <a href='work-single.php?id=" . $product['ProductID'] . "' class='item-wrap fancybox'>
            <div class='work-info'>
            <h3>" . $product['username'] . "</h3> <!-- عرض اسم المستخدم المرتبط بالمنتج -->
                <span>" . $product['Category'] . "</span>
            </div>";

            // عرض الصورة الأولى أو الصورة الافتراضية إذا لم تكن هناك صور
            if (count($images) > 0 && $images[0] != '') {
                $imagePath = (strpos($images[0], 'default1.png') !== false) ? $images[0] : 'uploads/' . $images[0];
                echo "<div class='product-image imgpo'>
                        <img class='img-fluid' src='" . $imagePath . "' alt='Product Image'style='width:100%;'>
                    </div>";
            } else {
                echo "<div class='product-image imgpo'>
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
            <h3 class="h3 heading">--------</h3>
            <p>------------------------------------------------.</p>
          </div>
        </div>
        </div>
      
    </section><!-- End Clients Section -->

   

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer class="footer" role="contentinfo">
    <div class="container">
      <div class="row">
        <div class="col-sm-6">
        <p class="mb-1">&copy; Copyright. All Rights Reserved</p>
          
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

  <!-- تضمين مكتبة Bootstrap JS من CDN -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- تضمين مكتبة jQuery من CDN -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- تضمين ملف JavaScript مخصص -->
<script src="scripttt.js"></script>
</body>

</body>

</html>

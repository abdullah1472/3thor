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
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>معلومات حسابي</title>
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function(){
            $(".alert").fadeTo(2000, 500).slideUp(500, function(){
                $(".alert").slideUp(500);
            });
        });

        window.onload = function() {
            window.scrollTo(0, 0);
        }


        function openEditPopup(productId, title, description, price, location, category) {
            document.getElementById("editProductId").value = productId;
            document.getElementById("editTitle").value = title;
            document.getElementById("editDescription").value = description;
            document.getElementById("editPrice").value = price;
            document.getElementById("editLocation").value = location;
            document.getElementById("editCategory").value = category;
            document.getElementById("editPopup").style.display = "block";
        }

        function closePopup() {
            document.getElementById("productPopup").style.display = "none";
        }
    </script>
    
    <style>
        body {
            background-image: url('assets/img/bac5.jpg'); 
            background-size: cover; 
            background-position: center; 
            background-repeat: no-repeat; 
            background-attachment: fixed;
        }
        .imgpo {
  background-size: cover;
  background-position: center;
  height: 100%; /* Set the height of the image container */
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
.text-right {
            text-align: right !important;
        }

    </style>
</head>

<body>

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['success_message'])) {
    echo'
              <div class="container">
                      <div class="row justify-content-center mt-5">
                          <div class="col-md-6">
                              <div id="success-alert"  class="alert alert-success" role="alert">
                              ' . $_SESSION['success_message'] . '
                              </div>
                          </div>
                      </div>
                  </div>';
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    echo'
              <div class="container">
                      <div class="row justify-content-center mt-5">
                          <div class="col-md-6">
                              <div id="error-alert" class="alert alert-danger" role="alert">
                              ' . $_SESSION['error_message'] . '
                              </div>
                          </div>
                      </div>
                  </div>';
    unset($_SESSION['error_message']);
}

?>
<script>
    setTimeout(function() {
        var successAlert = document.getElementById('success-alert');
        if (successAlert) {
            successAlert.style.display = 'none';
        }
        var errorAlert = document.getElementById('error-alert');
        if (errorAlert) {
            errorAlert.style.display = 'none';
        }
    }, 3000);
</script>

<nav class="navbar navbar-light custom-navbar">
    <div class="container">
        <a class="navbar-brand" href="index.php"><img class="navbar-brand" src="assets/img/logoname2.png" width="230" hight="230"></a>
            <span></span>
        </a>
    </div>
</nav>

<main id="main">
    <section class="section pb-5">
        <div class="container">
            <div class="row mb-5 align-items-end">
                <div class="col-md-6" data-aos="fade-up">
                    <h2>معلوماتي</h2>
                
            

            <div class="form-group">
            <div class="form-group">
                <label for="username">اسم المستخدم:</label>
                <input type="text" class="form-control border border-light col-md-5" id="username" name="username" value="<?php echo htmlspecialchars($user['UserName']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="email">البريد الإلكتروني:</label>
                <input type="email" class="form-control border border-light col-md-5" id="email" name="email" value="<?php echo htmlspecialchars($user['Email']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="phone">رقم الجوال:</label>
                <input type="tel" class="form-control border border-light col-md-5" id="phone" name="phone" value="<?php echo htmlspecialchars($user['Phone']); ?>" disabled>
            </div>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#updateModal">تحديث البيانات</button>
        </div>
    </div>
    <!-- نافذة منبثقة لتحديث البيانات -->
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">تحديث البيانات</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateForm" method="POST" action="update_profile.php">
                    <div class="form-group">
                        <label for="modal_username">اسم المستخدم:</label>
                        <input type="text" class="form-control" id="modal_username" name="username" value="<?php echo htmlspecialchars($user['UserName']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="modal_email">البريد الإلكتروني:</label>
                        <input type="email" class="form-control" id="modal_email" name="email" value="<?php echo htmlspecialchars($user['Email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="modal_phone">رقم الجوال:</label>
                        <input type="tel" class="form-control" id="modal_phone" name="phone" value="<?php echo htmlspecialchars($user['Phone']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="modal_password">كلمة المرور الحالية:</label>
                        <input type="password" class="form-control" id="modal_password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="modal_new_password">كلمة المرور الجديدة:</label>
                        <input type="password" class="form-control" id="modal_new_password" name="new_password">
                    </div>
                    <div class="form-group">
                        <label for="modal_confirm_new_password">تأكيد كلمة المرور الجديدة:</label>
                        <input type="password" class="form-control" id="modal_confirm_new_password" name="confirm_new_password">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('updateForm').addEventListener('submit', function (event) {
        var newPassword = document.getElementById('modal_new_password').value;
        var confirmNewPassword = document.getElementById('modal_confirm_new_password').value;

        var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d{5,})[A-Za-z\d]{7,}$/;

        if (newPassword && !passwordPattern.test(newPassword)) {
            alert('كلمة المرور يجب أن تحتوي على حرف كبير وحرف صغير وخمس أرقام.');
            event.preventDefault();
            return;
        }

        if (newPassword !== confirmNewPassword) {
            alert('كلمتا المرور الجديدتان لا تتطابقان.');
            event.preventDefault();
            return;
        }
    });
</script>
<div class="col-md-3 mb-5 mb-md-0" data-aos="fade-up">
                        <p><img src="assets/img/popo.png" alt="Image" class="img-fluid"></p>
                    </div>
</section>
</main>

<!-- عرض المنتجات -->
<div class="container">
    <div class="row justify-content-md-center">
    <div class="col col-lg-1">

<h3 style="text-decoration: underline;" >منتجاتي</h3>
</div>
</div>
</div>
<div id='portfolio-grid' class='row no-gutter' data-aos='fade-up' data-aos-delay='200'>
   
    <?php if(!empty($products)): ?>
        <?php foreach($products as $product): ?>
            <div class='item web col-sm-6 col-md-4 col-lg-2 mb-4'>
                <a href='work-single.php?id=<?php echo $product['ProductID']; ?>' class='item-wrap fancybox'>
                    <div class='work-info'>
                        <h3><?php echo htmlspecialchars($user['UserName']); ?></h3>
                        <span><?php echo htmlspecialchars($product['Category']); ?></span>
                    </div>
                    <?php
                    $images = explode(",", $product['ImageDescription']);
                    if (count($images) > 0 && $images[0] != '') {
                        $imagePath = (strpos($images[0], 'default.png') !== false) ? $images[0] : 'uploads/' . $images[0];
                        echo "<div class='product-image imgpo'>
                        <img class='img-fluid' src='" . $imagePath . "' alt='Product Image'style='width:100%;'>
                    </div>";
                    } else {
                        echo "<div class='product-image imgpo'><img class='img-fluid' src='" . $defaultImagePath . "' alt='Product Image'></div>";
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
                    <div class='row justify-content-around'>
                        <div class='col-6'>
                        
                            <form method="POST" action="delete_product.php" >
                                <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                <input type="hidden" name="product_id" value="<?php echo $product['ProductID']; ?>">
                            </form>
                            
                        </div>
                        <div class='col-6'>
                            <button class="btn btn-primary btn-sm" onclick="openEditPopup('<?php echo $product['ProductID']; ?>', '<?php echo htmlspecialchars(addslashes($product['Title'])); ?>', '<?php echo htmlspecialchars(addslashes($product['Description'])); ?>', '<?php echo $product['Price']; ?>', '<?php echo htmlspecialchars(addslashes($product['Location'])); ?>', '<?php echo htmlspecialchars(addslashes($product['Category'])); ?>')">تعديل</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>لا توجد منتجات.</p>
    <?php endif; ?>
</div>

<!-- Popup for editing product -->
<div id="editPopup" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closeEditPopup()">&times;</span>
        <form method="POST" action="edit_product.php">
            <input type="hidden" name="product_id" id="editProductId">
            <div class="form-group">
                <label for="editTitle">العنوان:</label>
                <input type="text" class="form-control" id="editTitle" name="title" required>
            </div>
            <div class="form-group">
                <label for="editDescription">الوصف:</label>
                <textarea class="form-control" id="editDescription" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="editPrice">السعر:</label>
                <input type="double" class="form-control" id="editPrice" name="price" required>
            </div>
            <div class="form-group">
                <label for="editLocation">الموقع:</label>
                <input type="text" class="form-control" id="editLocation" name="location" required>
            </div>
            <div class="form-group">
                <label for="editCategory">الفئة:</label>
                <input type="text" class="form-control" id="editCategory" name="category" required readonly>
            </div>
            <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
        </form>
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

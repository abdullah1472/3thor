<?php
require 'contc.php';

session_start();

if (!isset($_GET['id'])) {
    header('Location: index1.php');
    exit();
}

$productID = $_GET['id'];
echo "Product ID: " . $productID;

// تعديل استعلام SQL لجلب بيانات البائع مع المنتج
$sqlProduct = "
    SELECT p.Title, p.Description, p.Price, p.Location, p.Category, u.UserID, u.Username, u.Phone
    FROM product p
    JOIN users u ON p.UserID = u.UserID
    WHERE p.ProductID = ?
";
$stmtProduct = $conn->prepare($sqlProduct);
if (!$stmtProduct) {
    die('Query preparation failed: ' . $conn->errorInfo()[2]);
}
$stmtProduct->execute([$productID]);
if ($stmtProduct->errorCode() !== '00000') {
    die('Query execution failed: ' . $stmtProduct->errorInfo()[2]);
}
$product = $stmtProduct->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "Product not found.";
    exit();
}

// استعلام لجلب صور المنتج
$sqlImages = "SELECT ImageDescription FROM image WHERE ProductID = ?";
$stmtImages = $conn->prepare($sqlImages);
if (!$stmtImages) {
    die('Query preparation failed: ' . $stmtImages->errorInfo()[2]);
}
$stmtImages->execute([$productID]);
if ($stmtImages->errorCode() !== '00000') {
    die('Query execution failed: ' . $stmtImages->errorInfo()[2]);
}
$images = $stmtImages->fetchAll(PDO::FETCH_COLUMN);

// مسار الصورة الافتراضية
$defaultImagePath = "uploads/default.png";

// استعلام لجلب متوسط التقييمات لجميع منتجات البائع
$sqlAvgRating = "
    SELECT AVG(r.Rating) as averageRating
    FROM reviews r
    JOIN product p ON r.ProductID = p.ProductID
    WHERE p.UserID = ?
";
$stmtAvgRating = $conn->prepare($sqlAvgRating);
if (!$stmtAvgRating) {
    die('Query preparation failed: ' . $conn->errorInfo()[2]);
}
$stmtAvgRating->execute([$product['UserID']]);
if ($stmtAvgRating->errorCode() !== '00000') {
    die('Query execution failed: ' . $stmtAvgRating->errorInfo()[2]);
}
$avgRating = $stmtAvgRating->fetch(PDO::FETCH_ASSOC)['averageRating'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title><?= htmlspecialchars($product['Username']) ?></title>
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
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
        .product-image {
            margin-bottom: 1rem;
            max-width: 100%;
            width: 100%;
            height: auto;
            border: 1px solid #ddd;
            padding: 5px;
        }
        .images-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .seller-rating span {
            color: #ffd700;
        }
    </style>
    <script>
        // Scroll to the top of the page on refresh
        window.onload = function() {
            window.scrollTo(0, 0);
        }
    </script>
</head>
<body>
    <nav class="navbar navbar-light custom-navbar">
    <div class="container">
        <a class="navbar-brand" href="index.php"><img class="navbar-brand" src="assets/img/logoname2.png" width="230" hight="230"></a>
            
        </div>
    </nav>
    <main id="main">
        <section class="section">
            <div class="container">
                <div class="row mb-4 align-items-center">
                    <div class="col-md-6" data-aos="fade-up">
                        <h2><?= htmlspecialchars($product['Username']) ?></h2>
                        <div class="seller-rating">
                            <?php
                                $fullStars = floor($avgRating);
                                $halfStar = $avgRating - $fullStars >= 0.5 ? true : false;

                                for ($i = 0; $i < $fullStars; $i++) {
                                    echo '<span class="bi bi-star-fill"></span>';
                                }

                                if ($halfStar) {
                                    echo '<span class="bi bi-star-half"></span>';
                                }

                                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                for ($i = 0; $i < $emptyStars; $i++) {
                                    echo '<span class="bi bi-star"></span>';
                                }

                                echo '<span>' . round($avgRating, 1) . '</span>';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="site-section pb-0">
                <div class="container">
                    <div class="row align-items-stretch">
                        <div class="col-md-8" data-aos="fade-up">
                            <div class="images-container">
                                <?php if (count($images) > 0 && $images[0] != '' && strpos($images[0], 'default.png') === false): ?>
                                    <?php foreach ($images as $image): ?>
                                        <img src="uploads/<?= htmlspecialchars($image) ?>" alt="Product Image" class="product-image">
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <img src="<?= htmlspecialchars($defaultImagePath) ?>" alt="Default Product Image" class="product-image">
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-3 ml-auto" data-aos="fade-up" data-aos-delay="100">
                            <div class="sticky-content">
                                <ul class="list-unstyled list-line mb-5">
                                    <li><h3 class="h3"><?= htmlspecialchars($product['Title']) ?></h3></li>
                                    <li><p class="mb-4">السعر :<span class="text-muted">$<?= htmlspecialchars($product['Price']) ?></span></p></li>
                                    <li>
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="h4 mb-3">:شرح المنتج</h4>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text"><?= htmlspecialchars($product['Description']) ?></p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>الموقع: <?= htmlspecialchars($product['Location']) ?></li>
                                    <li>التصنيف: <?= htmlspecialchars($product['Category']) ?></li>
                                </ul>
                                <a href="https://wa.me/<?= htmlspecialchars($product['Phone']) ?>?text=I'm%20interested%20in%20your%20product%20titled%20<?=urlencode($product['Title'])?>" class="btn btn-success mt-3"><i class="bi bi-whatsapp"></i> تواصل عبر الواتساب</a>
                                <a href="https://waffyapp.com/" target="_blank" class="alert alert-info mt-3 d-block text-decoration-none" role="alert">
                                    <strong>ملاحظة:</strong> الدفع يكون عن طريق وفّي لضمان حقوقك وعدم الاحتيال عليك.
                                </a>
                            
                                <!-- زر فتح نموذج التقييم -->
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal">
                                    قيم البائع
                                </button>
                            </div>
                            <!-- نموذج التقييم (داخل Modal) -->
                            <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="reviewModalLabel">تقييم البائع</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form id="reviewForm" method="POST" action="submit_review.php">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="rating" class="form-label">التقييم (1-5):</label>
                                                    <input type="number" class="form-control" id="rating" name="rating" min="1" max="5" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="reviewText" class="form-label">التعليق:</label>
                                                    <textarea class="form-control" id="reviewText" name="reviewText" rows="3"></textarea>
                                                </div>
                                                <input type="hidden" name="productID" value="<?= htmlspecialchars($productID) ?>">
                                                <input type="hidden" name="userID" value="<?= htmlspecialchars($_SESSION['UserID']) ?>"> <!-- Assuming user is logged in and UserID is stored in session -->
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                                <button type="submit" class="btn btn-primary">إرسال التقييم</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
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
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>

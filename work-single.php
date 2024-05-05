<?php
require 'contc.php';

if (!isset($_GET['id'])) {
    header('Location: index1.php');
    exit();
}

$productID = $_GET['id'];
echo "Product ID: " . $productID;

// تعديل استعلام SQL لجلب بيانات البائع مع المنتج
$sqlProduct = "
    SELECT p.Title, p.Description, p.Price, p.Location, p.Category, u.Username, u.Phone
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
            <a class="navbar-brand" href="index1.php">عثور</a>
            <a href="#" class="burger" data-bs-toggle="collapse" data-bs-target="#main-navbar">
                <span></span>
            </a>
        </div>
    </nav>
    <main id="main">
        <section class="section">
            <div class="container">
                <div class="row mb-4 align-items-center">
                    <div class="col-md-6" data-aos="fade-up">
                        <h2><?= htmlspecialchars($product['Username']) ?></h2> <!-- استخدام اسم المستخدم بدلاً من عنوان المنتج -->
                        <div class="seller-rating">
                            <span class="bi bi-star-fill"></span>
                            <span class="bi bi-star-fill"></span>
                            <span class="bi bi-star-fill"></span>
                            <span class="bi bi-star-fill"></span>
                            <span class="bi bi-star-half"></span>
                            <span>4.5</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="site-section pb-0">
                <div class="container">
                    <div class="row align-items-stretch">
                        <div class="col-md-8" data-aos="fade-up">
                            <div class="images-container">
                                <?php foreach ($images as $image): ?>
                                    <img src="uploads/<?= htmlspecialchars($image) ?>" alt="Product Image" class="product-image">
                                <?php endforeach; ?>
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
                    <p class="mb-1">&copy; Copyright MyPortfolio. All Rights Reserved</p>
                    <div class="credits">Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a></div>
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

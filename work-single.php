<?php
require 'contc.php'; // الاتصال بقاعدة البيانات

// التحقق مما إذا كان مفتاح 'id' موجودًا في GET
if (!isset($_GET['id'])) {
  header('Location: index1.php'); // استبدل 'products.php' بصفحة المنتجات الفعلية
  exit();
}
$productID = $_GET['id']; // استخدم المعرف فقط إذا كان موجودًا

// استعلام لجلب بيانات المنتج
$sql = "SELECT p.Title, p.Description, p.Price, p.Location, p.Category, i.ImageDescription
        FROM product p
        LEFT JOIN image i ON p.ProductID = i.ProductID
        WHERE p.ProductID = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$productID]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "Product not found.";
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title><?= htmlspecialchars($product['Title']) ?></title>
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-light custom-navbar">
        <div class="container">
            <a class="navbar-brand" href="index.html">عثور</a>
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
                        <h2><?= htmlspecialchars($product['Title']) ?></h2>
                    </div>
                </div>
            </div>
            <div class="site-section pb-0">
                <div class="container">
                    <div class="row align-items-stretch">
                        <div class="col-md-8" data-aos="fade-up">
                            <img src="uploads/<?= htmlspecialchars($product['ImageDescription']) ?>" alt="Product Image" class="img-fluid">
                        </div>
                        <div class="col-md-3 ml-auto" data-aos="fade-up" data-aos-delay="100">
                            <div class="sticky-content">
                            <ul class="list-unstyled list-line mb-5">
                            <li>  <h3 class="h3"><?= htmlspecialchars($product['Title']) ?></h3></li>
                            <li>  <p class="mb-4">السعر :<span class="text-muted">$<?= htmlspecialchars($product['Price']) ?></span></p> </li>
                            <li>  <h4 class="h4 mb-3"> :شرح المنتج</h4></li>
                            <li>   <div class="mb-5"><?= htmlspecialchars($product['Description']) ?></div></li>
                                
                               
                                    <li>Location: <?= htmlspecialchars($product['Location']) ?></li>
                                    <li>Category: <?= htmlspecialchars($product['Category']) ?></li>
                                </ul>
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

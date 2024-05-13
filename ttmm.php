<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعبئة بيانات المنتج</title>
    <!-- تضمين مكتبة Bootstrap CSS من CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- تضمين ملف CSS مخصص -->
    <link href="styles.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">تعبئة بيانات المنتج</h2>
                </div>
                <div class="card-body">
                    <form id="productForm" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="productName">اسم المنتج:</label>
                            <input type="text" id="productName" name="productName" class="form-control" required>
                            <div id="productNameError" class="invalid-feedback"></div>
                        </div>

                        <div class="form-group">
                            <label for="productDescription">وصف المنتج:</label>
                            <textarea id="productDescription" name="productDescription" class="form-control" rows="3" required></textarea>
                            <div id="productDescriptionError" class="invalid-feedback"></div>
                        </div>

                        <div class="form-group">
                            <label for="productPrice">سعر المنتج:</label>
                            <input type="text" id="productPrice" name="productPrice" class="form-control" required>
                            <div id="productPriceError" class="invalid-feedback"></div>
                        </div>

                        <div class="form-group">
                            <label for="productType">نوع المنتج:</label>
                            <select id="productType" name="productType" class="form-control" required>
                                <option value="">اختر نوع المنتج</option>
                                <option value="إلكترونيات">إلكترونيات</option>
                                <option value="ملابس">ملابس</option>
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
                            <input type="file" id="productImages" name="productImages[]" class="form-control-file" multiple required>
                            <div id="productImagesError" class="invalid-feedback"></div>
                        </div>

                        <button type="submit" name="send" class="btn btn-primary">نشر</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- تضمين مكتبة Bootstrap JS من CDN -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- تضمين مكتبة jQuery من CDN -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- تضمين ملف JavaScript مخصص -->
<script src="scripttt.js"></script>
</body>
</html>

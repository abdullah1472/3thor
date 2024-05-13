$(document).ready(function(){
    $('#productForm').submit(function(e){
        e.preventDefault(); // منع إرسال النموذج
        
        // التحقق من البيانات وعرض رسائل الخطأ إذا لزم الأمر
        var productName = $('#productName').val();
        var productDescription = $('#productDescription').val();
        var productPrice = $('#productPrice').val();
        var productType = $('#productType').val();
        var productLocation = $('#productLocation').val();
        var productImages = $('#productImages').val();
        
        // يُمكنك إضافة المزيد من التحققات هنا
        
        // عرض رسائل الخطأ إذا كان هناك خطأ في البيانات المُدخلة
        if (productName === '') {
            $('#productNameError').text('يرجى إدخال اسم المنتج').show();
        } else {
            $('#productNameError').hide();
        }
        
        if (productDescription === '') {
            $('#productDescriptionError').text('يرجى إدخال وصف المنتج').show();
        } else {
            $('#productDescriptionError').hide();
        }
        
        if (productPrice === '') {
            $('#productPriceError').text('يرجى إدخال سعر المنتج').show();
        } else {
            $('#productPriceError').hide();
        }
        
        if (productType === '') {
            $('#productTypeError').text('يرجى اختيار نوع المنتج').show();
        } else {
            $('#productTypeError').hide();
        }
        
        if (productLocation === '') {
            $('#productLocationError').text('يرجى إدخال موقع المنتج').show();
        } else {
            $('#productLocationError').hide();
        }
        
        if (productImages === '') {
            $('#productImagesError').text('يرجى تحديد صور المنتج').show();
        } else {
            $('#productImagesError').hide();
        }
        
        // إذا كانت جميع البيانات صحيحة، يمكنك هنا إرسال النموذج
        if (productName !== '' && productDescription !== '' && productPrice !== '' && productType !== '' && productLocation !== '' && productImages !== '') {
            // يُمكنك هنا إرسال النموذج باستخدام AJAX أو تحويل النموذج إلى صفحة PHP لمعالجته
            alert('تم إرسال النموذج بنجاح!');
            // هنا يُمكن توجيه المستخدم إلى صفحة أخرى بعد إرسال النموذج
        }
    });
});

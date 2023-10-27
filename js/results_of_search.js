
$(document).ready(function() {
    function updateCartBadge() {
        $.ajax({
            type: "GET",
            url: "cart_count.php",
            dataType: "json", // Replace with the actual URL to get cart count
            success: function(response) {
                var cartCount = response.count;
                $("#cartBadge").text(response.count);
                if (cartCount === 0) {
                    $("#cartBadge").hide();
                } else {
                    $("#cartBadge").show();
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                console.error("Error fetching cart count:", textStatus, errorThrown);
            }
        });
    }    
    function addToCart(productId) {
        $.ajax({
            type: "POST",
            url: "add_to_cart_process.php",
            data: { product_id: productId },
            success: function(response) {
                updateCartBadge();
                var showToast = $("#toastMessage");
                showToast.show();
                if (response.status === "success") {
                    showToast.text(response.message); 
                    showToast.addClass("success-toast"); 
                }else{
                    showToast.text(response.message); 
                    showToast.addClass("error-toast"); 
                }
                // After a delay, hide the toast message again
                setTimeout(function() {
                    showToast.hide();
                    showToast.removeClass("success-toast error-toast"); // Remove styling classes
                }, 3500);
            }
        });
    }
    $(".addToCart").on("click", function() {
        const productId = $(this).data("product-id");
        addToCart(productId);
    });
    updateCartBadge();
});

function showLoginAnnouncement(event) {
    if(event){
    event.preventDefault();
    var showToast = $('#toastMessage');
    showToast.show();
    showToast.addClass('announce');
    showToast.text("Bạn Chưa Đăng Nhập");
    setTimeout(function() {
        showToast.hide();
    }, 4000);
}else{
    var showToast = $('#toastMessage');
    showToast.show();
    showToast.addClass('announce');
    showToast.text("Bạn Chưa Đăng Nhập");
    setTimeout(function() {
        showToast.hide();
    }, 4000);
}
}

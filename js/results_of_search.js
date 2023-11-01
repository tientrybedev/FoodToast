// hiển thị số lượng trong giỏ hàng
$(document).ready(function() {
    function updateCartBadge() {
        $.ajax({
            type: "GET",
            url: "cart_count.php",
            dataType: "json", 
            success: function(response) {
                var cartCount = response.count;
                $("#cartBadge").text(response.count);
                $("#cartBadge2").text(response.count);
                if (cartCount === 0) {
                    $("#cartBadge, #cartBadge2").hide();
                } else {
                    $("#cartBadge, #cartBadge2").show();
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
                setTimeout(function() {
                    showToast.hide();
                    showToast.removeClass("success-toast error-toast"); 
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

$(document).ready(function () {
    function updateFavoriteProductCount() {
        $.ajax({
            type: "GET",
            url: "favor_count.php", // Replace with the actual URL
            dataType: "json",
            success: function(response) {
                var favoriteProductCount = response.count;
                $("#favoriteProductCount").text(response.count);
                $("#favoriteProductCount2").text(response.count);
                if (favoriteProductCount === 0) {
                    $("#favoriteProductCount, #favoriteProductCount2").hide();
                } else {
                    $("#favoriteProductCount, #favoriteProductCount2").show();
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                console.error("Error fetching favorite product count:", textStatus, errorThrown);
            }
        });
    }
    updateFavoriteProductCount()
    $(".like").on("click", function () {
        const userId = $(this).data("user-id");
        const isLoggedIn = document.querySelector(".not-logged-in");
        const productId = $(this).data("product-id");
        const likeButton = $(this);
        if (!isLoggedIn) {
            $.ajax({
                type: "POST",
                url: "add_to_favorites.php",
                data: { user_id: userId, product_id: productId },
                dataType: "json",
                success: function (response) {
                    console.log("Ajax response:", response); 
                    if (response.status === "success") {
                        updateFavoriteProductCount();
                        var showToast = $("#toastMessage");
                        showToast.show();
                        showToast.text(response.message);
                    showToast.addClass("success-toast");
                        console.log("Like button:", likeButton); 
                        likeButton.find("i").css({
                            pointerEvents: 'none',
                            color: 'var(--heart-color)',
                            transform: 'scale(1.2)'
                        });
                    }
                    setTimeout(function () {
                        showToast.hide();
                        showToast.removeClass("success-toast");
                    }, 3500);
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.error("Error: " + textStatus, errorThrown);
                    alert("Lỗi.");
                }
            });
        } else {
            showLoginAnnouncement();
        }
    });
});
function dropDownFunction() {
    const navResponsive = document.getElementById("navResponsive");

    // Get the current height of navResponsive
    const currentHeight = navResponsive.clientHeight;

    // Define the height you want when expanded
    const expandedHeight = 250; // You can change this value as needed

    if (currentHeight === expandedHeight) {
        navResponsive.style.height = "75px"; // Collapse
        navResponsive.style.borderBottomRightRadius = "5px";
        navResponsive.style.borderBottomLeftRadius = "5px";
    } else {
        navResponsive.style.height = expandedHeight + "px"; // Expand
        navResponsive.style.borderBottomRightRadius = "15px";
        navResponsive.style.borderBottomLeftRadius = "15px";
    }
}

const dropDownBtn = document.getElementById("responsiveMenuDropDown");

// Attach the dropDownFunction to the click event of dropDownBtn
dropDownBtn.addEventListener("click", dropDownFunction);


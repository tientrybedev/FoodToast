const imgs = document.querySelectorAll('.img-select a');
const imgBtns = [...imgs];
let imgId = 1;

imgBtns.forEach((imgItem) => {
    imgItem.addEventListener('click', (event) => {
        event.preventDefault();
        imgId = imgItem.dataset.id;
        slideImage();
    });
});

function slideImage(){
    const displayWidth = document.querySelector('.img-showcase img:first-child').clientWidth;

    document.querySelector('.img-showcase').style.transform = `translateX(${- (imgId - 1) * displayWidth}px)`;
}

window.addEventListener('resize', slideImage);

function changeActive(image) {
    const images = document.querySelectorAll('.img-item img');
    images.forEach((img) => {
        img.classList.remove('active');
    });
    image.classList.add('active');
}

window.addEventListener('resize', slideImage);

function changeActive(image) {
    const images = document.querySelectorAll('.img-item img');
    images.forEach((img) => {
        img.classList.remove('active');
    });
    image.classList.add('active');
}

$(document).ready(function() {
    var $productDetail = $('.product-detail p');
    
    if ($productDetail[0].scrollHeight > $productDetail.height()) {
        $productDetail.css('overflow-y', 'scroll');
    } else {
        $productDetail.css('overflow-y', 'hidden');
    }
});


document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("starsRatingForm");
    const textarea = form.querySelector("textarea");
    const radios = form.querySelectorAll('input[name="rate"]');
    const starIcons = form.querySelectorAll('.fa-star');
    const toastMessage = document.querySelector("#toastMessage");
    form.addEventListener("submit", function (event) {
        if (textarea.value.trim() === "") {
            event.preventDefault();
            showToast("Bạn chưa bình luận");
        } else {
            let isChecked = false;
            radios.forEach((radio) => {
                if (radio.checked) {
                    isChecked = true;
                }
            });
            if (!isChecked) {
                event.preventDefault();
                showToast("Bạn chưa đánh giá");
                starIcons.forEach((star) => {
                    star.classList.add("shake");
                    setTimeout(function () {
                        star.classList.remove("shake");
                    }, 300);
                });
            }
        }
        function showToast(message) {
            toastMessage.textContent = message;
            toastMessage.style.display = "block";
                toastMessage.style.opacity = "1";


            setTimeout(function () {
                toastMessage.style.display = "none";
            }, 2500);
        }
    });
});

function displayRatingResult(ratingValue) {
    const ratingResult = document.querySelector('.rating-ele');
    const ratingText = getRatingText(ratingValue);
    ratingResult.textContent = ratingText;
}

function getRatingText(value) {
    switch (value) {
        case "5":
            return "Rất tuyệt vời";
        case "4":
            return "Tốt";
        case "3":
            return "Khá";
        case "2":
            return "Không ưng ý";
        case "1":
            return "Rất thất vọng";
        default:
            return "";
    }
}

const stars = document.querySelectorAll('input[name="rate"]');
stars.forEach((star) => {
    star.addEventListener("change", function () {
        displayRatingResult(star.value);
    });
});

$(document).ready(function () {
    function updateCartBadge() {
        $.ajax({
            type: "GET",
            url: "cart_count.php", 
            dataType: "json",
            success: function(response) {
                var cartCount = response.count;
                $("#cartBadge").text(cartCount);

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

    $(".addToCart").on("click", function () {
        const productId = $(this).data("product-id");
        const quantity = $("#quantities").val(); 
        if (quantity === "") {
            quantity = 1;
            addToCart(productId, quantity);
        } else {
            addToCart(productId, quantity);
        }
    });

    function addToCart(productId, quantity) {
        var data = { product_id: productId };
        if (quantity) {
            data.quantity = quantity;
        }
        $.ajax({
            type: "POST",
            url: "add_to_cart_process.php",
            data: { product_id: productId, quantity: quantity },
            success: function (response) {
                var showAnnounce = $("#cartAnnounce");
                showAnnounce.show();
                if (response.status === "success") {
                    showAnnounce.text(response.message);
                    showAnnounce.addClass("success-announce");
                } 
                setTimeout(function () {
                    showAnnounce.hide();
                    showAnnounce.removeClass("success-announce"); 
                }, 3500);
                updateCartBadge(); 
            }
        });
    }

    updateCartBadge();
});
function showLoginAnnouncement(event) {
    if(event){
    event.preventDefault();
    var showToast = $('#cartAnnounce');
    showToast.show();
    showToast.addClass('announce');
    showToast.text("Bạn Chưa Đăng Nhập");
    setTimeout(function() {
        showToast.hide();
    }, 4000);
}else{
    var showToast = $('#cartAnnounce');
    showToast.show();
    showToast.addClass('announce');
    showToast.text("Bạn Chưa Đăng Nhập");
    setTimeout(function() {
        showToast.hide();
    }, 4000);
}
}



$(document).ready(function () {
    $(".addToFavor, .like").on("click", function () {
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
                        var showToast = $("#cartAnnounce");
                        showToast.show();
                        showToast.text(response.message);
                        showToast.addClass("success-toast");
                        likeButton.find("i").css({
                            pointerEvents: 'none',
                            color: 'var(--heart-color)',
                            transform: 'scale(1.2)'
                        });
                    }
                    setTimeout(function () {
                        if(showToast){
                        showToast.hide();
                        showToast.removeClass("success-toast");
                        }
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

const checkboxes = document.querySelectorAll('input[type="checkbox"]');
const showChoices = document.getElementById("showCategoryChoices");
checkboxes.forEach(checkbox => {
    checkbox.addEventListener('change', () => {
        const selectedChoices = [];
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                selectedChoices.push(document.querySelector(`label[for="${checkbox.id}"]`).textContent);
            }
        });
        if(selectedChoices.length>0){
            showChoices.textContent = `${selectedChoices.join(', ')}`;
        }else{
            showChoices.textContent = 'Bạn chưa chọn gì';
        }
    });
});
$(document).ready(function () {
    $('#advancedSearchForm').submit(function (e) {
        e.preventDefault(); 
        const selectedMeals = [];
        $('input[name="meals[]"]:checked').each(function () {
            selectedMeals.push($(this).val());
        });
        if (selectedMeals.length === 0) {
            $('#validationMessage').text('Hãy chọn ít nhất 1 phân loại');
        } else {
            $('#validationMessage').text('');
            $.ajax({
                type: 'POST',
                url: 'advance_search_process.php',
                data: { meals: selectedMeals },
                success: function (data) {
                    $('.filter-result').html(data);
                }
            });
        }
    });
});

$(document).ready(function () {
    $('input[name="meals[]"]').click(function () {
        $(this).parent().toggleClass('selected');
    });
});

$(document).ready(function() {
    function updateCartBadge() {
        $.ajax({
            type: "GET",
            url: "cart_count.php",
            dataType: "json",
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
                setTimeout(function() {
                    showToast.hide();
                    showToast.removeClass("success-toast error-toast"); // Remove styling classes
                }, 3500);
            }
        });
    }
    $(document).on("click", ".addToCart", function () {
        const productId = $(this).data("product-id");
        addToCart(productId);
        console.log("addToCart button clicked. Product ID: " + productId);
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
    $(document).on("click", ".like", function () {
        const userId = $(this).data("user-id");
        const isLoggedIn = document.querySelector(".not-logged-in");
        const productId = $(this).data("product-id");
        const likeButton = $(this);
        console.log("Clicked element:", this); 
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
                        showToast.addClass("success-announce");
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




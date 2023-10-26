function loadCart() {
    $.ajax({
        type: "GET",
        url: "cart.php",
        success: function(response) {
            $("#cart-container").html(response);
        }
    });
}

$(".plus").on("click", function() {
    const productId = $(this).data("product-id");
    increaseQuantity(productId);
});

// When a "Minus" button is clicked
$(".minus").on("click", function() {
    const productId = $(this).data("product-id");
    decreaseQuantity(productId);
});

// Function to handle increasing quantity
function increaseQuantity(productId) {
    updateQuantity(productId, "increase");
}

function decreaseQuantity(productId) {
    updateQuantity(productId, "decrease");
}
function updateQuantity(productId, action) {
    let url;
    if (action === "increase") {
        url = "increase_product.php";
    } else if (action === "decrease") {
        url = "decrease_product.php";
    }

    $.ajax({
        type: "POST",
        url: url,
        data: {product_id: productId},
        success: function(response) {
            if (response === "Quantity updated successfully!") {
                loadCart();
            } else {
                alert(response);
            }
        }
    });
}

$(".delete").on("click", function() {
    const productId = $(this).data("product-id");
    deleteProduct(productId);
});
function deleteProduct(productId) {
    $.ajax({
        type: "POST",
        url: "delete_product_in_cart.php", 
        data: { product_id: productId },
        success: function(response) {
            if (response === "Product removed from cart!") {
                loadCart();
            } else {
                alert(response);
            }
        }
    });
}


$(".product-checkbox").on("change", function() {
    const productId = $(this).data("product-id");
    const isChecked = $(this).is(":checked");
    $.ajax({
        type: "POST",
        url: "selected_product_in_cart.php", // Create this PHP script to handle the update
        data: { product_id: productId, checked: isChecked ? 1 : 0 },
        success: function(response) {
            if (response === "Updated successfully!") {
                // Update the overall total or perform any other necessary actions
                updateOverallTotal();
            } else {
                alert(response);
            }
        }
    });
});

function updateOverallTotal() {
    let selectedTotal = 0;
    const selectedProducts = [];

    // Iterate through all checkboxes and calculate the total for checked products
    $(".product-checkbox:checked").each(function() {
        const price = parseFloat($(this).data("product-price"));
        const quantity = parseInt($(this).data("product-quantity"));
        const productId = $(this).data("product-id");
        const subtotal = price * quantity;
        selectedTotal += price * quantity;
        selectedProducts.push({
            id: productId,
            quantity: quantity,
            price: price,
            subtotal: subtotal
        });
    });
    const toast = document.getElementById("toastMessage");
    if (selectedProducts.length === 0) {
        $("#checked-total").text("Vui lòng chọn sản phẩm");
    } else {
    $("#checked-total").text("Tổng tiền: " + selectedTotal.toFixed(3) + " VNĐ");
    }
    $(".final-payment").off("click"); 
    $(".final-payment").on("click", function() {
        if (selectedProducts.length === 0) {
            toast.textContent = "Vui lòng chọn sản phẩm trước khi thanh toán.";
            $("#checked-total").addClass("shake-animation")
            
            setTimeout(function () {
                toast.style.display = "block";
                $("#checked-total").removeClass("shake-animation")
            }, 550);
            setTimeout(function () {
                toast.style.display = "block";
            }, 100);
            toast.style.animation ="showup 1s forwards"
            toast.style.animation ="close 1s 2.5s forwards"
            setTimeout(function () {
                toast.style.display = "none";
            }, 3000); 
        } else {
        const selectedProductData = selectedProducts.map(product => `${product.id}:${product.quantity}:${product.price}:${product.subtotal}`).join(',');
        window.location.href = 'payment.php?selectedProducts=' + selectedProductData;
        }
    });
}

$(document).ready(function() {
    $(".product-checkbox").on("change", updateOverallTotal);
    updateOverallTotal();
});
    // Wait for the page to load
    document.addEventListener("DOMContentLoaded", function () {
        const productContainer = document.querySelector(".product-container");
        const products = document.querySelectorAll(".products");

        // Check if there are more than three products
        if (products.length > 3) {
            productContainer.classList.add("scrollbar");
        }
    });




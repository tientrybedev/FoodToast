<?php
include("connect.php");
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $sqlCart = "SELECT c.*, p.name AS product_name, p.image_1 FROM cart c
    JOIN products p ON c.product_id = p.product_id
    WHERE c.user_id = ?";
    $stmtCart = $conn->prepare($sqlCart);
    $stmtCart->bind_param("i", $user_id); 
    $stmtCart->execute();
    $resultCart = $stmtCart->get_result();
    $stmtCart->close();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="Home-img/food-logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css/cart.css">
    <title>Giỏ hàng</title>
</head>
<body>

    <div class="cart-container" id= cart-container>
    <h2 class= cart-header>giỏ hàng</h2>
    <div class="toast" id="toastMessage"></div>
        <div class="cart" id="cart" >
            <div class="turn-back-links">
                <a href="javascript:history.go(-1)">Quay lại </a>
                <a href="index.php" >Trang chủ</a>
                <a href="produces-page.php">Menu</a>
            </div>

            <div class="product-container">
                
                <?php
                    if ($resultCart->num_rows > 0) {
                        while ($rowCart = $resultCart->fetch_assoc()) {
                            $product_id = $rowCart['product_id'];
                            $price = $rowCart['price'];
                            $quantity = $rowCart['quantity'];
                            $pro_name= $rowCart['product_name'];
                            $pro_Img = $rowCart['image_1'];

                            $totalAmount = number_format($price * $quantity, 3, '.', ''); 
                                echo '<div class="products">';
                                echo'<div class="option">';
                                echo '<div class="checkbox-wrapper-31">';
                                echo '<input type="checkbox" id="checkbox_' . $product_id . '" class="product-checkbox" data-product-id="' . $product_id . '" data-product-price="' . $price . '" data-product-quantity="' . $quantity . '" ' . ($rowCart['selected'] == 1 ? 'checked' : '') . ' />';
                                echo '<svg viewBox="0 0 35.6 35.6">';
                                echo '<circle class="background" cx="17.8" cy="17.8" r="17.8"></circle>';
                                echo '<circle class="stroke" cx="17.8" cy="17.8" r="14.37"></circle>';
                                echo '<polyline class="check" points="11.78 18.12 15.55 22.23 25.17 12.87"></polyline>';
                                echo '</svg>';
                                echo '</div>';
                                echo'</div>';
                            echo '<div class="products-section">';
                            echo '<div class="product-img-container">';
                            echo '<img src="' . $pro_Img . '" alt="' . $pro_name . '" width="100"><br>';
                            echo '</div>';
                            echo '<a href="Single-product-detail.php?product_id=' . $product_id . '" class="detail-btn"> Chi tiết sản phẩm <i class="fa-solid fa-circle-question"></i></a>';
                            echo '<button class="delete" data-product-id="' . $product_id . '">Bỏ khỏi giỏ hàng <i class="fa-solid fa-trash"></i></button>';
                            echo '</div>';
                            echo '<div class="products-content">';
                            echo "<h4> $pro_name</h4>";
                            echo "<p>Giá: <b> $price</b></p> ";
                            echo '<div class="products-amount">';
                            echo "<p>Tổng tiền: $totalAmount</p>";
                            echo '</div>';
                            echo '</div>';
                            echo '<div class="products-quantity-btn">';
                            echo '<button class="plus" data-product-id="' . $product_id . '"><i class="fa-solid fa-plus"></i></button>';
                            echo "<b>$quantity</b>";
                            echo '<button class="minus" data-product-id="' . $product_id . '"><i class="fa-solid fa-minus"></i></button>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo "Chưa có gì trong giỏ hàng";
                    }   
                ?>
    
            </div>
            <div class ="products-total">
                <h1>Thanh toán</h1>
                <span id="checked-total" class="checked-total">Tổng tiền: 0.000 VNĐ</span>
                <button class="final-payment">Thanh toán<i class="fa-solid fa-truck-ramp-box"></i></button>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" ></script> 
    <script src="js/cart.js"></script>
</body>
</html> 

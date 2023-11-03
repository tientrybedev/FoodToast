<?php
include("connect.php");
session_start();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT username, phone FROM users WHERE user_id = '$user_id'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if ($row) {
            $username = $row['username'];
            $phone = $row['phone'];
        }
    } 
}

if (isset($_GET['selectedProducts']) ) {
    $selectedProductData = urldecode($_GET['selectedProducts']);
    $productDetailsHTML = '';
    $totalAmount=0;
    $selectedProducts = explode(',', $selectedProductData);
    foreach ($selectedProducts as $productData) {
        list($productId, $quantity, $price, $subtotal) = explode(':', $productData);
        $totalAmount += $subtotal;
        // Fetch product details from your database using $productId
        $sql = "SELECT * FROM products WHERE product_id ='$productId'";
        $result = mysqli_query($conn, $sql);
        
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            if ($row) {
                $pro_name = $row['name'];
                $pro_img = $row['image_1'];
                $vnPrice = number_format($price, 3, '.', '');
                $total = $price * $quantity;
                $categories = [];
                if ($row['breakfast']) {
                    $categories[] = 'Bữa sáng';
                }
                if ($row['lunch']) {
                    $categories[] = 'Bữa trưa';
                }
                if ($row['dinner']) {
                    $categories[] = 'Bữa tối';
                }
                if ($row['dessert']) {
                    $categories[] = 'Tráng miệng';
                }
                if ($row['snacks']) {
                    $categories[] = 'Ăn vặt';
                }
                if ($row['drinks']) {
                    $categories[] = 'Thức uống';
                }
                if ($row['rices']) {
                    $categories[] = 'Món cơm';
                }
                if ($row['diet']) {
                    $categories[] = 'Ăn kiêng';
                }
                if ($row['soup']) {
                    $categories[] = 'Món nước';
                }  
                // Add product details to the HTML variable
                $productDetailsHTML .= '<div class="product">';
                $productDetailsHTML .= '<div class="product-img">';
                $productDetailsHTML .= '<img src="' . $pro_img . '" alt="' . $pro_name . '">';
                $productDetailsHTML .= '</div>';
                $productDetailsHTML .= '<div class="product-details">';
                $productDetailsHTML .= '<h3>' . $pro_name . '</h3>';
                $productDetailsHTML .= '<p>Phân loại: ' . implode(', ', $categories) . '</p>';
                $productDetailsHTML .= '<p>Giá: ' . $vnPrice . ' VNĐ</p>';
                $productDetailsHTML .= '<p>Số lượng: ' . $quantity . '</p>';
                $productDetailsHTML .= '<p style="color: red">Tổng cộng: ' . number_format($total, 3, '.', '') . '</p>';
                $productDetailsHTML .= '</div>';
                $productDetailsHTML .= '</div>';
            }
        }
    }
}
?>


<!-- Display the details for each selected product -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/payment.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="shortcut icon" href="Home-img/food-logo.png" type="image/x-icon">
    <title>Thanh Toán</title>
</head>
<body>
    <div class="all">    
        <header>
            <div class="nav">
                <a href="index.php">Trang chủ</a>
                <a href="produces-page.php">Trang sản phẩm</a>
                <a href="cart.php">Giỏ hàng</a>
            </div>
        </header>
        <main>
            <div class="order_container">
                <div class="product-container" <?php echo (count($selectedProducts) > 3) ? 'style="max-height: 670px; overflow-y: auto; overscroll-behavior: contain;"' : ''; ?>>
                    <?php echo $productDetailsHTML; ?>
                </div>
                    <p class="totalPayment">Tổng cộng: <b><?php echo number_format($totalAmount, 3, '.', '');?> VNĐ</b></p>
            </div>
            <div class="payment_container">
                <form action="final_process.php" method="post" autocomplete="off" class="payment_form" id="paymentForm" >
                    <div class="user-info">
                        <h4>Thông tin khách hàng</h4>
                        <div class="user-input-detail">
                            <label for="realname">Họ và Tên</label>
                            <input type="text" id="realname" name="realname" placeholder="Họ và tên" oninput="validateRealName(this)"  >
                            <div class="fail-valid" id="realNameError"></div>
                        </div>
                        <div class="realNameError"></div>
                        <div class="user-input-detail">
                            <label for="address">Địa chỉ</label>
                            <input type="text"  id="address" name="address" placeholder="VD: Q8, Tp Hồ Chí Minh">
                            <div class="fail-valid" id="addressError"></div>
                        </div>
                        <div class="user-input-detail">
                            <label for="phone">Số Điện Thoại</label>
                            <input type="tel" id= "phone" name="phone" value="<?php echo $phone;?>" maxlength="10" >
                            <div class="fail-valid" id="phoneError"></div>
                        </div>
                        
                        <div class="user-input-detail">
                            <label for="username">Tên người dùng</label>
                            <input type="text"  id="username" name="username" value="<?php echo $username ?> " readonly>
                        </div>
                    </div>
                    <div class="final-detail">
                            <div class="final-detail-info">
                                <label for="totalAmount">Tổng tiền:</label>
                            <input type="text" id="totalAmount" name="totalAmount" value="<?php echo number_format($totalAmount, 3, '.', '');?>">
                            </div>
                            <div class="final-detail-info">
                                <label for="deliveryCost">Phí vận chuyển:</label>
                                <input type="text" id="deliveryCost" name="deliveryCost" value="Bạn chưa điền địa chỉ">
                            </div>
                            <div class="final-detail-info">
                                <label for="finalPayment">Tổng tiền phải trả:</label>
                                <input type="text" id="finalPayment" name="finalPayment">
                            </div>    
                        </div>
                    <div class="payment-type">
                        <label>
                            <input type="radio" name="paymentType" value="Cash" checked/>
                            <span>Tiền mặt</span>
                        </label>
                        <label>
                            <input type="radio" name="paymentType" value="Banking" id="bankingType" />
                            <span>Chuyển khoản</span>
                        </label>
                    </div>
                    <div class="card-info" id="cardInfo" >
                    <h4>Thông Tin Thẻ</h4>
                    <div class="card-form">
                        <div class="card-number-input">
                            <input type="test" class="card-number" name="card-number" id="card-number" placeholder="Số thẻ" maxlength="19" oninput="validateCardNumber(this)" >
                            <div class="fail-valid-card" id="cardNumberError"></div>
                        </div>
                        <div class="date-field">
                            <div class="month">
                                    <select name="Month">
                                        <option value="1">Tháng 1</option>
                                        <option value="2">Tháng 2</option>
                                        <option value="3">Tháng 3</option>
                                        <option value="4">Tháng 4</option>
                                        <option value="5">Tháng 5</option>
                                        <option value="6">Tháng 6</option>
                                        <option value="7">Tháng 7</option>
                                        <option value="8">Tháng 8</option>
                                        <option value="9">Tháng 9</option>
                                        <option value="10">Tháng 10</option>
                                        <option value="11">Tháng 11</option>
                                        <option value="12">Tháng 12</option>
                                    </select>
                                </div>
                                <div class="year">
                                    <select name="Year">
                                        <option value="2023">2023</option>
                                        <option value="2024">2024</option>
                                        <option value="2024">2025</option>
                                        <option value="2024">2026</option>
                                        <option value="2024">2027</option>
                                        <option value="2024">2028</option>
                                        <option value="2024">2029</option>
                                        <option value="2024">2030</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-verification">
                                <div class="cvv-input">
                                    <input type="text" placeholder="CVV" maxlength="3" id="cvvInput" name="cvv" oninput="validateCVV(this)" >
                                    <div class="fail-valid-cvv" id="cvvError"></div>
                                </div>
                                <div class="cvv-details">
                                    <p>3 chữ số ở mặt sau <br>của thẻ</p>
                                </div>
                            </div>
                    </div>
                    </div>
                    <div class="product-info" >
                        <?php
                            echo '<table>';
                            foreach ($selectedProducts as $productData) {
                            list($productId, $quantity, $price, $subtotal) = explode(':', $productData);
                            echo '<tr>';
                            echo '<td><input type="hidden" name="product_ids[]" value="' . $productId . '"></td>';
                            echo '<td><input type="hidden" name="quantities[]" value="' . $quantity . '"></td>';
                            echo '<td><input type="hidden" name="prices[]" value="' . $price . '"></td>';
                            echo '<td><input type="hidden" name="subtotals[]" value="' . number_format($subtotal, 3, '.', '') . '"></td>';
                            echo '</tr>';
                            }
                            echo '</table>';    
                        ?>
                    </div>

                    <button class="pay-btn">Đặt Hàng<i class="fa-solid fa-truck-fast"></i></button>
                </form>
            </div>
        </main>
        <div class="" id="product-details-container"></div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" ></script> 
    <script src = "js/payment.js"></script>
</body>
</html>
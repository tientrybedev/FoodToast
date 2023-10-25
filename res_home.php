<?php
session_start();
include("connect.php");
if (isset($_SESSION["success_message"])) {
    $successMessage = $_SESSION["success_message"];
    unset($_SESSION["success_message"]);
    echo '<div class="success-message">' . $successMessage . '</div>';
}

if (isset($_SESSION["error_messages"])) {
    $errorMessages = $_SESSION["error_messages"];
    unset($_SESSION["error_messages"]);
    echo '<div class="error-message">' . implode(' ', $errorMessages) . '</div>';
}

$isRestaurantLoggedIn = isset($_SESSION['res_loggedin']) && $_SESSION['res_loggedin'] === true;

if (isset($_GET['restaurant_id'])) {
    $restaurant_id = $_GET['restaurant_id'];
}elseif(isset($_SESSION['restaurant_id'])){
    $restaurant_id = $_SESSION['restaurant_id'];
}else{
    header("Location: res_register.php");
    exit();
}
$getRestaurantInfoSql = "SELECT restaurant_name, email, phone, description, brand_image FROM restaurants WHERE restaurant_id = ?";
$stmt = $conn->prepare($getRestaurantInfoSql);
$stmt->bind_param("i", $restaurant_id);
$stmt->execute();
$stmt->bind_result($restaurant_name, $email, $phone, $description, $brand_img);
$stmt->fetch();
$stmt->close();


$getRestaurantProductsSql = "SELECT * FROM products WHERE restaurant_id = ?";
$stmt = $conn->prepare($getRestaurantProductsSql);
$stmt->bind_param("i", $restaurant_id);
$stmt->execute();
$productResResult = $stmt->get_result();
$stmt->close();

$orderProducts ='';
$getProductOrdersSql = "SELECT od.order_id, p.product_id, od.order_status, od.quantity, od.subtotal
FROM order_details od
JOIN products p ON od.product_id = p.product_id
WHERE p.restaurant_id = ?";
$stmt = $conn->prepare($getProductOrdersSql);
$stmt->bind_param("i", $restaurant_id);
$stmt->execute();
$getProductsResult = $stmt->get_result();

$orderProducts .= '<table>';
$orderProducts .= '<tr>';
$orderProducts .= '<th>Mã đơn hàng</th>';
$orderProducts .= '<th>Mã sản phẩm</th>';
$orderProducts .= '<th>Số lượng sản phẩm</th>';
$orderProducts .= '<th>Tổng</th>';
$orderProducts .= '<th>Trạng thái đơn hàng</th>';
$orderProducts .='</tr>';
while ($row = $getProductsResult->fetch_assoc()) {
    $order_id = $row["order_id"];
    $product_id = $row["product_id"];
    $order_status = $row["order_status"];
    $order_quantity = $row["quantity"];
    $order_subtotal = $row["subtotal"];
    $dropdownId = 'orderStatusDropdown_' . $order_id . '_' . $product_id;
    $orderProducts .= '<tr>';
    $orderProducts .= '<td>' . $order_id . '</td>';
    $orderProducts .= '<td>' . $product_id . '</td>';
    $orderProducts .= '<td>' . $order_quantity  . '</td>';
    $orderProducts .= '<td>' . $order_subtotal . '</td>';
    $orderProducts .= '<td>';
    $orderProducts .= '<select id="' . $dropdownId . '" data-order-id="' . $order_id . '" data-product-id="' . $product_id . '" name="order_status">';
    $orderProducts .= '<option value="Pending" ' . ($order_status === 'Pending' ? 'selected' : '') . '>Đang chờ</option>';
    $orderProducts .= '<option value="Processing" ' . ($order_status === 'Processing' ? 'selected' : '') . '>Đang xử lý</option>';
    $orderProducts .= '<option value="Delivering" ' . ($order_status === 'Delivering' ? 'selected' : '') . '>Đang giao hàng</option>';
    $orderProducts .= '<option value="Finished" ' . ($order_status === 'Finished' ? 'selected' : '') . '>Hoàn tất</option>';
    $orderProducts .= '</select>';
    $orderProducts .= '</td>';
    $orderProducts .= '</tr>';
}
$orderProducts .= '</table>';
$product_id = $restaurant_name . "-" . getProductCountForRestaurant($restaurant_id, $conn);
function getProductCountForRestaurant($restaurant_id, $conn) {
    $count = 0; 
    $getCountSql = "SELECT COUNT(*) FROM products WHERE restaurant_id = ?";
    $stmt = $conn->prepare($getCountSql);
    $stmt->bind_param("i", $restaurant_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    return $count + 1; 
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="Home-img/food-logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/res_home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <title>Thông tin nhà hàng</title>
</head>
<body>
    <div class="res_main">
        <h1 class="res-header">Thông tin nhà hàng</h1>
    <section class="intro">
    <?php if (!$isRestaurantLoggedIn): ?>
        <div class="turn-back-link">
            <a href="index.php">Trang chủ</a>
            <a href="produces-page.php">Menu</a>
        </div>
    <?php endif; ?>
        <div class="intro-cont">
            <div class="brand-image-container">
                <img src="<?php echo$brand_img ?>" alt="">
            </div>
            <div class="information">
                <p><b>Tên nhà hàng: </b><?php echo $restaurant_name; ?></p>
                <p><b>Email nhà hàng: </b><?php echo $email; ?></p>
                <p><b>SĐT: </b><?php echo $phone; ?></p>
                <p><b>Giới thiệu:</b> <?php echo $description; ?></p>
            </div>
        </div>
        <div class="function">
            <?php if ($isRestaurantLoggedIn): ?>
                <button class="openBtn" id="addProductBtn">Đăng sản phẩm</button>
                <div class="add_product_container" id="add_product_container">
                    <div class="add_product_form">
                        <button class="close_add_form" id="closeAddProduct"><i class="fa-solid fa-xmark"></i></button>
                        <h2>Form đăng sản phẩm</h2>      
                        <form action="add_product_process.php" method="post" class="product_form" id="addProductForm" autocomplete="off" enctype="multipart/form-data">
                            <div class="form_content">
                                <div class="left_box">
                                    <div class="product_detail_input">
                                        <label for="product_id">Mã sản phẩm:</label>
                                        <input type="text" id="product_id" name="product_id" value="<?php echo $product_id; ?>" readonly>
                                        <div id="openModalDiv" onclick="openModal()"  class="change_proId_btn" ><i class="fa-solid fa-pen-to-square"></i></div>
                                        <div id="myModal" class="modal">
                                            <div class="modal-content">
                                                <span class="close" onclick="closeModal()">&times;</span>
                                                <label for="newPart">Mã ID mới:</label>
                                                <input type="text" id="newPart">
                                                <span onclick="applyChange()" class="change_ID_done" >OK</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product_detail_input">
                                        <label for="pro_name">Tên sản phẩm:</label>
                                        <input type="text" id="pro_name" name="name" >
                                        <div class="fail-validation" id="nameFail" ></div>
                                    </div>
                                    <div class="product_detail_input">
                                        <label for="pro_description">Mô tả:</label>
                                        <textarea id="pro_description" name="description" rows="auto" cols="32" style="resize: none;"></textarea>
                                        <div class="fail-validation" id="descriptionFail" ></div>
                                    </div>
                                    <h3 >Sản phẩm thuộc loại</h3>
                                    <div class="product_detail_input">
                                        <div class="checkbox-wrapper-24">
                                            <input type="checkbox" id="check-24" name="breakfast" checked />
                                            <label for="check-24">
                                                <span></span>Bữa sáng
                                            </label>
                                        </div>
                                        <div class="checkbox-wrapper-25">
                                            <input type="checkbox" id="check-25" name="lunch"  />
                                            <label for="check-25">
                                                <span></span>Bữa trưa
                                            </label>
                                        </div>
                                        <div class="checkbox-wrapper-26">
                                            <input type="checkbox" id="check-26" name="dinner" />
                                            <label for="check-26">
                                                <span></span>Bữa tối
                                            </label>
                                        </div>
                                        <div class="checkbox-wrapper-27">
                                            <input type="checkbox" id="check-27" name="dessert"  />
                                            <label for="check-27">
                                                <span></span>Tráng miệng
                                            </label>
                                        </div>
                                        <div class="checkbox-wrapper-28">
                                            <input type="checkbox" id="check-28" name="snacks"  />
                                            <label for="check-28">
                                                <span></span>Ăn vặt
                                            </label>
                                        </div>
                                        <div class="checkbox-wrapper-29">
                                            <input type="checkbox" id="check-29" name="drinks"  />
                                            <label for="check-29">
                                                <span></span>Thức uống
                                            </label>
                                        </div>
                                        <div class="checkbox-wrapper-30">
                                            <input type="checkbox" id="check-30" name="rices"  />
                                            <label for="check-30">
                                                <span></span>Món cơm
                                            </label>
                                        </div>
                                        <div class="checkbox-wrapper-31">
                                            <input type="checkbox" id="check-31" name="soup"  />
                                            <label for="check-31">
                                                <span></span>Món nước
                                            </label>
                                        </div>
                                        <div class="checkbox-wrapper-32">
                                            <input type="checkbox" id="check-32" name="diet"  />
                                            <label for="check-32">
                                                <span></span>Ăn kiêng
                                            </label>
                                        </div>
                                    </div>
                                    <div class="product_detail_input">
                                        <label for="pro_price">Giá:</label>
                                        <div class="price_display">
                                            <input type="text" id="pro_price" name="price" >
                                            <b class="currency">VNĐ</b>
                                        </div>
                                        <div class="fail-validation" id="priceFail" ></div>
                                    </div>
                                    <div class="product_detail_input">
                                        <label for="pro_quantity">Số lượng:</label>
                                        <input type="number" id="pro_quantity" name="quantity"  >
                                        <div class="fail-validation" id="quantityFail" ></div>
                                    </div>
                                </div>
                                <div class="right_box">
                                    <h3>Hình ảnh sản phẩm</h3>
                                    <div class="product_detail_input">
                                        <div class="img_input_content">
                                            <label for="image">Ảnh 1:</label><br>
                                            <input type="file" id="image" name="image[]" class="img-input" accept="image/*" onchange="previewImage(this, 'img-preview');">
                                            <div class="error-img-input" id="imgDup1" ></div>
                                        </div>
                                        <!-- Container to display the selected image -->
                                        <div class="img-display-container">
                                            <img id="img-preview" class="uploaded-img" src="#" alt="Image Preview">
                                        </div>
                                    </div>
                                    <div class="product_detail_input">
                                        <div class="img_input_content">
                                            <label for="image1">Ảnh 2:</label><br>
                                            <input type="file" id="image1" name="image[]" class="img-input" accept="image/*" onchange="previewImage(this, 'img-preview-1');">
                                            <div class="error-img-input" id="imgDup2" ></div>
                                        </div>
                                        <div class="img-display-container">
                                            <img id="img-preview-1" class="uploaded-img" src="#" alt="Image Preview">
                                        </div>
                                    </div>
                                    <div class="product_detail_input">
                                        <div class="img_input_content">
                                            <label for="image2">Ảnh 3:</label><br>
                                            <input type="file" id="image2" name="image[]" class="img-input" accept="image/*" onchange="previewImage(this, 'img-preview-2');">
                                            <div class="error-img-input" id="imgDup3" ></div>
                                        </div>
                                        <!-- Container to display the selected image -->
                                        <div class="img-display-container">
                                            <img id="img-preview-2" class="uploaded-img" src="#" alt="Image Preview">
                                        </div>
                                    </div>
                                    <div class="product_detail_input">
                                        <div class="img_input_content">
                                            <label for="image3">Ảnh 4:</label><br>
                                            <input type="file" id="image3" name="image[]" class="img-input" accept="image/*" onchange="previewImage(this, 'img-preview-3');">
                                            <div class="error-img-input" id="imgDup4" ></div>
                                        </div>
                                        <!-- Container to display the selected image -->
                                        <div class="img-display-container">
                                            <img id="img-preview-3" class="uploaded-img" src="#" alt="Image Preview">
                                        </div>
                                    </div>
                                </div>
                            </div>
            
                            <div class="btn-section">
                                <button class="btn" type="reset" onclick="clearImagePreviews();">Xoá</button>
                                <button class="btn" type="submit" onclick="checkForDuplicateNames()">Đăng</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($isRestaurantLoggedIn): ?>
            <button class="openBtn" id="ordersInfoBtn" >Đơn hàng</button>
            <div class="delete_acc_container" id="order_info_container">
            <div class="toast" id="toastMessage"></div>
                <div class="delete_acc_form">
                <h2>Đơn hàng</h2>
                <div class="res_orders">
                    <?php echo $orderProducts; ?>
                </div>
                        <button class="btn" id="closeOrderBtn" ><i class="fa-solid fa-xmark"></i></button>
            </div>
        </div>
        <?php endif; ?>
        <?php if ($isRestaurantLoggedIn): ?>
            <button class="openBtn" id="changeInfoBtn">Thay đổi Thông tin</button>
            <div class="change_info_container" id="change_info_container">
                <div class="change_info_form">
                    <h2>Thay đổi thông tin nhà hàng</h2>
                    <button class="close_info_form" id="closeInfoProduct"><i class="fa-solid fa-xmark"></i></button>
                    <form action="update_res_info.php" method="post" id="changeInfoForm" class="product_form" enctype="multipart/form-data" autocomplete="off" >
                        <div class="change_detail_input">
                            <label for="res_name">Tên nhà hàng:</label>
                            <input type="text" id="res_name" name="name" value="<?php echo $restaurant_name; ?>">
                            <div class="res-input-error" id="resNameError"></div>
                        </div>
                        <div class="change_detail_input">
                            <label for="res_email">Email:</label>
                            <input type="email" id="res_email" name="email" value="<?php echo $email; ?>" >
                            <div class="res-input-error" id="resEmailError" ></div>
                        </div>
                        <div class="change_detail_input">
                            <label for="res_phone">SĐT:</label>
                            <input type="tel" id="res_phone" name="phone" maxlength="10" value="<?php echo $phone; ?>">
                            <div class="res-input-error" id="resPhoneError"></div>
                        </div>
                        <div class="change_detail_input">
                            <label for="res_description">Giới thiệu</label>
                            <textarea id="res_description" name="description" cols="42" rows="3" ><?php echo $description; ?></textarea>
                            <div class="res-input-error" id="resDesError"></div>
                        </div>
                        <div class="change_detail_input">
                            <label for="image">Ảnh đại diện</label>
                            <input type="file" id="image" name="image" value="">
                        </div>
                        <div class="btn-section">
                            <button class="btn" type="reset" >Xoá</button>
                            <button class="btn" type="submit">Thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($isRestaurantLoggedIn): ?>
            <button class="openBtn" id="openLogOutBtn" >Đăng xuất</button>
            <div class="logout_container" id="logout_container">
                <div class="logout_content">
                    <i class="fa-solid fa-circle-question"></i>
                        <h2>Bạn muốn thoát ra ?</h2>
                        <div class="ASK">
                            <a href="log_out.php" class="btn">Đúng</a>
                            <button class="btn" id="closeLogOutBtn">Không</button>
                        </div>
                </div>
            </div>
        <?php endif; ?>
    </section>
    <section class="res_product">
        <div class="res_product_container">
                <?php if ($productResResult->num_rows === 0): ?>
                    <p class="zero_product" >Chưa có sản phẩm nào.</p>
                <?php else: ?>
                    <?php while ($row = $productResResult->fetch_assoc()): ?>
                        <?php
                            $product_id = $row['product_id'];
                            $name = $row['name'];
                            $description = $row['description'];
                            $price = $row['price'];
                            $quantity = $row['quantity'];
                            $image = $row['image_1'];
                        ?>
                        <div class="card">
                            <p class="card-id"> ID: <?php echo $product_id; ?></p>
                            <div class="fpic-container">
                                <img src="<?php echo $image; ?>" class="card-img-top" alt="Product Image">
                            </div>
                            <div class="card-body">
                                <h3 class="card-title"><?php echo $name; ?></h3>
                                <div class="price">
                                    <p>Giá:</p> <strong><?php echo $price; ?> VNĐ</strong>
                                </div>
                                <div class="card-link">
                                    <?php if ($isRestaurantLoggedIn): ?>
                                        <a href="Single-product-detail.php?product_id=<?php echo $product_id; ?>" class="openBtn">Chi Tiết  Sản Phẩm <i class="fa-solid fa-circle-question"></i></a>
                                        <button class="openBtn editProductBtn" data-productid="<?php echo $product_id; ?>">Sửa sản phẩm<i class="fa-solid fa-edit"></i></button>
                                        <div class="edit-product-container" id="editProContainer_<?php echo $product_id; ?>">
                                        <div class="edit-product-content">
                                            <button class="close_edit_form" data-productid="<?php echo $product_id; ?>"><i class="fa-solid fa-xmark"></i></button>
                                            <h2>Sửa sản phẩm</h2>
                                            <form action="update_product_detail.php" method="post" id="editProForm_<?php echo $product_id; ?>" class="edit_pro_form">
                                                <div class="edit-input-detai">
                                                    <input type="hidden" name="product_id" id="editProductId" readonly value="<?php echo $product_id; ?>">
                                                </div>
                                                <div class="edit-input-detail">
                                                    <label for="editName">Tên sản phẩm:</label>
                                                    <input type="text" id="editName" name="edit-name" value="<?php echo $name; ?>">
                                                    <div class="fail-valid" id="failEditName"></div>
                                                </div>
                                                <div class="edit-input-detail">
                                                    <label for="editDescription">Mô tả:</label>
                                                    <textarea id="editDescription" name="edit-description"  ><?php echo $description; ?></textarea>
                                                    <div class="fail-valid" id="failEditDes" ></div>
                                                </div>
                                                <div class="edit-input-detail">
                                                    <label for="editPrice">Giá</label>
                                                    <input type="number" id="editPrice" name="edit-price" value="<?php echo $price; ?>">
                                                    <div class="fail-valid" id="failEditPrice" ></div>
                                                </div>
                                                <div class="edit-input-detail">
                                                    <label for="editQuantity">Số lượng</label>
                                                    <input type="number" id="editQuantity" name="edit-quantity" value="<?php echo $quantity; ?>">
                                                    <div class="fail-valid" id="failEditQuantity" ></div>
                                                </div>
                                                <input type="submit" value="Lưu thay đổi" class="btn">
                                            </form>	
                                        </div>
                                        </div>
                                        <button class="openBtn delProductBtn" data-productid="<?php echo $product_id; ?>">Xóa sản phẩm<i class="fa-solid fa-trash"></i></button>
                                        <div class="del_product_container" id="del_product_container_<?php echo $product_id; ?>">
                                            <div class="del_product_content">
                                                <i class="fa-solid fa-circle-question"></i>
                                                <h2>Bạn có muốn xóa sản phẩm ?</h2>
                                                <div class="ASK">
                                                    <form action="delete_product_process.php" method="post">
                                                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                                        <button type="submit" class="btn">Có</button>
                                                    </form>
                                                    <button class="btn closeDelProBtn" data-productid="<?php echo $product_id; ?>">Không</button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <a href="Single-product-detail.php?product_id=<?php echo $product_id; ?>" class="openBtn">Chi Tiết<i class="fa-solid fa-circle-question"></i></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
    </section>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" ></script>
<script src="js/res_home.js"></script>
</body>
</html>

<?php
$conn->close();
?>

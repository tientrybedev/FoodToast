<?php 
session_start();
include("connect.php");
$isRestaurantLoggedIn = isset($_SESSION['res_loggedin']) && $_SESSION['res_loggedin'] === true;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']; 
}
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $queryStarRatingSql = "SELECT AVG(rating) AS avg_rating FROM ratings WHERE product_id = ?";
    $stmtdDisplayRating = mysqli_prepare($conn, $queryStarRatingSql);
    if ($stmtdDisplayRating) {
        mysqli_stmt_bind_param($stmtdDisplayRating, 's', $product_id);
        mysqli_stmt_execute($stmtdDisplayRating);
        mysqli_stmt_bind_result($stmtdDisplayRating, $avgRating);
        mysqli_stmt_fetch($stmtdDisplayRating);
        $roundedAvgRating = round($avgRating);
        $userRatingDisplay = '';
        for ($i = 1; $i <= 5; $i++) {
            $starDisplayClass = ($i <= $roundedAvgRating) ? 'star-filled' : 'star-empty';
            $userRatingDisplay .= '<span><i class="fa fa-star ' . $starDisplayClass . '"></i></span>';
        }
        mysqli_stmt_close($stmtdDisplayRating);
    } else {
        echo "Error preparing the statement.";
    }
    // Query to fetch product details
    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $product_id); 
    $stmt->execute();
    $result = $stmt->get_result();

    $alreadyFavorSql1 = "SELECT * FROM favorite_products WHERE user_id = ? AND product_id = ?";
    $stmtfavor = $conn->prepare($alreadyFavorSql1);
    $stmtfavor->bind_param("is", $user_id, $product_id); 
    $stmtfavor->execute();
    $favorResult1 = $stmtfavor->get_result();
    $isFavorited1 = $favorResult1->num_rows > 0;

    
    if ($result->num_rows ===1) {
        $row = $result->fetch_assoc();
        $price = $row['price'];
        $product_name = $row['name'];
        $product_description = $row['description'];
        $product_quantity = $row['quantity'];
        $price = $row['price'];
        $breakfast = $row['breakfast'];
        $lunch = $row['lunch'];
        $dinner = $row['dinner'];
        $dessert = $row['dessert'];
        $snack = $row['snacks'];
        $drink = $row['drinks'];
        $rices = $row['rices'];
        $soup = $row['soup'];
        $diet = $row['diet'];
        $img1 = $row['image_1'];
        $img2 = $row['image_2'];
        $img3 = $row['image_3'];
        $img4 = $row['image_4'];
        //CATEGORY
        $categories = [];
        if ($breakfast) {
            $categories[] = 'Bữa sáng';
        }
        if ($lunch) {
            $categories[] = 'Bữa trưa';
        }
        if ($dinner) {
            $categories[] = 'Bữa tối';
        }
        if ($dessert) {
            $categories[] = 'Tráng miệng';
        }
        if ($snack) {
            $categories[] = 'Snacks';
        }
        if ($drink) {
            $categories[] = 'Thức uống';
        }
        if ($rices) {
            $categories[] = 'Món cơm';
        }
        if ($diet) {
            $categories[] = 'Ăn kiêng';
        }
        if ($soup) {
            $categories[] = 'Món nước';
        }                                            
    } else {
        echo "Product not found.";
    }
    $stmt->close();
}
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $sql_get_restaurant_id = "SELECT restaurant_id FROM products WHERE product_id = ?";
    $stmt_get_restaurant_id = $conn->prepare($sql_get_restaurant_id);
    $stmt_get_restaurant_id->bind_param("s", $product_id);
    $stmt_get_restaurant_id->execute();
    $restaurant_id_result = $stmt_get_restaurant_id->get_result();
    $relateResPro ="";
    if ($restaurant_id_result->num_rows === 1) {
        $restaurant_row = $restaurant_id_result->fetch_assoc();
        $restaurant_id = $restaurant_row['restaurant_id'];
    }
    
    $sql_related_products = "SELECT * FROM products WHERE restaurant_id = ? AND product_id != ?  LIMIT 4";
    $stmt_related_products = $conn->prepare($sql_related_products);
    $stmt_related_products->bind_param("is", $restaurant_id, $product_id);
    $stmt_related_products->execute();
    $related_products_result = $stmt_related_products->get_result();

    if($related_products_result->num_rows > 0){
    while ($related_row = $related_products_result->fetch_assoc()) {
        $related_product_ID = $related_row['product_id'];
        $related_product_name = $related_row['name'];
        $related_product_price = $related_row['price'];
        $related_product_img = $related_row['image_1'];
        $queryRelateStarRatingSql = "SELECT AVG(rating) AS avg_rating FROM ratings WHERE product_id = ?";
        
        $alreadyFavorSql = "SELECT * FROM favorite_products WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($alreadyFavorSql);
        $stmt->bind_param("is", $user_id, $related_product_ID);
        $stmt->execute();
        $result = $stmt->get_result();
        $isFavorited = $result->num_rows > 0;
        $stmt->close();
        $relateResPro .='<div class="card">';
        if(isset($user_id)){
        $relateResPro .= '<div class="like" data-user-id="' . $user_id . '" data-product-id="' . $related_product_ID . '"><i class="fa-solid fa-heart" title="Yêu thích" ' . ($isFavorited ? 'style="text-shadow: 0 0 2px; color: var(--heart-color); transform: scale(1.2);"' : '') . '></i></div>';
        }else{
        $relateResPro .= '<div class="like not-logged-in" data-user-id="' . $user_id . '" data-product-id="' . $related_product_ID . '"><i class="fa-solid fa-heart" title="Yêu thích" ' . ($isFavorited ? 'style="text-shadow: 0 0 2px; color: var(--heart-color); transform: scale(1.2);"' : '') . '></i></div>';
        }
        $stmtdDisplayRelateRating = mysqli_prepare($conn, $queryRelateStarRatingSql);
        if ($stmtdDisplayRelateRating) {
            mysqli_stmt_bind_param($stmtdDisplayRelateRating, 's', $related_product_ID);
            mysqli_stmt_execute($stmtdDisplayRelateRating);
            mysqli_stmt_bind_result($stmtdDisplayRelateRating, $avgRating);
            mysqli_stmt_fetch($stmtdDisplayRelateRating);
            $roundedAvgRating1 = round($avgRating);
            $userRatingRelateDisplay = '';
            for ($i = 1; $i <= 5; $i++) {
                $starDisplayRelateClass = ($i <= $roundedAvgRating1) ? 'star-filled' : 'star-empty';
                $userRatingRelateDisplay .= '<span><i class="fa fa-star ' . $starDisplayRelateClass . '"></i></span>';
            }
            mysqli_stmt_close($stmtdDisplayRelateRating);
        } else {
            echo "Error preparing the statement.";
        }
        $relateResPro .='<div class="fpic-container">';
        $relateResPro .='<img src="' . $related_product_img  . '" class="card-img-top" alt="' . $related_product_name . '">';
        $relateResPro .= '</div>';
        $relateResPro .='<div class="card-body">';
        $relateResPro .='<h3 class="card-title">' . $related_product_name . '</h3>';

        $relateResPro .= '<div class="card-rating">' . $userRatingRelateDisplay . '</div>' ;
        $relateResPro .='<div class="price"> <p>Giá:<p> <strong>' . $related_product_price . '</strong></div>';
        $relateResPro .='<div class="card-link">';
        $relateResPro .= '<a href="Single-product-detail.php?product_id=';
        $relateResPro .= $related_product_ID;
        $relateResPro .= '" class="btn">Chi tiết sản phẩm<i class="fa-solid fa-circle-question"></i></a>';
        if(!$isRestaurantLoggedIn){
        if (isset($user_id)){
            $relateResPro .='<button class="addToCart btn" data-product-id="' . $related_product_ID . '">Thêm vào giỏ hàng <i class="fa-solid fa-cart-shopping"></i></button>';
        }else{
            $relateResPro .='<button class="addToCart btn" onclick="showLoginAnnouncement(event) ">Thêm vào giỏ hàng <i class="fa-solid fa-cart-shopping"></i></button>';
        }
    }
        $relateResPro .='</div>';
        $relateResPro .='</div>';
        $relateResPro .='</div>';
    }
    }else{
        $relateResPro .= '<div class="no-related-products">';
        $relateResPro .= '<p>Không có sản phẩm liên quan</p>';
        $relateResPro .= '</div>';
    }
    $stmt_related_products->close();
}

    if (isset($_GET['product_id'])) {
        $product_id = $_GET['product_id'];
        $ratingSQL = "SELECT * FROM ratings WHERE product_id = ?";
        $stmtRating = $conn->prepare($ratingSQL);
        $stmtRating->bind_param("s", $product_id);
        $stmtRating->execute();
        $ratingResult = $stmtRating->get_result();
        
        $userCommentsHtml = '';
        if ($ratingResult->num_rows > 0) {
            while ($row = $ratingResult->fetch_assoc()) {
                $comment_user_id = $row['user_id'];
                $rating = $row['rating'];
                $comment = $row['comment'];

                $userSQL = "SELECT * FROM users WHERE user_id = ?";
                $stmtUser = $conn->prepare($userSQL);
                $stmtUser->bind_param("s", $comment_user_id);
                $stmtUser->execute();
                $user_comment_result = $stmtUser->get_result();

                $usersCountSQL = "SELECT COUNT(*) AS user_count FROM ratings WHERE product_id = ?";
                $stmtUsersCount = $conn->prepare($usersCountSQL);
                $stmtUsersCount->bind_param("s", $product_id);
                $stmtUsersCount->execute();
                $userCountResult = $stmtUsersCount->get_result();
                $userCountRow = $userCountResult->fetch_assoc();
                if ($user_comment_result->num_rows === 1) {
                    $current_user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
                    $userRow = $user_comment_result->fetch_assoc();
                    $user_comment_avartar = $userRow['profile_image'];
                    $user_comment_id = $userRow['user_id']; 
                    if (!is_null($current_user_id) && $user_comment_id === $current_user_id) {
                        $user_comment_name = 'Bạn';
                    } else {
                        $user_comment_name = $userRow['username'];
                    }
                    
                    $userCommentsHtml .= '<div class="user-comment">';
                    $userCommentsHtml .= '<div class="user-avatar-container">';
                    $userCommentsHtml .= '<img src="' . $user_comment_avartar . '" alt="' . $user_comment_name. '" />';
                    
                    $userCommentsHtml .= '</div>';
                    $userCommentsHtml .= '<div class=user_comment_content>';
                    $userCommentsHtml .= '<span>' .$user_comment_name . '</span> <br>'; 
                    for ($i = 1; $i <= 5; $i++) {
                        $starClass = ($i <= $rating) ? 'star-filled' : 'star-empty';
                        $starText ='';
                        switch ($rating) {
                            case 1:
                                $starText = 'Rất thất vọng';
                                $starTextClass = 'txt-rating';
                                break;
                            case 2:
                                $starText = 'Không vừa ý';
                                $starTextClass = 'txt-rating';
                                break;
                            case 3:
                                $starText = 'Khá';
                                $starTextClass = 'txt-rating';
                                break;
                            case 4:
                                $starText = 'Tốt';
                                $starTextClass = 'txt-rating';
                                break;
                            case 5:
                                $starText = 'Rất tuyệt vời';
                                $starTextClass = 'txt-rating';
                                break;
                            default:
                                $starText = '';
                                break;
                        }
                        $userCommentsHtml .= '<span><i class="fa fa-star ' . $starClass . '"></i></span>';
                    }
                    if ($starText !== '') {
                        $userCommentsHtml .= '<span class="' . $starTextClass . '">' . $starText . '</span>';
                    }
                    $userCommentsHtml .= '</div>';                   
                    $userCommentsHtml .= '</div>';
                    $userCommentsHtml .= '<span style= "margin-left: 22px; font-size:1.05rem" >' . $comment .'<span><br>';
                    $userCommentsHtml .= '<br>';
                    $userCommentsHtml .= '<hr>';
                }
                $stmtUser->close();
            }
        } else {
            $userCommentsHtml .= '<div class="user-comment">';
            $userCommentsHtml .= '<h4 style ="font-weight: 500; letter-spacing=1px; font-style: italic; color: gray;">Chưa có bình luận nào</h4>';
            $userCommentsHtml .= '</div>';
        }
        $stmtRating->close();
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/single-product-detail.css">
    <link rel="shortcut icon" href="Home-img/food-logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>Chi tiết sản phẩm</title>
</head>
<body>
<div class ="cart-announce" id="cartAnnounce"></div>
    <h1 class="start-title">Chi tiết sản phẩm</h1>
    <div class = "card-wrapper">
        <div class = "card">
            <?php
            if(!$isRestaurantLoggedIn){
                echo '<div class="return-menu">';
                echo '<a href="javascript:history.go(-1)"><i class="fa-solid fa-rotate-left"></i>Quay về</a>';
                echo'<a href="index.php" ><i class="fa-solid fa-house"></i>Trang chủ</a>';
                echo'<a href="produces-page.php" ><i class="fa-solid fa-clipboard-list"></i>Menu</a>';
                if(isset($user_id)){
                echo'<a href="cart.php" ><i class="fa-solid fa-bag-shopping"></i><span class="badge" id="cartBadge"></span>Giỏ hàng</a>';
                }else{
                    echo'<a href="cart.php" onclick="showLoginAnnouncement(event)" ><i class="fa-solid fa-bag-shopping"></i><span class="badge" id="cartBadge"></span>Giỏ hàng</a>';
                }
                echo'</div>';
            }else{
                echo '<div class="return-menu">';
                echo'<a href="res_home.php" ><i class="fa-solid fa-store"></i></i>Nhà Hàng</a>';
                echo'</div>';
            }
            ?>
            <div class = "product-imgs">
                <div class = "img-display">
                    <div class = "img-showcase">
                            <img src = "<?php echo $img1; ?>" alt = "smoothy">
                            <img src = "<?php echo $img2;  ?>" alt = "smoothy">
                            <img src = "<?php echo $img3;  ?>" alt = "smoothy">
                            <img src = "<?php echo $img4;  ?>" alt = "smoothy">
                        </div>
                </div>
                    <div class = "img-select">
                        <div class = "img-item">
                            <a href = "#" data-id = "1" onclick="">
                                <img src = "<?php echo $img1; ?>" alt = "1" class="active" onclick=" changeActive(this)">
                            </a>
                        </div>
                        <div class = "img-item">
                            <a href = "#" data-id = "2">
                                <img src = "<?php echo $img2;?>" alt = "2"
                                onclick=" changeActive(this)">
                            </a>
                        </div>
                        <div class = "img-item">
                            <a href = "#" data-id = "3">
                                <img src = "<?php echo $img3;?>" alt = "3"
                                onclick=" changeActive(this)">
                            </a>
                        </div>
                        <div class = "img-item">
                            <a href = "#" data-id = "4">
                                <img src = "<?php echo $img4; ?>" alt = "4"
                                onclick=" changeActive(this)">
                            </a>
                        </div>
                    </div>
            </div>
            <div class = "product-content">
                <h2 class = "product-title"><?php echo $product_name; ?></h2>
                <div class = "product-detail">
                    <h2>Mô tả sản phẩm: </h2>
                    <p><?php echo $product_description ?></p>
                    <ul>
                        <li>Còn: <span><?php echo $product_quantity; ?> đơn vị</span></li> 
                                <?php
                                            if (!empty($categories)) {
                                                    echo '<li>Phân loại: <span>' . implode(', ', $categories) . '</span></li>';
                                                }
                                    ?>
                        <li>Khu vực giao hàng:  <span> 12 tỉnh thành</span></li>
                        <li>Phí giao hàng: <span>Theo khu vực</span></li>
                    </ul>
                </div>
                <div class="line"></div>
                <div class="product-rating">
        <p>Đánh giá: </p>
        <?php 
        echo $userRatingDisplay;
        ?>
        <span>
            <?php
                if (isset($userCountRow['user_count'])) {
                    $userCount = $userCountRow['user_count'];
                    echo '('. $userCount . ')';
                }else{
                    echo "0";
                }
            ?>
        </span>
    </div>

                <div class="buy">
                    <div class = "product-price">
                        <p class = "new-price">giá: <span><?php echo $price ?> VNĐ</span></p>
                    </div>
                    <?php
                        if (!$isRestaurantLoggedIn) {
                            echo '<div class="purchase-info">';
                            echo '<div class="amount">';
                            echo '<label for="quantities">Số lượng</label>';
                            echo '<input type="number" min="1" max="' . $product_quantity . '" value="1" id="quantities">';
                            echo '</div>';
                            echo '<div class="buy-btn">';
                            if(isset($user_id)){
                                echo '<button class="addToCart btn" data-product-id="' . $product_id . '">Thêm vào giỏ hàng <i class="fa-solid fa-cart-shopping"></i></button>';
                                echo '<div class="addToFavor btn" data-user-id="' . $user_id . '" data-product-id="' . $product_id . '"><i class="fa-solid fa-heart" title="Yêu thích" ' . ($isFavorited1 ? 'style="text-shadow: 0 0 2px; color: var(--heart-color); transform: scale(1.2);"' : '') . '></i></div>';
                            }else{
                                echo '<button class="addToCart btn" onclick="showLoginAnnouncement(event)">Thêm vào giỏ hàng <i class="fa-solid fa-cart-shopping"></i></button>';
                                echo '<div class="addToFavor btn not-logged-in" data-user-id="' . $user_id . '" data-product-id="' . $product_id . '"><i class="fa-solid fa-heart" title="Yêu thích" ' . ($isFavorited1 ? 'style="text-shadow: 0 0 2px; color: var(--heart-color); transform: scale(1.2);"' : '') . '></i></div>';
                            }
                            echo '</div>';
                            echo '</div>';
                        }
                    ?>
                </div>    
            </div>
            <div class = "social-links">
                <p>Share: </p>
                <div class="social-box">
                    <span><i class="fa-brands fa-facebook " ></i></span>
                    <span><i class="fa-brands fa-youtube " ></i></span>
                    <span><i class="fa-brands fa-twitter " ></i></span>
                    <span><i class="fa-brands fa-instagram "></i></span>
                    <span><i class="fa-brands fa-tiktok "></i></span>
                </div>
            </div>
        </div>
    </div>
    <h1 class="rating-title" >Đánh giá</h1>
    <section class="rating">
        <div class="rating-container">
            <?php 
            if (!$isRestaurantLoggedIn) {
                echo '<form method="post" action="submit_rating.php" class="stars_rating_form" id="starsRatingForm"> ';
                echo '<input type="text" name="product_id" value="' . $product_id . '" readonly hidden>';
                if (isset($user_id)) {
                    echo '<input type="text" name="user_id" value="' . $user_id . '" hidden>';
                    echo '<div class="star-widget">';
                    echo'<span class="rating-ele"></span>';
                    echo '<div class="rating-stars">';
                    echo '<input type="radio" name="rate" id="rate-5" value="5">';
                    echo '<label for="rate-5" class="fas fa-star"></label>';
                    echo '<input type="radio" name="rate" id="rate-4" value="4">';
                    echo '<label for="rate-4" class="fas fa-star"></label>';
                    echo '<input type="radio" name="rate" id="rate-3" value="3">';
                    echo '<label for="rate-3" class="fas fa-star"></label>';
                    echo '<input type="radio" name="rate" id="rate-2" value="2">';
                    echo '<label for="rate-2" class="fas fa-star"></label>';
                    echo '<input type="radio" name="rate" id="rate-1" value="1">';
                    echo '<label for="rate-1" class="fas fa-star"></label>';
                    echo '<p>Đánh giá của bạn về sản phẩm:</p>';
                    echo '</div>';
                    echo '<div class="comment">';
                    echo '<textarea rows="5" name="comment" placeholder="Cảm nghĩ về sản phẩm"></textarea>';
                    echo '</div>';
                    echo '<button type="submit" class="btn">Đăng</button>';
                    echo'<div class ="toast" id="toastMessage"></div>';
                    echo '</form>';
                } else {
                    echo '<input type="text" name="user_id" value="Chưa đăng nhập" readonly hidden>';
                    echo '<div class="star-widget">';
                    echo'<span class="rating-ele"></span>';
                    echo '<div class="rating-stars" style="pointer-events: none;">';
                    echo '</div>';
                    echo '<div class="comment">';
                    echo'<p style="font-size: 1.5rem; font-weight: 500px; opacity: .7;font-style:italic;">Đăng nhập để đánh giá</p>';
                    echo '<textarea rows="5" name="comment" placeholder="Đăng nhập để bình luận" style="pointer-events:none;" ></textarea>';
                    echo '</div>';
                    echo '</form>';
                }
            }
            ?>
        </div>
        <div class="results-of-rating">
                <?php
                if (isset($userCountRow['user_count'])) {
                    $userCount = $userCountRow['user_count'];
                    echo '<p style="font-size:1.1rem; float:right;">' . $userCount . ' bình luận</p>';
                }else{
                    $userCount = ''; 
                }
                echo $userCommentsHtml;
                ?>
        </div>
    </section>

    <h1 class="relate-title">sản phẩm liên quan</h1>
    <section class="relate-products-section">
        <div class="relate-products-container">
            <?php echo $relateResPro; ?>
        </div>
    </section>
    <footer>
        <div class="footer-container" id="footer">
            <div class="left box">
                <h2>Thông tin</h2>
                <p><strong>FoodToast</strong> cung cấp cho bạn trải nghiệm mua sắm đồ ăn trực tuyến thú vị. Đừng chần chừ, truy cập FoodToast để tiết kiệm thời gian và chi phí ngay nào.</p>
            </div>
            <div class="middle box">
                <h2>Liên lạc</h2>
                    <ul>
                        <li><a href="#"><i class="fa-solid fa-phone fa-lg" style="color: #1d62d7;"></i>0935.68.68.68.</a> </li>
                        <li><a href="#"><i class="fa-solid fa-envelope fa-lg" style="color: #d0c335;"></i>Tien@gmail.com.</a></li>
                        <li><a href="#"><i class="fa-solid fa-location-dot fa-lg" style="color: #ff3300;"></i>Quận 10, Tp.Hồ Chí Minh.</a></li>    
                    </ul>    
            </div>
            <div class="right box">
                <h2>Dịch vụ</h2>
                    <ul>
                        <li><i class="fa-solid fa-truck-fast fa-lg" style="color: #208bee;"></i>Giao hàng</li>
                        <li><i class="fa-regular fa-circle-check fa-lg" style="color: #2d9f3a;"></i> Giá tốt nhất</li>
                        <li><i class="fa-solid fa-comments fa-lg" style="color: #2f64c1;"></i> Hỗ trợ 24/7</li>
                        <li><i class="fa-solid fa-money-check-dollar fa-lg" style="color: #0e9a04;"></i> Thanh toán điện tử </li>
                    </ul> 
            </div>       
        </div>

        <hr style="margin: 0 auto; width: 50%; border: 1px groove gray;">
        <div class="social-box">
            <h2>Theo dõi ngay</h2>
            <span><i class="fa-brands fa-facebook " ></i></span>
            <span><i class="fa-brands fa-youtube " ></i></span>
            <span><i class="fa-brands fa-twitter " ></i></span>
            <span><i class="fa-brands fa-instagram "></i></span>
            <span><i class="fa-brands fa-tiktok "></i></span>
        </div>
    </footer> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" ></script>
    <script src="js/single-product-detail.js"></script>
</body>
<?php
    $conn->close();
    ?>
</html>

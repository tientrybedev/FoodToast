<?php
include("connect.php");
session_start();
if (isset($_SESSION['res_loggedin']) && $_SESSION['res_loggedin'] === true) {
    unset($_SESSION['res_loggedin']);
}
$isUserLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$RestaurantsSql = "SELECT restaurant_id, brand_image FROM restaurants ";
$res_result = $conn->query($RestaurantsSql);
$countRestaurantSql = "SELECT COUNT(*) as total FROM restaurants";
$countResult = $conn->query($countRestaurantSql);
$countRow = $countResult->fetch_assoc();
$totalItems = $countRow['total'];
$itemsPerPage = 12;
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

$startIndex = ($currentPage - 1) * $itemsPerPage;
$restaurantSql = "SELECT restaurant_id, brand_image FROM restaurants LIMIT $startIndex, $itemsPerPage";
$totalPages = ceil($totalItems / $itemsPerPage);
$res_result = $conn->query($restaurantSql);

if ($isUserLoggedIn) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT user_id, username, profile_image FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $user_data = $result->fetch_assoc();
        $username = $user_data['username'];
        $profileImage = $user_data['profile_image'];
        $userNameIn = '<a href="user_profile.php" class="user-detail-btn">' . $username . '</a>';
        $userHis = '<a href ="favor.php" class="favorite" >Yêu thích</a>';
        $userBtnOut = '<button class="log-out-btn"><i class="fa-solid fa-arrow-right-from-bracket fa-rotate-180"></i>Đăng xuất</button>';
    }
    $stmt->close();
} else{
    $userNameIn = '<p>Bạn chưa đăng nhập</p>';
    $userHis = '';
    $userBtnOut = '<a href="login.php" class="log-in-btn">Đăng nhập <i class="fa-solid fa-arrow-right-to-bracket"></i></a>';
    $profileImage = 'Home-img/non-lgin.png'; 
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/restaurant_page.css">
    <link rel="shortcut icon" href="Home-img/food-logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>Nhà hàng</title>
</head>
<body>
<header>
<div class="loader-container">
        <div class="waiting-img">
            <div class="waiting-card">
                <img src="Home-img/wm7.png" alt="">
            </div>
            <div class="waiting-card">
                <img src="Home-img/wm8.png" alt="">
            </div>
            <div class="waiting-card">
                <img src="Home-img/wm9.png" alt="">
            </div>
        </div>
        <div class="loader">
            <p class="loading">LOADING
                <span>.</span>
                <span>.</span>
                <span>.</span>
            </p>
        </div>
    </div>
        <nav class="nav-bar">
            <div class="logo">
                <a href="#"><img src="Home-img/FT-logo.png" alt=""></a>
            </div>
            <div class="links">
                    <ul>
                        <li><a href="index.php" >Trang chủ</a></li>
                        <li><a href="produces-page.php">Menu</a></li>
                        <li><a href="#" class="home-active">Nhà hàng</a></li>
                    </ul>
            </div>
            <div class="function">
                    <div class="search">
                        <form action="results_of_search.php" method="get">
                            <input type="text" id="searchInput" name="search-query"  placeholder="Tìm kiếm sản phẩm" autocomplete="off"  onkeyup="liveSearch(this.value);">
                            <div class="category-search"><a href="advance_search.php"><i class="fa-solid fa-sliders"></a></i></div>
                            <button class="sear-btn" type="submit"><i class="fa-solid fa-magnifying-glass fa-lg"></i></button>
                            <div id='searchResults' class="searchResults"></div>
                        </form>
                    </div>

                    <div class="cart">
                    <?php if($isUserLoggedIn): ?>
                            <a href="cart.php"><i class="fa-solid fa-cart-shopping fa-lg"></i></a>
                            <span id="cartBadge" class="badge"></span> 
                        <?php else: ?>
                            <a href="cart.php" onclick="showLoginAnnouncement(event)"><i class="fa-solid fa-cart-shopping fa-lg"></i></a>
                            <span id="cartBadge" class="badge"></span> 
                        <?php endif; ?>
                    </div>
                    <div class="btn-login">
                        <div class="btn-login-container">
                            <i class="fa-solid fa-user fa-lg" title="User"></i>
                            <div class="btn-login-content">
                                    <div class="avatar-img">
                                        <img id="profileImage" src="<?php echo $profileImage; ?>" alt="">
                                    </div>
                                <?php echo $userNameIn; ?>
                                <?php echo $userHis; ?>
                                <?php echo $userBtnOut; ?>
                            </div>
                        </div>
                    </div>
                    <div class="bar">
                        <span><i class="fa-solid fa-bars fa-lg"></i></span>
                    </div>
            </div>
        </nav>
    </header>
    <!-- <nav>
        <div class="return_links">
            <a href="javascript:history.go(-1)"><i class="fa-solid fa-rotate-left"></i> Quay về </a>
            <a href="index.php"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <a href="produces-page.php"><i class="fa-solid fa-clipboard-list"></i> Menus</a>
        </div>
        <div class="search">
            <form action="results_of_search.php" method="get" class="product-search-form" id="productSearchForm">
            <input type="text" id="searchInput" name="search-query"  placeholder="Tìm kiếm sản phẩm" autocomplete="off"  onkeyup="liveSearch(this.value);">
            <div class="category-search"><a class="advance_search" href="advance_search.php"><i class="fa-solid fa-sliders"></a></i></div>
            <button class="sear-btn" type="submit"><i class="fa-solid fa-magnifying-glass fa-lg" title="Tìm kiếm theo phân loại" ></i></button>
            <div id='searchResults' class="searchResults"></div>
            </form>
        </div>
    </nav> -->
<div class="restaurant-title"><h1>Nhà hàng</h1></div>
<p class="total-res" >Hiện đang có <?php echo $totalItems  ?> nhà hàng đăng ký trên FoodToast</p>
<div class="brands">
<div class="brands-container">
    <?php
    while ($row = $res_result->fetch_assoc()):
        $brandImg = $row['brand_image'];
        $restaurantId = $row['restaurant_id'];
        echo '<a href="res_home.php?restaurant_id=' . $restaurantId . '" class="img-res-link" >';
        echo '<div class="brands-img-container">';
        echo '<img src="' . $brandImg . '" alt="" >';
        echo '</div>';
        echo '</a>';
    endwhile;

    ?>

    </div>
        <div class="pagination">
            <?php if ($currentPage > 1): ?>
                <a href="?page=<?php echo ($currentPage - 1); ?>" class="prevBtn"><i class="fa-solid fa-angle-left"></i></a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" <?php echo ($i === $currentPage) ? 'class="active-page"' : ''; ?>><?php echo $i; ?></a>
            <?php endfor; ?>
            <?php if ($currentPage < $totalPages): ?>
                <a href="?page=<?php echo ($currentPage + 1); ?>" class="nextBtn"><i class="fa-solid fa-angle-right"></i></a>
            <?php endif; ?>
        </div>
</div>
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
<script src="js/restaurant_page.js"></script>
</body>
</html>


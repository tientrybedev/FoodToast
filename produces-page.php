<?php
session_start();
include("connect.php");
if (isset($_SESSION['res_loggedin']) && $_SESSION['res_loggedin'] === true) {
    unset($_SESSION['res_loggedin']);
}
$isUserLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
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
        $userHis = '<a href= "favor.php" class="favorite" >Yêu thích</a></p>';
        $userBtnOut = '<button class="log-out-btn"><i class="fa-solid fa-arrow-right-from-bracket fa-rotate-180"></i>Đăng xuất</button>';
    }
    $stmt->close();
} else{
    $userNameIn = '<p>Bạn chưa đăng nhập</p>';
    $userHis = '';
    $userBtnOut = '<a href="login.php" class="log-in-btn">Đăng nhập <i class="fa-solid fa-arrow-right-to-bracket"></i></a>';
    $profileImage = 'Home-img/non-lgin.png'; 
}



//breakfast
$bf_no ='';
$selectBreakFastsql = "SELECT * FROM products WHERE breakfast = 1 LIMIT 8";
$breakfastResult = $conn->query($selectBreakFastsql);
$totalRowsBreakfast = $breakfastResult->num_rows;
if ($breakfastResult->num_rows === 0) {
    $bf_no = '<p style="text-transform: capitalize; font-style: italic; font-size: 1.2rem;">chưa có sản phẩm</p>';
}
//lunch
$lu_no ='';
$selectLunchsql = "SELECT * FROM products WHERE lunch = 1 LIMIT 8";
$lunchResult = $conn->query($selectLunchsql);
$totalRowsLunch = $lunchResult->num_rows;
if ($lunchResult->num_rows === 0) {
    $lu_no = '<p style="text-transform: capitalize; font-style: italic; font-size: 1.2rem;">chưa có sản phẩm</p>';
}
//dinner
$din_no ='';
$selectDinnersql = "SELECT * FROM products WHERE dinner = 1 LIMIT 8";
$dinnerResult = $conn->query($selectDinnersql);
$totalRowsDinner = $dinnerResult->num_rows;
if ($dinnerResult->num_rows === 0) {
    $din_no = '<p style="text-transform: capitalize; font-style: italic; font-size: 1.2rem;">chưa có sản phẩm</p>';
}
//dessert
$des_no ='';
$selectDessertsql = "SELECT * FROM products WHERE dessert = 1 LIMIT 8";
$dessertResult = $conn->query($selectDessertsql);
$totalRowsDessert = $dessertResult->num_rows;
if ($dessertResult->num_rows === 0) {
    $des_no = '<p style="text-transform: capitalize; font-style: italic; font-size: 1.2rem;">chưa có sản phẩm</p>';
}
//snacks
$sna_no ='';
$selectSnacksql = "SELECT * FROM products WHERE snacks = 1 LIMIT 8";
$snackResult = $conn->query($selectSnacksql);
$totalRowsSnack = $snackResult->num_rows;
if ($snackResult->num_rows === 0) {
    $sna_no = '<p style="text-transform: capitalize; font-style: italic; font-size: 1.2rem;">chưa có sản phẩm</p>';
}
//drinks
$dri_no ='';
$selectDrinksql = "SELECT * FROM products WHERE drinks = 1 LIMIT 8";
$drinkResult = $conn->query($selectDrinksql);
$totalRowsDrink = $drinkResult->num_rows;
if ($drinkResult->num_rows === 0) {
    $dri_no = '<p style="text-transform: capitalize; font-style: italic; font-size: 1.2rem;">chưa có sản phẩm</p>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/produces-page.css">
    <title>Thực đơn</title>
    <link rel="shortcut icon" href="Home-img/food-logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>
<body>
<!--
================================================================================
================================================================================
                                                                    LOADING-SECTION
================================================================================
================================================================================
        -->     
<div class="loader-container">
    <div class="waiting-img">
        <div class="waiting-card">
            <img src="Home-img/wm1.png" alt="">
        </div>
        <div class="waiting-card">
            <img src="Home-img/wm2.png" alt="">
        </div>
        <div class="waiting-card">
            <img src="Home-img/wm3.png" alt="">
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
<div class="toast" id="toastMessage"></div>

<!--
================================================================================
================================================================================
                                                                                    NAV-BAR
================================================================================
================================================================================
        -->        
        <div id="lg-out-message" class="lg-out-message">
        <div class="lg-out-message-content">
            <i class="fa-solid fa-circle-question fa-2xl" style="color: #D80032;"></i>
            <h3>Bạn có muốn thoát ra không?</h3>
            <div class="log-out-confirm">
            <button id="confirmLogout">Đúng</button>
            <button id="cancelLogout">Không</button>
            </div>
        </div>
    </div>
    <header>
        <nav class="nav-bar">
            <div class="logo">
                    <img src="Home-img/FT-logo.png" alt="">
            </div>
            <div class="links">
                    <ul>
                        <li><a href="index.php" >Trang chủ</a></li>
                        <li><a href="produces-page.php" class="menu-active">Menu</a></li>
                        <li><a href="restaurant_page.php" >Nhà hàng</a></li>
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
            </div>
        </nav>
    </header>
    <div class="sidebar-container">
        <div class="sidebar-content">
            <div class="item-img">
                <i class="fa-solid fa-ellipsis"></i>
            </div>
            <div class="item-img">
                <a href="#f-special" ><img src="Home-img/special.png" title="Món Đặc Biệt" alt=""></a>
            </div>
            <div class="item-img">
                <a href="#section1"><img src="Home-img/breakfast.png" title="Món Đặc Biệt" alt=""></a>
            </div>
            <div class="item-img">
                <a href="#section2"><img src="Home-img/lunch.png" title="Bữa trưa" alt=""></a>
            </div>
            <div class="item-img">
                <a href="#section3"><img src="Home-img/dinner.png" title="Bữa tối" alt=""></a>
            </div>
            <div class="item-img">
                <a href="#section4"><img src="Home-img/dessert.png" title="Tráng Miệng" alt=""></a>
            </div>
            <div class="item-img">
                <a href="#section5"><img src="Home-img/snack.png" title="Ăn vặt" alt=""></a>
            </div>
            <div class="item-img">
                <a href="#section6"><img src="Home-img/drink.png" title="Thức Uống" alt=""></a>
            </div>
        </div>
    </div>
    <section class="start">
        <div class="bar">
            <div class="bar-content">
                <span class="line1"></span>
                <span class="line2"></span>
                <span class="line3"></span>
            </div>
        </div>  
        <div class="hidden_search">
            <div class="search">
                <form action="results_of_search.php" method="get">
                    <input type="text" id="searchInput" name="search-query"  placeholder="Tìm kiếm sản phẩm" autocomplete="off"  onkeyup="liveSearch(this.value);">
                    <div class="category-search"><a href="advance_search.php"><i class="fa-solid fa-sliders"></a></i></div>
                    <button class="sear-btn" type="submit"><i class="fa-solid fa-magnifying-glass fa-lg"></i></button>
                </form>
            </div>
        </div>
        <div class="hidden-bar">
            <div class="hidden-bar-links">
                <a href="index.php" ><li>Trang chủ</li></a>
                <a href="#" class="menu-active"><li>Menu</li></a>
                <a href="restaurant_page.php" ><li>Nhà hàng</li></a>
            </div>
            <div class="hidden-bar-function">
                    <div class="btn-login">
                        <div class="btn-login-container">
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
                    <div class="cart">
                    <?php if($isUserLoggedIn): ?>
                            <a href="cart.php"><i class="fa-solid fa-cart-shopping fa-lg"></i></a>
                            <span id="hidden-cartBadge" class="badge"></span> 
                        <?php else: ?>
                            <a href="cart.php" onclick="showLoginAnnouncement(event)"><i class="fa-solid fa-cart-shopping fa-lg"></i></a>
                            <span id="hidden-cartBadge" class="badge"></span> 
                        <?php endif; ?>
                    </div>
                    <div class="favor">
                    <?php if($isUserLoggedIn): ?>
                            <a href="favor.php"><i class="fa-solid fa-heart fa-lg"></i></i></a>
                        <?php else: ?>
                            <a href="favor.php" onclick="showLoginAnnouncement(event)"><i class="fa-solid fa-heart fa-lg"></i></a>
                        <?php endif; ?>
                    </div>
            </div>
        </div>
        <header class="start-header">
            <h1>Thực đơn món ăn</h1>
        </header>
    </section> 

    <section class="category">
        <h1>Bạn muốn ăn gì hôm nay?</h1>
        <div class="f-special" id="f-special">
            <div class="img-container">
                <img src="Home-img/special.png" alt="">
            </div>
            <h3>Món đặc biệt</h3>
        </div>
        <div class="container">
            <div class="item" id="item1">
                <div class="img-container">
                    <img src="Home-img/breakfast.png" alt="">
                </div>
                <h3>Bữa sáng</h3>
            </div>
            <div class="item" id="item2">
                    <div class="img-container">
                        <img src="Home-img/lunch.png" alt="">
                    </div>
                    <h3>Bữa trưa</h3>
            </div>
            <div class="item" id="item3">
                <div class="img-container">
                    <img src="Home-img/dinner.png" alt="">
                </div>
                <h3>Bữa tối</h3>
            </div>
            <div class="item" id="item4">
                <div class="img-container">
                    <img src="Home-img/dessert.png" alt="">
                </div>
                <h3>Tráng miệng</h3>
            </div>
            <div class="item" id="item5">
                <div class="img-container">
                    <img src="Home-img/snack.png" alt="">
                </div>
                <h3>Ăn vặt</h3>
            </div>
            <div class="item" id="item6">
                <div class="img-container">
                    <img src="Home-img/drink.png" alt="">
                </div>
                <h3>Thức uống</h3>
            </div>
        </div>
    </section>
    <section class="section-special" id="special">
        <div class="special-content">
            <h1>Món đặc biệt hôm nay</h1>
            <div class="wrapper">
    <i id="left" class="fa-solid fa-chevron-left"></i>
    <ul class="carousel">
        <?php
        $randomProductSql = "SELECT product_id, image_1, name, price FROM products ORDER BY RAND() LIMIT 9";
        $randomProductResult = $conn->query($randomProductSql);
        while ($row = $randomProductResult->fetch_assoc()) {
            echo '<li class="guest-card">';
            echo '<div class="guest-img">';
            echo '<img src="' . $row['image_1'] . '" alt="img" draggable="false">';
            echo '</div>';
            echo '<div class="guest-comment">';
            echo '<h2>' . $row['name'] . '</h2>';
            echo '</div>';
            echo '<div class="rate">';
            echo '<p>Đánh Giá:</p>';
            echo '<i class="fa-solid fa-star"></i>';
            echo '<i class="fa-solid fa-star"></i>';
            echo '<i class="fa-solid fa-star"></i>';
            echo '<i class="fa-solid fa-star"></i>';
            echo '<i class="fa-regular fa-star-half-stroke"></i>';
            echo '</div>';
            echo '<div class="price">';
            echo '<p>Giá:<p> <strong>' . $row['price'] . ' VNĐ</strong>';
            echo '</div>';
            echo '<div class="card-link">';
            echo '<a href="Single-product-detail.php?product_id=' . $row['product_id'] . '" class="btn">Tìm hiểu thêm <i class="fa-solid fa-circle-question"></i></a>';
            if ($isUserLoggedIn) {
                echo '<a href="#" class="btn" onclick="addToCart()">Thêm vào giỏ hàng <i class="fa-solid fa-cart-shopping"></i></a>';
            } else {
                echo '<a href="#" class="btn" onclick="showLoginAnnouncement(event)">Thêm vào giỏ hàng <i class="fa-solid fa-cart-shopping"></i></a>';
            }
            echo '</div>';
            echo '</li>';
        }
        ?>
    </ul>
    <i id="right" class="fa-solid fa-chevron-right"></i>
</div>

        </div>
    </section>
    <!-- ===================================================================================
        ===================================================================================
                                                                        CHOICES-SECTION
        ===================================================================================
    ======================================================================================
    -->
<section class="choices">
    <section class="section" id="section1">
        <div class="top">
            <div class="line"></div>
            <h1>Bữa sáng</h1>
            <div class="line"></div>
        </div>
        <div class="stuff-container" id="stuff1">
            <?php echo $bf_no;?>
    <?php $cardCount = 0; ?>
    <?php while ($row = $breakfastResult->fetch_assoc()): 
        $pro_id=$row['product_id'];
        $pro_img=$row['image_1'];
        $pro_name=$row['name'];
        $pro_price=$row['price'];
        $queryStarRatingSql = "SELECT AVG(rating) AS avg_rating FROM ratings WHERE product_id = ?";
        $stmtdDisplayRating = mysqli_prepare($conn, $queryStarRatingSql);
        if ($stmtdDisplayRating) {
            mysqli_stmt_bind_param($stmtdDisplayRating, 's', $row["product_id"]);
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
        $alreadyFavorSql = "SELECT * FROM favorite_products WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($alreadyFavorSql);
        $stmt->bind_param("is", $user_id, $pro_id); 
        $stmt->execute();
        $result = $stmt->get_result();
        $isFavorited = $result->num_rows > 0;
        $stmt->close();
        ?>
        <div class="card <?php echo ($cardCount >= 4) ? 'hidden' : ''; ?>">
        <?php if ($isUserLoggedIn): ?>
            <div class="like" data-user-id="<?php echo $user_id; ?>" data-product-id="<?php echo $pro_id; ?>"><i class="fa-solid fa-heart" title="Yêu thích" <?php echo $isFavorited ? 'style="text-shadow: 0 0 2px; color: var(--heart-color); transform: scale(1.2); cursor: default;"': ''; ?>></i></div>
        <?php else: ?>
            <div class="like not-logged-in " data-user-id="<?php echo $user_id; ?>" data-product-id="<?php echo $pro_id; ?>"><i class="fa-solid fa-heart" title="Yêu thích" <?php echo $isFavorited ? 'style="text-shadow: 0 0 2px; color: var(--heart-color); transform: scale(1.2);"': ''; ?>></i></div>
        <?php endif; ?>
            <div class="fpic-container">
                <img src="<?php echo $pro_img; ?>" class="card-img-top" alt="">
            </div>
            <div class="card-body">
                <h3 class="card-title"><?php echo $pro_name; ?></h3>
                <div class="rate">
                    <p>Đánh Giá:</p>
                    <?php echo $userRatingDisplay ?>
                </div>
                <div class="price">
                    <p>Giá:<p> <strong><?php echo $pro_price; ?> VNĐ</strong>
                </div>
                <div class="card-link">
                <a href="Single-product-detail.php?product_id=<?php echo $row['product_id']; ?>" class="btn">Tìm hiểu thêm<i class="fa-solid fa-circle-question"></i></a>
                <?php if ($isUserLoggedIn): ?>
                    <button class="addToCart btn" data-product-id="<?php echo $row['product_id']; ?>">Thêm vào giỏ hàng <i class="fa-solid fa-cart-shopping"></i></button>
                <?php else: ?>
                    <button class="addToCart btn" onclick="showLoginAnnouncement(event)">Thêm vào giỏ hàng <i class="fa-solid fa-cart-shopping"></i></button>
                <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
        $cardCount++;
        ?>
    <?php endwhile; ?>
    <?php if ($totalRowsBreakfast > 4): ?>
        <button class="more" id="showSecondRow1"><i class="fa-solid fa-angles-down" title="Thêm"></i></button>
    <?php endif; ?>

        </div>
    </section>
    <section class="section" id="section2">
        <div class="top">
            <div class="line"></div>
            <h1>Bữa trưa</h1>
            <div class="line"></div>
        </div>
        <div class="stuff-container" id="stuff2">
        <?php echo $lu_no; ?>
    <?php $cardCount = 0; ?>
    <?php while ($row = $lunchResult->fetch_assoc()): 
        $pro_id=$row['product_id'];
        $pro_img=$row['image_1'];
        $pro_name=$row['name'];
        $pro_price=$row['price'];
        $queryStarRatingSql = "SELECT AVG(rating) AS avg_rating FROM ratings WHERE product_id = ?";
        $stmtdDisplayRating = mysqli_prepare($conn, $queryStarRatingSql);
        if ($stmtdDisplayRating) {
            mysqli_stmt_bind_param($stmtdDisplayRating, 's', $row["product_id"]);
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
        $alreadyFavorSql = "SELECT * FROM favorite_products WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($alreadyFavorSql);
        $stmt->bind_param("is", $user_id, $pro_id); // Assuming $user_id is set elsewhere
        $stmt->execute();
        $result = $stmt->get_result();
        $isFavorited = $result->num_rows > 0;
        $stmt->close();
    ?>
        <div class="card <?php echo ($cardCount >= 4) ? 'hidden' : ''; ?>">
        <?php if ($isUserLoggedIn): ?>
            <div class="like" data-user-id="<?php echo $user_id; ?>" data-product-id="<?php echo $pro_id; ?>"><i class="fa-solid fa-heart" title="Yêu thích" <?php echo $isFavorited ? 'style="text-shadow: 0 0 2px; color: var(--heart-color); transform: scale(1.2); cursor: default;"': ''; ?>></i></div>
        <?php else: ?>
            <div class="like not-logged-in " data-user-id="<?php echo $user_id; ?>" data-product-id="<?php echo $pro_id; ?>"><i class="fa-solid fa-heart" title="Yêu thích" <?php echo $isFavorited ? 'style="text-shadow: 0 0 2px; color: var(--heart-color); transform: scale(1.2);"': ''; ?>></i></div>
        <?php endif; ?>
            <div class="fpic-container">
                <img src="<?php echo $row['image_1']; ?>" class="card-img-top" alt="">
            </div>
            <div class="card-body">
                <h3 class="card-title"><?php echo $row['name']; ?></h3>
                <div class="rate">
                    <p>Đánh Giá:</p>
                    <?php echo $userRatingDisplay; ?>
                </div>
                <div class="price">
                    <p>Giá:<p> <strong><?php echo $row['price']; ?> VNĐ</strong>
                </div>
                <div class="card-link">
                <a href="Single-product-detail.php?product_id=<?php echo $row['product_id']; ?>" class="btn">Tìm hiểu thêm<i class="fa-solid fa-circle-question"></i></a>
                <?php if ($isUserLoggedIn): ?>
    <button class="addToCart btn" data-product-id="<?php echo $row['product_id']; ?>">Thêm vào giỏ hàng <i class="fa-solid fa-cart-shopping"></i></button>
<?php else: ?>
    <button class="addToCart btn" onclick="showLoginAnnouncement(event)">Thêm vào giỏ hàng <i class="fa-solid fa-cart-shopping"></i></button>
<?php endif; ?>
                </div>
            </div>
        </div>
        <?php
        $cardCount++;
        ?>
    <?php endwhile; ?>
    <?php if ($totalRowsLunch > 4): ?>
        <button class="more" id="showSecondRow2"><i class="fa-solid fa-angles-down" title="Thêm"></i></button>
    <?php endif; ?>
        </div>
    </section>
    <section class="section" id="section3">
        <div class="top">
            <div class="line"></div>
            <h1>Bữa tối</h1>
            <div class="line"></div>
        </div>
        <div class="stuff-container" id="stuff3">
        <?php echo $din_no; ?>
    <?php $cardCount = 0; ?>
    <?php while ($row = $dinnerResult->fetch_assoc()): 
        $pro_id=$row['product_id'];
        $pro_img=$row['image_1'];
        $pro_name=$row['name'];
        $pro_price=$row['price'];
        $queryStarRatingSql = "SELECT AVG(rating) AS avg_rating FROM ratings WHERE product_id = ?";
        $stmtdDisplayRating = mysqli_prepare($conn, $queryStarRatingSql);
        if ($stmtdDisplayRating) {
            mysqli_stmt_bind_param($stmtdDisplayRating, 's', $row["product_id"]);
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
        $alreadyFavorSql = "SELECT * FROM favorite_products WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($alreadyFavorSql);
        $stmt->bind_param("is", $user_id, $pro_id); // Assuming $user_id is set elsewhere
        $stmt->execute();
        $result = $stmt->get_result();
        $isFavorited = $result->num_rows > 0;
        $stmt->close();
        ?>
        <div class="card <?php echo ($cardCount >= 4) ? 'hidden' : ''; ?>">
        <?php if ($isUserLoggedIn): ?>
            <div class="like" data-user-id="<?php echo $user_id; ?>" data-product-id="<?php echo $pro_id; ?>"><i class="fa-solid fa-heart" title="Yêu thích" <?php echo $isFavorited ? 'style="text-shadow: 0 0 2px; color: var(--heart-color); transform: scale(1.2); cursor: default;"': ''; ?>></i></div>
        <?php else: ?>
            <div class="like not-logged-in " data-user-id="<?php echo $user_id; ?>" data-product-id="<?php echo $pro_id; ?>"><i class="fa-solid fa-heart" title="Yêu thích" <?php echo $isFavorited ? 'style="text-shadow: 0 0 2px; color: var(--heart-color); transform: scale(1.2);"': ''; ?>></i></div>
        <?php endif; ?>
            <div class="fpic-container">
                <img src="<?php echo $row['image_1']; ?>" class="card-img-top" alt="">
            </div>
            <div class="card-body">
                <h3 class="card-title"><?php echo $row['name']; ?></h3>
                <div class="rate">
                    <p>Đánh Giá:</p>
                    <?php echo $userRatingDisplay; ?>
                </div>
                <div class="price">
                    <p>Giá:<p> <strong><?php echo $row['price']; ?> VNĐ</strong>
                </div>
                <div class="card-link">
                <a href="Single-product-detail.php?product_id=<?php echo $row['product_id']; ?>" class="btn">Tìm hiểu thêm<i class="fa-solid fa-circle-question"></i></a>
                <?php if ($isUserLoggedIn): ?>
    <button class="addToCart btn" data-product-id="<?php echo $row['product_id']; ?>">Thêm vào giỏ hàng <i class="fa-solid fa-cart-shopping"></i></button>
<?php else: ?>
    <button class="addToCart btn" onclick="showLoginAnnouncement(event)">Thêm vào giỏ hàng <i class="fa-solid fa-cart-shopping"></i></button>
<?php endif; ?>
                </div>
            </div>
        </div>
        <?php
        $cardCount++;
        ?>
    <?php endwhile; ?>
    <?php if ($totalRowsDinner > 4): ?>
        <button class="more" id="showSecondRow3"><i class="fa-solid fa-angles-down" title="Thêm"></i></button>
    <?php endif; ?>
        </div>
    </section>
    <section class="section" id="section4">
        <div class="top">
            <div class="line"></div>
            <h1>Tráng miệng</h1>
            <div class="line"></div>
        </div>
        <div class="stuff-container" id="stuff4">
        <?php echo $des_no; ?>
    <?php $cardCount = 0; ?>
    <?php while ($row = $dessertResult->fetch_assoc()): 
        $pro_id=$row['product_id'];
        $pro_img=$row['image_1'];
        $pro_name=$row['name'];
        $pro_price=$row['price'];
        $queryStarRatingSql = "SELECT AVG(rating) AS avg_rating FROM ratings WHERE product_id = ?";
        $stmtdDisplayRating = mysqli_prepare($conn, $queryStarRatingSql);
        if ($stmtdDisplayRating) {
            mysqli_stmt_bind_param($stmtdDisplayRating, 's', $row["product_id"]);
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
        $alreadyFavorSql = "SELECT * FROM favorite_products WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($alreadyFavorSql);
        $stmt->bind_param("is", $user_id, $pro_id); // Assuming $user_id is set elsewhere
        $stmt->execute();
        $result = $stmt->get_result();
        $isFavorited = $result->num_rows > 0;
        $stmt->close();
    ?>
        <div class="card <?php echo ($cardCount >= 4) ? 'hidden' : ''; ?>">
        <?php if ($isUserLoggedIn): ?>
            <div class="like" data-user-id="<?php echo $user_id; ?>" data-product-id="<?php echo $pro_id; ?>"><i class="fa-solid fa-heart" title="Yêu thích" <?php echo $isFavorited ? 'style="text-shadow: 0 0 2px; color: var(--heart-color); transform: scale(1.2); cursor: default;"': ''; ?>></i></div>
        <?php else: ?>
            <div class="like not-logged-in " data-user-id="<?php echo $user_id; ?>" data-product-id="<?php echo $pro_id; ?>"><i class="fa-solid fa-heart" title="Yêu thích" <?php echo $isFavorited ? 'style="text-shadow: 0 0 2px; color: var(--heart-color); transform: scale(1.2);"': ''; ?>></i></div>
        <?php endif; ?>
            <div class="fpic-container">
                <img src="<?php echo $row['image_1']; ?>" class="card-img-top" alt="">
            </div>
            <div class="card-body">
                <h3 class="card-title"><?php echo $row['name']; ?></h3>
                <div class="rate">
                    <p>Đánh Giá:</p>
                    <?php echo $userRatingDisplay; ?>
                </div>
                <div class="price">
                    <p>Giá:<p> <strong><?php echo $row['price']; ?> VNĐ</strong>
                </div>
                <div class="card-link">
                <a href="Single-product-detail.php?product_id=<?php echo $row['product_id']; ?>" class="btn">Tìm hiểu thêm<i class="fa-solid fa-circle-question"></i></a>
                <?php if ($isUserLoggedIn): ?>
    <button class="addToCart btn" data-product-id="<?php echo $row['product_id']; ?>">Thêm vào giỏ hàng <i class="fa-solid fa-cart-shopping"></i></button>
<?php else: ?>
    <button class="addToCart btn" onclick="showLoginAnnouncement(event)">Thêm vào giỏ hàng <i class="fa-solid fa-cart-shopping"></i></button>
<?php endif; ?>
                </div>
            </div>
        </div>
        <?php
        $cardCount++;
        ?>
    <?php endwhile; ?>
    <?php if ($totalRowsDessert > 4): ?>
        <button class="more" id="showSecondRow4"><i class="fa-solid fa-angles-down" title="Thêm"></i></button>
    <?php endif; ?>
        </div>
    </section>
    <section class="section" id="section5">
        <div class="top">
            <div class="line"></div>
            <h1>ăn vặt</h1>
            <div class="line"></div>
        </div>
        <div class="stuff-container" id="stuff5">
        <?php echo $sna_no; ?>
    <?php $cardCount = 0; ?>
    <?php while ($row = $snackResult->fetch_assoc()): 
        $pro_id=$row['product_id'];
        $pro_img=$row['image_1'];
        $pro_name=$row['name'];
        $pro_price=$row['price'];
        $queryStarRatingSql = "SELECT AVG(rating) AS avg_rating FROM ratings WHERE product_id = ?";
        $stmtdDisplayRating = mysqli_prepare($conn, $queryStarRatingSql);
        if ($stmtdDisplayRating) {
            mysqli_stmt_bind_param($stmtdDisplayRating, 's', $row["product_id"]);
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
        $alreadyFavorSql = "SELECT * FROM favorite_products WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($alreadyFavorSql);
        $stmt->bind_param("is", $user_id, $pro_id); // Assuming $user_id is set elsewhere
        $stmt->execute();
        $result = $stmt->get_result();
        $isFavorited = $result->num_rows > 0;
        $stmt->close();
        ?>
        <div class="card <?php echo ($cardCount >= 4) ? 'hidden' : ''; ?>">
        <?php if ($isUserLoggedIn): ?>
            <div class="like" data-user-id="<?php echo $user_id; ?>" data-product-id="<?php echo $pro_id; ?>"><i class="fa-solid fa-heart" title="Yêu thích" <?php echo $isFavorited ? 'style="text-shadow: 0 0 2px; color: var(--heart-color); transform: scale(1.2); cursor: default;"': ''; ?>></i></div>
        <?php else: ?>
            <div class="like not-logged-in " data-user-id="<?php echo $user_id; ?>" data-product-id="<?php echo $pro_id; ?>"><i class="fa-solid fa-heart" title="Yêu thích" <?php echo $isFavorited ? 'style="text-shadow: 0 0 2px; color: var(--heart-color); transform: scale(1.2);"': ''; ?>></i></div>
        <?php endif; ?>
            <div class="fpic-container">
                <img src="<?php echo $row['image_1']; ?>" class="card-img-top" alt="">
            </div>
            <div class="card-body">
                <h3 class="card-title"><?php echo $row['name']; ?></h3>
                <div class="rate">
                    <p>Đánh Giá:</p>
                    <?php echo $userRatingDisplay; ?>
                </div>
                <div class="price">
                    <p>Giá:<p> <strong><?php echo $row['price']; ?> VNĐ</strong>
                </div>
                <div class="card-link">
                <a href="Single-product-detail.php?product_id=<?php echo $row['product_id']; ?>" class="btn">Tìm hiểu thêm<i class="fa-solid fa-circle-question"></i></a>
                <?php if ($isUserLoggedIn): ?>
    <button class="addToCart btn" data-product-id="<?php echo $row['product_id']; ?>">Thêm vào giỏ hàng <i class="fa-solid fa-cart-shopping"></i></button>
<?php else: ?>
    <button class="addToCart btn" onclick="showLoginAnnouncement(event)">Thêm vào giỏ hàng <i class="fa-solid fa-cart-shopping"></i></button>
<?php endif; ?>
                </div>
            </div>
        </div>
        <?php
        $cardCount++;
        ?>
    <?php endwhile; ?>
    <?php if ($totalRowsSnack > 4): ?>
        <button class="more" id="showSecondRow5"><i class="fa-solid fa-angles-down" title="Thêm"></i></button>
    <?php endif; ?>
        </div>
    </section>
    <section class="section" id="section6">
        <div class="top">
            <div class="line"></div>
            <h1>Thức uống</h1>
            <div class="line"></div>
        </div>
        <div class="stuff-container" id="stuff6">
        <?php echo $dri_no; ?>
    <?php $cardCount = 0; ?>
    <?php while ($row = $drinkResult->fetch_assoc()): 
        $pro_id=$row['product_id'];
        $pro_img=$row['image_1'];
        $pro_name=$row['name'];
        $pro_price=$row['price'];
        $queryStarRatingSql = "SELECT AVG(rating) AS avg_rating FROM ratings WHERE product_id = ?";
        $stmtdDisplayRating = mysqli_prepare($conn, $queryStarRatingSql);
        if ($stmtdDisplayRating) {
            mysqli_stmt_bind_param($stmtdDisplayRating, 's', $row["product_id"]);
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
        $alreadyFavorSql = "SELECT * FROM favorite_products WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($alreadyFavorSql);
        $stmt->bind_param("is", $user_id, $pro_id); // Assuming $user_id is set elsewhere
        $stmt->execute();
        $result = $stmt->get_result();
        $isFavorited = $result->num_rows > 0;
        $stmt->close();
    ?>
        <div class="card <?php echo ($cardCount >= 4) ? 'hidden' : ''; ?>">
        <?php if ($isUserLoggedIn): ?>
            <div class="like" data-user-id="<?php echo $user_id; ?>" data-product-id="<?php echo $pro_id; ?>"><i class="fa-solid fa-heart" title="Yêu thích" <?php echo $isFavorited ? 'style="text-shadow: 0 0 2px; color: var(--heart-color); transform: scale(1.2); cursor: default;"': ''; ?>></i></div>
        <?php else: ?>
            <div class="like not-logged-in " data-user-id="<?php echo $user_id; ?>" data-product-id="<?php echo $pro_id; ?>"><i class="fa-solid fa-heart" title="Yêu thích" <?php echo $isFavorited ? 'style="text-shadow: 0 0 2px; color: var(--heart-color); transform: scale(1.2);"': ''; ?>></i></div>
        <?php endif; ?>
            <div class="fpic-container">
                <img src="<?php echo $row['image_1']; ?>" class="card-img-top" alt="">
            </div>
            <div class="card-body">
                <h3 class="card-title"><?php echo $row['name']; ?></h3>
                <div class="rate">
                    <p>Đánh Giá:</p>
                    <?php 
                    echo $userRatingDisplay;
                    ?>
                </div>
                <div class="price">
                    <p>Giá:<p> <strong><?php echo $row['price']; ?> VNĐ</strong>
                </div>
                <div class="card-link">
                    <a href="Single-product-detail.php?product_id=<?php echo $row['product_id']; ?>" class="btn">Tìm hiểu thêm<i class="fa-solid fa-circle-question"></i></a>
                    <?php if ($isUserLoggedIn): ?>
                        <button class="addToCart btn" data-product-id="<?php echo $row['product_id']; ?>">Thêm vào giỏ hàng <i class="fa-solid fa-cart-shopping"></i></button>
                    <?php else: ?>
                        <button class="addToCart btn" onclick="showLoginAnnouncement(event)">Thêm vào giỏ hàng <i class="fa-solid fa-cart-shopping"></i></button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
        $cardCount++;
        ?>
    <?php endwhile; ?>
    <?php if ($totalRowsDrink > 4): ?>
        <button class="more" id="showSecondRow6"><i class="fa-solid fa-angles-down" title="Thêm"></i></button>
    <?php endif; ?>
        </div>
    </section>
</section>

        <!-- ===================================================================================
        ===================================================================================
                                                                        FOOTER-SECTION
        ===================================================================================
    ======================================================================================
    -->
<footer>
    <div class="footer-container" id="footer">
        <div class="left box">
            <h2>Thông tin</h2>
            <p><strong>FoodToast</strong> cung cấp cho bạn trải nghiệm mua sắm đồ ăn trực tuyến thú vị. Đừng chần chừ, truy cập FoodToast để tiết kiệm thời gian và chi phí ngay nào.</p>
        </div>
        <div class="middle box">
            <h2>Liên lạc</h2>
                <ul>
                    <li><i class="fa-solid fa-phone fa-lg" style="color: #1d62d7;"></i>0935.68.68.68.</</li>
                    <li><i class="fa-solid fa-envelope fa-lg" style="color: #d0c335;"></i>Tien@gmail.com.</a></li>
                    <li><i class="fa-solid fa-location-dot fa-lg" style="color: #ff3300;"></i>Quận 10, Tp.Hồ Chí Minh.</li>    
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
    <br>
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
    <script src="js/produces-page.js"></script>
</body>
</html>
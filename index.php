<?php
include("connect.php");
session_start();
if (isset($_SESSION['res_loggedin']) && $_SESSION['res_loggedin'] === true) {
    unset($_SESSION['res_loggedin']);
}
$isUserLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$RestaurantsSql = "SELECT restaurant_id, brand_image FROM restaurants ";
$res_result = $conn->query($RestaurantsSql);
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
    <title>FoodToast</title>
    <link rel="shortcut icon" href="Home-img/food-logo.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css/index.css">
    <script src="https://unpkg.com/scrollreveal"></script>
</head>
<body>
    <div class="loader-container">
        <div class="waiting-img">
            <div class="waiting-card">
                <img src="Home-img/wp1.png" alt="">
            </div>
            <div class="waiting-card">
                <img src="Home-img/wp2.png" alt="">
            </div>
            <div class="waiting-card">
                <img src="Home-img/wp3.png" alt="">
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
            <h2>Bạn có muốn thoát ra không?</h2>
            <div class="log-out-confirm">
            <button id="confirmLogout">Đúng</button>
            <button id="cancelLogout">Không</button>
            </div>
        </div>
    </div>
    <header>
        <nav class="nav-bar">
            <div class="logo">
                <a href="#"><img src="Home-img/FT-logo.png" alt=""></a>
            </div>
            <div class="links">
                    <ul>
                        <li><a href="#" class="home-active">Trang chủ</a></li>
                        <li><a href="produces-page.php">Menu</a></li>
                        <li><a href="#">Nhà hàng</a></li>
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
    <div class="sidebar-container">
        <div class="sidebar-content">
            <div class="item-img">
                <i class="fa-solid fa-ellipsis"></i>
            </div>
            <div class="item-img">
                <a href="#service" ><img src="Home-img/sb-service.png" title="Dịch Vụ" alt=""></a>
            </div>
            <div class="item-img">
                <a href="#introduce" ><img src="Home-img/sb-question.png" title="Giới Thiệu" alt=""></a>
            </div>
            <div class="item-img">
                <a href="#special"><img src="products-img/special.png" title="Món Đặc biệt" alt=""></a>
            </div>
            <div class="item-img">
                <a href="#testimonial"><img src="Home-img/sb-testimonial.png" title="Ý kiến" alt=""></a>
            </div>
            <div class="item-img">
                <a href="#user"><img src="Home-img/sb-user.png" title="Người Dùng" alt=""></a>
            </div>
            <div class="item-img">
                <a href="#partner"><img src="Home-img/sb-store.png" title="Nhà Hàng" alt=""></a>
            </div>
        </div>
    </div>
    <main>
        <section class="top">
            <div class="top-pic">
                <img src="Home-img/pic2.jpg" alt="">
            </div>
            <div class="top-content">
                <h1>Chào mừng đến <br><strong>FoodToast</strong></h1>
                    <p>Website đặt thức ăn</p>
                        <a href="produces-page.php"><button class="btn-order">Order Ngay</button></a>
                        <a href="#introduce"><button class="btn-about">Tìm hiểu thêm</button></a> 
            </div>
        </section> 
        <section class="service-content" id="service" >
            <h1 class="sec-header1">Chúng tôi Đảm bảo</h1>
            <div class="cards">
                <div class="service-card">
                    <div class="img-icon">
                        <img src="Home-img/menu.png" alt="">
                    </div>
                    <span><p>Chất lượng và đa dạng món ăn<p><span>
                </div>
                <div class="service-card">
                    <div class="img-icon">
                        <img src="Home-img/order.png" alt="">
                    </div>
                    <span><p>Tiện lợi và dễ dàng đặt hàng<p></span>
                </div>
                <div class="service-card">
                    <div class="img-icon">
                        <img src="Home-img/delivey.png" alt="">
                    </div>
                    <span><p>Giao hàng nhanh chóng và an toàn<p></span>
                </div>
            </div>
        </section>

        <section class="sec-two" id="introduce">
            <h1 class="sec-header2">FOODTOAST?</h1>  
            <div class="intro-content">
                <div class="intro-img">
                    <img src="Home-img/about-us.png" alt="">
                </div>
                <div class="intro-txt">
                    <h1>Về chúng tôi</h1> 
                    <h3> Website đặt thức ăn </h3>
                    <p>
                    
                    <b style="padding-left: 10px;">FoodToast Được Tạo Nên Để:</b> <br>
                    <strong>- Mang lại sự tiện lợi và linh hoạt cho khách hàng: </strong>
                    <br>
                    Chúng tôi nhận thấy rằng nhu cầu của khách hàng ngày càng tăng về việc đặt đồ ăn trực tuyến và giao hàng tận nơi. <br> Việc thành lập trang web này giúp chúng tôi đáp ứng nhu cầu đó và mang lại sự thuận tiện cho người dùng.
                    <br>
                    <strong>- Mở rộng phạm vi và lựa chọn ẩm thực:</strong>
                    <br>
                    Thông qua trang web, khách hàng có thể lựa chọn nhiều món mới lạ, độc đáo.
                    <br>
                    <strong>- Đảm bảo chất lượng và đáng tin cậy: </strong>
                    <br>
                    Chúng tôi luôn luôn theo dõi và đánh giá các đối tác của mình để đảm bảo rằng khách hàng sẽ nhận được trải nghiệm ẩm thực tốt nhất.
                    </p>
                </div>
            </div>
        </section>
        <section class="sec-three">
        <div class="special" id="special">
            <h1 class="sec-header3">Deal đặc biệt hôm nay</h1>
            <div class="special-contain">
            <?php $sqlIndex = "SELECT product_id, image_1, name, price FROM products ORDER BY RAND() LIMIT 6";
                $Randomresult = $conn->query($sqlIndex);

                if ($Randomresult->num_rows > 0) {
                    while ($row = $Randomresult->fetch_assoc()) {
                        echo '<div class="item">';
                        echo'<div class="special-img">';
                        echo'<img src="' .$row['image_1'] . '" alt="">
                    </div>';
                        echo '<div class="special-content">';
                        echo '<h2>' . $row['name'] . '</h2>';
                        echo '<p>Giá: <b>' . $row['price'] . ' VNĐ</b></p>';
                        echo '</div>';
                        echo'<div class="function-card-btn">';
                        echo'<a href="Single-product-detail.php?product_id='. $row['product_id'] .'" class="info-btn">Tìm hiểu thêm<i class="fa-solid fa-circle-question"></i></a>';
                        if ($isUserLoggedIn){
                            echo'<button class="into-cart-btn" data-product-id="'.$row['product_id'] .'">Thêm vào giỏ hàng <i class="fa-solid fa-cart-shopping"></i></button>';
                        }else{
                        echo'<button class="into-cart-btn" onclick="showLoginAnnouncement(event)">Thêm vào giỏ hàng <i class="fa-solid fa-cart-shopping"></i></button>';
                        }
                        echo'</div>';
                        echo '</div>';
                    }
                } else {
                    echo 'No products found.';
                }
?>
            </div>
        </section>
        <section class="testimonial" id="testimonial">
            <h1 class="sec-header4">Nhận xét về chúng tôi</h1>
            <div class="wrapper">
                <i id="left" class="fa-solid fa-chevron-left"></i>
                <ul class="carousel">
                    <li class="guest-card">
                        <div class="guest-img">
                            <img src="Home-img/g1.png" alt="img" draggable="false"></div>
                            <div class="guest-comment">
                                <h2>User</h2>
                                <span> Rất tuyệt vời</span>
                            </div>
                    </li>
                    <li class="guest-card">
                        <div class="guest-img">
                            <img src="Home-img/g2.png" alt="img" draggable="false"></div>
                            <div class="guest-comment">
                                <h2>User</h2>
                                <span> Rất tuyệt vời</span>
                            </div>
                    </li>
                    <li class="guest-card">
                        <div class="guest-img">
                            <img src="Home-img/g3.png" alt="img" draggable="false"></div>
                            <div class="guest-comment">
                                <h2>User</h2>
                                <span> Rất tuyệt vời</span>
                            </div>
                    </li>
                    <li class="guest-card">
                        <div class="guest-img">
                            <img src="Home-img/g4.png" alt="img" draggable="false"></div>
                            <div class="guest-comment">
                                <h2>User</h2>
                                <span> Rất tuyệt vời</span>
                            </div>
                    </li>
                    <li class="guest-card">
                        <div class="guest-img">
                            <img src="Home-img/g5.png" alt="img" draggable="false"></div>
                            <div class="guest-comment">
                                <h2>User</h2>
                                <span> Rất tuyệt vời</span>
                            </div>
                    </li>
                    <li class="guest-card">
                        <div class="guest-img">
                            <img src="Home-img/g6.png" alt="img" draggable="false"></div>
                            <div class="guest-comment">
                                <h2>User</h2>
                                <span> Rất tuyệt vời</span>
                            </div>
                    </li>
                    <li class="guest-card">
                        <div class="guest-img">
                            <img src="Home-img/g7.png" alt="img" draggable="false"></div>
                            <div class="guest-comment">
                                <h2>User</h2>
                                <span> Rất tuyệt vời</span>
                            </div>
                    </li>
                    <li class="guest-card">
                        <div class="guest-img">
                            <img src="Home-img/g8.png" alt="img" draggable="false"></div>
                            <div class="guest-comment">
                                <h2>User</h2>
                                <span> Rất tuyệt vời</span>
                            </div>
                    </li>
                    <li class="guest-card">
                        <div class="guest-img">
                            <img src="Home-img/g9.png" alt="img" draggable="false"></div>
                        <div class="guest-comment">
                            <h2>User</h2>
                            <span> Rất tuyệt vời</span>
                        </div>
                    </li>
                </ul>
                <i id="right" class="fa-solid fa-chevron-right"></i>
            </div>
            <h1 class="sec-header5">đối tác với</h1>
            <div class="brands">
                <?php
                    $count = 0;
                    $totalBrands = $res_result->num_rows; 
                    echo '<div class="brands-container first-row">'; 
                    while ($row = $res_result->fetch_assoc()):
                        $brandImg = $row['brand_image'];
                        $restaurantId = $row['restaurant_id']; 
                        echo '<a href="res_home.php?restaurant_id=' . $restaurantId . '">';
                        echo '<div class="brands-img-container">';
                        echo '<img src="' . $brandImg . '" alt="">';
                        echo '</div>';
                        echo '</a>';
                        $count++;
                        if ($count == 4 || $count == $totalBrands) {
                            break;
                        }
                    endwhile;
                    echo '</div>'; 
                    if ($totalBrands > 4) {
                        echo '<button id="showSecondRowButton" class="show-second-row-btn">Xem Thêm</button>';
                        echo '<div class="brands-container second-row" style="display: none;">'; 
                        while ($row = $res_result->fetch_assoc()):
                            $brandImg = $row['brand_image'];
                            $restaurantId = $row['restaurant_id'];
                            echo '<a href="res_home.php?restaurant_id=' . $restaurantId . '" class="img-res-link">';
                            echo '<div class="brands-img-container">';
                            echo '<img src="' . $brandImg . '" alt="">';
                            echo '</div>';
                            echo '</a>';
                            echo '<a href="restaurant_page.php" id="viewAllLink" class="res-page-btn" >Xem tất cả</a>';
                            $count++;
                            if ($count == 8 ) {
                                break;
                            }
                        endwhile;
                        echo '</div>'; 
                    }
                ?>
            </div>
        </section>
        <section class="sec-five">
            <div class="participate" id="user">
                <h1 class="sec-header6">Tham gia với chúng tôi</h1>
                <div class="participate-content">
                    <div class="participate-img">
                        <img src="Home-img/participant.jpg" alt="">
                    </div>
                    <div class="btn-for-user-section">
                        <h2>Trở thành thành viên ngay</h2>
                        <div class="btn-for-user">
                            <a href="login.php" class="userLogBtn link"><span>đăng ký</span>
                                
                            </a>
                            <a href="register.php" class="userResBtn link">
                                <span>đăng nhập</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="partner" id="partner">
            <h1 class="sec-header7">Là cửa hàng?</h1>
            <div class="partner-content">
                <div class="partner-txt">
                    <h2>Trở thành đối tác</h2>
                    <dl class="txt">
                        <dt>Mở rộng phạm vi khách hàng:</dt>
                        <dd>-  Chúng tôi có một cơ sở khách hàng rộng lớn và đa dạng. <br>- Trở thành đối tác của chúng tôi sẽ giúp cửa hàng của bạn tiếp cận được với một lượng khách hàng mới, tăng doanh số và tạo ra cơ hội kinh doanh mới.</dd>
                        <dt>Quản lý đơn đặt hàng thuận tiện:</dt>
                        <dd>-  Chúng tôi cung cấp một giao diện quản lý đơn đặt hàng dễ sử dụng, giúp bạn quản lý và xử lý đơn hàng một cách hiệu quả</dd>
                        <dt>Xây dựng tương tác và đánh giá tích cực:</dt>
                        <dd>-  Điều này giúp tạo niềm tin và độ tin cậy trong cửa hàng của bạn, thu hút khách hàng mới và tạo ra một trải nghiệm tốt cho người dùng.</dd>
                    </dl>
                    <div class="btn-for-res">
                        <a href="res_register.php" class="login-res link"><button>Đăng ký</button></a>
                    <a href="res_register.php"class="register-res link"><button>Đăng nhập</button></a>
                    </div>
                    
                </div>
                <div class="partner-img">
                    <img src="Home-img/restaurant.jpg" alt="">
                </div>
            </div>


        </section>
    </main>
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
    <script src="js/index.js"></script>
    <!-- <script>
        ScrollReveal({  
        reset: false,
        duration: 1800
    });
		ScrollReveal().reveal('.sec-header1', { delay: 150, distance:'100px',  origin: 'left' });
        ScrollReveal().reveal('.cards .service-card ', { delay: 280, distance: '100px', origin: 'bottom', interval: 150 });
        ScrollReveal().reveal('.sec-header2', { delay: 150, distance:'100px', origin: 'right' });
        ScrollReveal().reveal('.intro-img', { delay: 165, distance: '120px', origin: 'top' });
        ScrollReveal().reveal('.intro-txt', { delay: 185, distance: '120px', origin: 'bottom' });
        ScrollReveal().reveal('.sec-header3', {delay: 150, distance: '50px', origin:'top'});
        ScrollReveal().reveal('.sec-header4', {delay: 150, distance: '60px', origin:'bottom'});
        ScrollReveal().reveal('.wrapper', {delay: 190, distance: '65px', origin:'bottom'});
        ScrollReveal().reveal('.sec-header5', {delay: 160, distance: '0px', easing: 'ease-out' }); -->

	</script>
    <?php 
    $conn->close();
    ?>
</body> 
</html>
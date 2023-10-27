<?php 
include("connect.php");
$isUserLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
session_start();
if (isset($_GET['search-query'])) {
    $query = $_GET['search-query'];
    $sql = "SELECT * FROM products WHERE name LIKE '%$query%'";
    $search_result = mysqli_query($conn, $sql);
}
    $recordsPerPage = 12;
    $totalRecords = mysqli_num_rows($search_result);
    $totalPages = ceil($totalRecords / $recordsPerPage);
    $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $offset = ($currentPage - 1) * $recordsPerPage;
    $sql = "SELECT * FROM products WHERE name LIKE '%$query%' LIMIT $recordsPerPage OFFSET $offset";
    $result = mysqli_query($conn, $sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tìm kiếm</title>
    <link rel="stylesheet" href="css/results_of_search.css">
    <link rel="shortcut icon" href="Home-img/food-logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
<div class="toast" id="toastMessage"></div>
    <nav>
        <a href="index.php"><i class="fa-solid fa-house"></i> Trang chủ</a>
        <a href="produces-page.php"><i class="fa-solid fa-clipboard-list"></i> Menus</a>
        <?php if($isUserLoggedIn): ?>
                            <a href="cart.php" class="cart-link"><i class="fa-solid fa-bag-shopping"></i> Giỏ hàng <span id="cartBadge" class="badge"></span></a> 
                        <?php else: ?>
                            <a href="cart.php" class="cart-link" onclick="showLoginAnnouncement(event)"><i class="fa-solid fa-bag-shopping"></i> Giỏ hàng <span id="cartBadge" class="badge"></span></a> 
                        <?php endif; ?>
        <div class="search" >
            <form action="results_of_search.php" method="get" class="product-search-form" id="productSearchForm">
            <input type="text" id="searchInput" name="search-query"  placeholder="Tìm kiếm sản phẩm" autocomplete="off"  onkeyup="liveSearch(this.value);">
            <button class="sear-btn" type="submit"><i class="fa-solid fa-magnifying-glass fa-lg"></i></button>
            <div class="category-search"><a href="advance_search.php"><i class="fa-solid fa-sliders"></a></i></div>
            <div id='searchResults' class="searchResults"></div>
            </form>
        </div>
    </nav>


    <h2 class="search-result">Kết Quả Tìm Kiếm Của: <?php echo $query ?></h2>
    <section class="query-search-section">
        <?php 
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $pro_Id = $row['product_id'];
                    $pro_name = $row['name'];
                    $pro_price =$row['price'];
                    $imagePath = $row['image_1'];
                    echo '<div class="card">';
                    echo '<div class="like"><i class="fa-solid fa-heart" title="Yêu thích"></i></div>';
                    echo '<div class="fpic-container">';
                    echo '<img src="' . $imagePath . '" class="card-img-top" alt="' . $pro_name . '" />';
                    echo '</div>';
                    echo '<div class="card-body">';
                    echo '<h3 class="card-title">' . $pro_name . '</h3>';
                    echo '<div class="rate">';
                    echo '<p>Đánh Giá:</p>';
                    echo '</div>';
                    echo '<div class="price">';
                    echo '<p>Giá:<p> <strong>' . $row['price'] . ' VNĐ</strong>';
                    echo '</div>';
                    echo '<div class="card-link">';
                    echo '<a href="Single-product-detail.php?product_id=' . $pro_Id . '" class="btn">Tìm hiểu thêm<i class="fa-solid fa-circle-question"></i></a>';
                    
                    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                        echo '<button class="addToCart btn" data-product-id="' . $pro_Id . '">Thêm vào giỏ hàng <i class="fa-solid fa-cart-shopping"></i></button>';
                    } else {
                        echo '<button class="addToCart btn" onclick="showLoginAnnouncement(event)" >Thêm vào giỏ hàng <i class="fa-solid fa-cart-shopping"></i></button>';
                    }
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p style="font-size: 1rem; padding: 4px; font-weight: 400; font-style: italic; text-align:center;">Không tìm thấy sản phẩm nào</p>';
            }               
        ?>
    </section>
    <?php 
        echo '<div class="pagination">';
        if ($currentPage > 1) {
            echo '<a href="?search-query=' . $query . '&page=' . ($currentPage - 1) . '" class = "prevBtn"><i class="fa-solid fa-angle-left"></i></a>';
        }
        for ($i = 1; $i <= $totalPages; $i++) {
            echo '<a href="?search-query=' . $query . '&page=' . $i . '"';
            if ($i == $currentPage) {
                echo ' class="active-page"';
            }
            echo '>' . $i . '</a>';
        }
        if ($currentPage < $totalPages) {
            echo '<a href="?search-query=' . $query . '&page=' . ($currentPage + 1) . '" class = "nextBtn"><i class="fa-solid fa-angle-right"></i></a>';
        }
        echo '</div>';
    ?>
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
    <script src="js/results_of_search.js"></script>
</body>
<?php 
        mysqli_close($conn);
?>
</html>

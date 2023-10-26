<?php
include("connect.php");
session_start();
if (isset($_GET['delete_success']) && $_GET['delete_success'] == 1) {
    $successMessage = 'Xóa thành công.';
}
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT product_id FROM favorite_products WHERE user_id = $user_id";
    $result = mysqli_query($conn, $query);
    $totalRecords = mysqli_num_rows($result);
    $recordsPerPage = 12;
    $totalPages = ceil($totalRecords / $recordsPerPage);
    $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $offset = ($currentPage - 1) * $recordsPerPage;
    $sql = "SELECT product_id FROM favorite_products WHERE user_id = $user_id LIMIT $recordsPerPage OFFSET $offset";
    $result = mysqli_query($conn, $sql);
    $relateResPro = '';
    while ($row = mysqli_fetch_assoc($result)) {
        $product_id = $row['product_id'];
        $product_query = "SELECT name, price, image_1 FROM products WHERE product_id = '$product_id'";
        $product_result = mysqli_query($conn, $product_query);
        if ($product_row = mysqli_fetch_assoc($product_result)) {
            $related_product_name = $product_row['name'];
            $related_product_price = $product_row['price'];
            $related_product_img = $product_row['image_1'];

            $relateResPro .= '<div class="card">';
            $relateResPro .= '<div class="fpic-container">';
            $relateResPro .= '<img src="' . $related_product_img . '" class="card-img-top" alt="' . $related_product_name . '">';
            $relateResPro .= '</div>';
            $relateResPro .= '<div class="card-body">';
            $relateResPro .= '<h3 class="card-title">' . $related_product_name . '</h3>';
            $relateResPro .= '<div class="price"> <p>Giá:<p> <strong>' . $related_product_price . '</strong></div>';
            $relateResPro .= '<div class="card-link">';
            $relateResPro .= '<a href="Single-product-detail.php?product_id=' . $product_id . '" class="btn">Chi tiết sản phẩm<i class="fa-solid fa-circle-question"></i></a>';

            $relateResPro .= '<form action="delete_from_favorite.php" method="post">';
            $relateResPro .= '<input type="hidden" name="product_id" value="' . $product_id . '">';
            $relateResPro .= '<input type="hidden" name="user_id" value="' . $user_id . '">';
            $relateResPro .= '<button type="submit" name="delete" class="deleteFavoriteProduct btn">Xóa khỏi yêu thích <i class="fa-solid fa-trash"></i></button>';
            $relateResPro .= '</form>';
            
            
            $relateResPro .= '</div>';
            $relateResPro .= '</div>';
            $relateResPro .= '</div>';
        }
    }
} else {
    header("Location: index.php");
}

if (empty($relateResPro)) {
    $relateResPro = '<div class="no-related-products">';
    $relateResPro .= '<p>Không có sản phẩm yêu thích</p>';
    $relateResPro .= '</div>';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yêu thích</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="shortcut icon" href="Home-img/food-logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/favor.css">
    
</head>
<body>
<div id="notification" class="success-message" style="display: none;"></div>
        <div class="return_links">
            <a href="javascript:history.go(-1)"><i class="fa-solid fa-rotate-left"></i>Quay về</a>
            <a href="index.php"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <a href="produces-page.php"><i class="fa-solid fa-clipboard-list"></i> Menus</a>
        </div>
        <h1 class="favor_title">Yêu thích</h1>
        <main>
            <div class="relate-products-container">
                <?php
                    echo  $relateResPro;
                ?>
            </div>
            <?php
            echo '<div class="pagination">';
                if ($currentPage > 1) {
                    echo '<a href="?page=' . ($currentPage - 1) . '" class="prevBtn"><i class="fa-solid fa-angle-left"></i></a>';
                    }
                for ($i = 1; $i <= $totalPages; $i++) {
                    echo '<a href="?page=' . $i . '"';
                        if ($i == $currentPage) {
                            echo ' class="active-page"';
                            }
                            echo '>' . $i . '</a>';
                }
                if ($currentPage < $totalPages) {
                    echo '<a href="?page=' . ($currentPage + 1) . '" class="nextBtn"><i class="fa-solid fa-angle-right"></i></a>';
                    }
                echo '</div>';
            ?>
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
    <script src="js/favor.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    var notification = document.getElementById('notification');

    <?php if (isset($successMessage)) { ?>
        notification.innerText = '<?php echo $successMessage; ?>';
        notification.style.display = 'block';
        setTimeout(function () {
            notification.style.display = 'none';
        }, 3000);
    <?php } ?>
});
</script>

</body>
</html>
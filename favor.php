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
            $relateResPro .= '<button type="submit" name="delete" class="deleteFavoriteProduct btn">Xóa khỏi yêu thích</button>';
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
    <div class="relate-products-container">
        <?php
        echo  $relateResPro;
        ?>
    </div>

    <script src="js/favor.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    var notification = document.getElementById('notification');

    <?php if (isset($successMessage)) { ?>
        // Display the success message
        notification.innerText = '<?php echo $successMessage; ?>';
        notification.style.display = 'block';

        // Hide the message after 3 seconds
        setTimeout(function () {
            notification.style.display = 'none';
        }, 3000);
    <?php } ?>
});
</script>

</body>
</html>
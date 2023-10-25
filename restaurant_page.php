<?php
session_start();
include("connect.php");
if (isset($_SESSION['res_loggedin']) && $_SESSION['res_loggedin'] === true) {
    unset($_SESSION['res_loggedin']);
}
$isUserLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
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
    <nav>
        <div class="return_links">
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
    </nav>
<div class="restaurant-title"><h1>Nhà hàng</h1></div>
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
<script src="js/restaurant_page.js"></script>
</body>
</html>


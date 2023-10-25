<?php 
include("connect.php");
session_start();
if (isset($_GET['search-query'])) {
    $query = $_GET['search-query'];
    $sql = "SELECT * FROM products WHERE name LIKE '%$query%'";
    $search_result = mysqli_query($conn, $sql);
}
    $recordsPerPage = 12;
    $totalRecords = mysqli_num_rows($search_result);

$totalPages = ceil($totalRecords / $recordsPerPage);

// Calculate the current page from the URL
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Calculate the offset for the SQL query
$offset = ($currentPage - 1) * $recordsPerPage;

// Modify your SQL query
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
        <a href="cart.php" class="cart-link"><i class="fa-solid fa-bag-shopping"></i> Giỏ hàng <span id="cartBadge" class="badge"></span></a>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" ></script> 
    <script src="js/results_of_search.js"></script>
</body>
<?php 
        mysqli_close($conn);
?>
</html>

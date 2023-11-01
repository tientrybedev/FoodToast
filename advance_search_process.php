<?php
include("connect.php");
session_start();
$isUserLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
if ($isUserLoggedIn) {
$user_id = $_SESSION['user_id'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['meals'])) {
    $selectedMeals = $_POST['meals'];
}
$conditions = [];
foreach ($selectedMeals as $meal) {
    $conditions[] = "$meal = 1";
}
$mealConditions = implode(" AND ", $conditions);
$query = "SELECT * FROM products WHERE $mealConditions";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $search_product_ID = $row['product_id'];
        $search_product_name = $row['name'];
        $search_product_price = $row['price'];
        $search_product_img = $row['image_1'];
        $alreadyFavorSql = "SELECT * FROM favorite_products WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($alreadyFavorSql);
        $stmt->bind_param("is", $user_id, $search_product_ID ); 
        $stmt->execute();
        $result2 = $stmt->get_result();
        $isFavorited = $result2->num_rows > 0;
        $stmt->close();
        $queryStarRatingSql = "SELECT AVG(rating) AS avg_rating FROM ratings WHERE product_id = ?";
        $stmtdDisplayRating = mysqli_prepare($conn, $queryStarRatingSql);
        if ($stmtdDisplayRating) {
            mysqli_stmt_bind_param($stmtdDisplayRating, 's', $search_product_ID);
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
        echo'<div class="card">';
        if ($isUserLoggedIn){
            echo '<div class="like" data-user-id="' . $user_id . '" data-product-id="' . $row["product_id"] . '"><i class="fa-solid fa-heart" title="Yêu thích" ' . ($isFavorited ? 'style="text-shadow: 0 0 2px; color: var(--heart-color); transform: scale(1.2); cursor: default;"' : '') . '></i></div>';
        }
        else{
            echo '<div class="like not-logged-in " data-user-id="' . $user_id . '" data-product-id="' . $row["product_id"] . '"><i class="fa-solid fa-heart" title="Yêu thích" ' . ($isFavorited ? 'style="text-shadow: 0 0 2px; color: var(--heart-color); transform: scale(1.2); cursor: default;"' : '') . '></i></div>';
        }
        echo'<div class="fpic-container">';
        echo'<img src="' . $search_product_img  . '" class="card-img-top" alt="' . $search_product_name . '">';
        echo '</div>';
        echo'<div class="card-body">';
        echo'<h3 class="card-title">' . $search_product_name . '</h3>';
        echo '<div class="rate">';
                    echo '<p>Đánh Giá:</p>';
                    echo $userRatingDisplay;
                    echo '</div>';
        echo'<div class="price"> <p>Giá:<p> <strong>' . $search_product_price . '</strong></div>';
        echo'<div class="card-link">';
        echo '<a href="Single-product-detail.php?product_id=' .$search_product_ID . '" class="btn">Tìm hiểu thêm<i class="fa-solid fa-circle-question"></i></a>';
        if ($isUserLoggedIn){
            echo'<button class="addToCart btn" data-product-id="'. $search_product_ID .'">Thêm vào giỏ hàng <i class="fa-solid fa-cart-shopping"></i></button>';
        }else{
        echo'<button class="addToCart btn" onclick="showLoginAnnouncement(event)">Thêm vào giỏ hàng <i class="fa-solid fa-cart-shopping"></i></button>';
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';

    }

} else {
    echo '<p style="font-size:2rem; text-align: center; font-style: italic; opacity: .7;grid-column: 2 / 4;">Không tìm thấy sản phẩm phù hợp.</p>';
}
mysqli_close($conn);
?>

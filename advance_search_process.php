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

        echo'<div class="card">';
        echo'<div class="like"><i class="fa-solid fa-heart" title="Yêu thích"></i></div>';
        echo'<div class="fpic-container">';
        echo'<img src="' . $search_product_img  . '" class="card-img-top" alt="' . $search_product_name . '">';
        echo '</div>';
        echo'<div class="card-body">';
        echo'<h3 class="card-title">' . $search_product_name . '</h3>';
        echo'<div class="price"> <p>Giá:<p> <strong>' . $search_product_price . '</strong></div>';
        echo'<div class="card-link">';
        echo '<a href="Single-product-detail.php?product_id=' .$search_product_ID . '" class="btn">Tìm hiểu thêm<i class="fa-solid fa-circle-question"></i></a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo '<p style="font-size:2rem; text-align: center; font-style: italic; opacity: .7;grid-column: 2 / 4;">Không tìm thấy sản phẩm phù hợp.</p>';
}
mysqli_close($conn);
?>

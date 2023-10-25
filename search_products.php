<?php
session_start(); 
if (isset($_GET['query'])) {
    $query = $_GET['query'];
    include("connect.php");
    $sql = "SELECT * FROM products WHERE name LIKE '%$query%' LIMIT 5";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $pro_Id = $row['product_id'];
            $pro_name=$row['name'];
            $imagePath = $row['image_1'];        
            echo '<a href="Single-product-detail.php?product_id=' . $pro_Id . '" class="product_result">';
            echo '<div class="img-product-result-container">';
            echo '<img src="' . $imagePath . '" alt="' . $pro_name . '" />';
            echo '</div>';
            echo '<span>' . $pro_name . '</span>';
            echo '</a>';
        }
    } else {
        echo '<p style="font-size: 1rem; padding: 4px; font-weight: 400; font-style: italic; text-align:center;">Không tìm thấy sản phẩm nào</p>';
    }
    mysqli_close($conn);
}
?>

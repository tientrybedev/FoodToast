<?php

include("connect.php");
if (isset($_POST['delete'])) {
    $product_id = $_POST['product_id'];
    $user_id = $_POST['user_id'];
    $query = "DELETE FROM favorite_products WHERE user_id = $user_id AND product_id = '$product_id'";
    if (mysqli_query($conn, $query)) {
        header("Location: favor.php?delete_success=1");
        exit();
    } else {
        echo 'Error deleting the product.';
    }
} else {
    echo 'Invalid request.';
}
?>

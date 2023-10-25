<?php
include("connect.php"); // Include your database connection code here
session_start();

if (isset($_SESSION['user_id']) && isset($_POST['product_id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];

    // Query to delete the product from the cart
    $sqlDelete = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bind_param("is", $user_id, $product_id);

    if ($stmtDelete->execute()) {
        echo "Product removed from cart!";
    } else {
        echo "Error removing product from cart!";
    }

    $stmtDelete->close();
} else {
    echo "Invalid request!";
}
?>

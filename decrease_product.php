<?php
include("connect.php"); // Include your database connection code here
session_start();

if (isset($_SESSION['user_id']) && isset($_POST['product_id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];

    // Query to decrease the product quantity, ensuring it doesn't go below 1
    $sqlUpdate = "UPDATE cart SET quantity = GREATEST(quantity - 1, 1) WHERE user_id = ? AND product_id = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("is", $user_id, $product_id);

    if ($stmtUpdate->execute()) {
        echo "Quantity updated successfully!";
    } else {
        echo "Error updating quantity!";
    }

    $stmtUpdate->close();
} else {
    echo "Invalid request!";
}

?>

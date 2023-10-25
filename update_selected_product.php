<?php
include("connect.php"); // Include your database connection code here
session_start();

if (isset($_SESSION['user_id']) && isset($_POST['product_id']) && isset($_POST['selected'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $selected = $_POST['selected'];

    // Query to update the selected status of the product
    $sqlUpdate = "UPDATE cart SET selected = ? WHERE user_id = ? AND product_id = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("iis", $selected, $user_id, $product_id);

    if ($stmtUpdate->execute()) {
        echo "Selected status updated successfully!";
    } else {
        echo "Error updating selected status!";
    }

    $stmtUpdate->close();
} else {
    echo "Invalid request!";
}

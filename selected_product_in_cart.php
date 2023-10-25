<?php
include("connect.php"); // Include your database connection code here
session_start();

if (isset($_SESSION['user_id']) && isset($_POST['product_id']) && isset($_POST['checked'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $isChecked = $_POST['checked']; // Retrieve the "checked" status

    $sqlUpdateCheckbox = "UPDATE cart SET selected = ? WHERE user_id = ? AND product_id = ?";
    $stmtUpdateCheckbox = $conn->prepare($sqlUpdateCheckbox);
    $stmtUpdateCheckbox->bind_param("iis", $isChecked, $user_id, $product_id);

    if ($stmtUpdateCheckbox->execute()) {
        echo "Updated successfully!";
    } else {
        echo "Error updating the selected status!";
    }

    $stmtUpdateCheckbox->close();
} else {
    echo "Invalid request!";
}


?>
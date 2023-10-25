<?php
session_start();
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["product_id"])) {
    $product_id = $_POST["product_id"];

    // Get the image paths for the product
    $getImagesSql = "SELECT image_1, image_2, image_3, image_4 FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($getImagesSql);
    $stmt->bind_param("s", $product_id);
    
    if (!$stmt->execute()) {
        die("Error executing getImagesSql: " . $stmt->error);
    }
    
    $stmt->bind_result($image1, $image2, $image3, $image4);
    $stmt->fetch();
    $stmt->close();

    // Delete the product record from the database
    $deleteProductSql = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($deleteProductSql);
    $stmt->bind_param("s", $product_id);

    if (!$stmt->execute()) {
        die("Error executing deleteProductSql: " . $stmt->error);
    }

    if ($stmt->affected_rows > 0) {
        // Delete associated images from the server
        unlink($image1);
        unlink($image2);
        unlink($image3);
        unlink($image4);

        $_SESSION["success_message"] = "Xoá Sản Phẩm thành công!";
    } else {
        $_SESSION["error_messages"] = ["Xóa sản phẩm thất bại"];
    }

    $stmt->close();
} else {
    $_SESSION["error_messages"] = ["Invalid request."];
}

$conn->close();

// Redirect back to res_home.php
header("Location: res_home.php");
exit();
?>

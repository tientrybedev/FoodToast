<?php
session_start();
include("connect.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the product ID from the form
    $product_id = $_POST["product_id"];
    $new_name = $_POST["edit-name"];
    $new_description = $_POST["edit-description"];
    $new_price = $_POST["edit-price"];
    $new_quantity =$_POST["edit-quantity"];
    $updateSql = "UPDATE products SET name = ?, description = ?, price = ?, quantity = ? WHERE product_id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("ssdis", $new_name, $new_description, $new_price, $new_quantity, $product_id);

    if ($stmt->execute()) {
        $_SESSION["success_message"] = "Thay đổi thành công.";
        header("Location: res_home.php");
        exit();
    } else {
        $_SESSION["error_messages"] = ["Thay đổi thất bại. <br> Hãy thử lại"];
        header("Location: res_home.php");
        
    }
    
    $stmt->close();
}

// Close the database connection
$conn->close();

// session_start();
// include("connect.php");

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $product_id = $_POST["product_id"]; // Get the product_id

//     // Other field values here (name, description, price, quantity)

//     // Update the product using the product_id
//     $updateSql = "UPDATE products SET name = ?, description = ?, price = ?, quantity = ? WHERE product_id = ?";
//     $stmt = $conn->prepare($updateSql);
//     $stmt->bind_param("ssdii", $new_name, $new_description, $new_price, $new_quantity, $product_id);

//     if ($stmt->execute()) {
//         $_SESSION["success_message"] = "Thay đổi thành công.";
//     } else {
//         $_SESSION["error_messages"] = ["Thay đổi thất bại. Hãy thử lại"];
//     }

//     $stmt->close();
// }

// // Close the database connection
// $conn->close();

// // Redirect back to the previous page
// header("Location: res_home.php");
// exit();


?>

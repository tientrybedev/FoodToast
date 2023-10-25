<?php
include("connect.php");
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $product_id = $_POST['product_id'];

    // Check if the product is already in the favorites
    $sqlCheck = "SELECT * FROM favorite_products WHERE user_id = ? AND product_id = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("is", $user_id, $product_id);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows === 0) {
        // If not in favorites, insert it
        $sqlInsert = "INSERT INTO favorite_products (user_id, product_id) VALUES (?, ?)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bind_param("is", $user_id, $product_id);

        if ($stmtInsert->execute()) {
            $response = ["status" => "success", "message" => "Đã thêm vào yêu thích"];
        } else {
            $response = ["status" => "error", "message" => "Thêm thất bại"];
        }

        $stmtInsert->close();
    } else {
        $response = ["status" => "error", "message" => "Sản phẩm đã trong yêu thích."];
    }

    $stmtCheck->close();
} else {
    $response = ["status" => "error", "message" => "Lỗi"];
}

header("Content-Type: application/json");
echo json_encode($response);
?>

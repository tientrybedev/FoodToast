<?php
include("connect.php");
session_start();
if (isset($_SESSION['user_id']) && isset($_POST['product_id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $sqlCheck = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("is", $user_id, $product_id);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        $rowCheck = $resultCheck->fetch_assoc();
        $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : $rowCheck['quantity'] + 1;
        $sqlUpdate = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("iis", $quantity, $user_id, $product_id);
        if ($stmtUpdate->execute()) {
            $response = ["status" => "success", "message" => "Đã thêm sản phẩm"];
        } else {
            $response = ["status" => "fail", "message" => "Có lỗi xảy ra"];
        }
        $stmtUpdate->close();
    } else {
        // Product doesn't exist in the cart, so add it
        $sql = "SELECT * FROM products WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $price = $row['price'];
            $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;
            $sqlCart = "INSERT INTO cart (user_id, product_id, price, quantity) VALUES (?, ?, ?, ?)";
            $stmtCart = $conn->prepare($sqlCart);
            $stmtCart->bind_param("isdi", $user_id, $product_id, $price, $quantity);
            if ($stmtCart->execute()) {
                $response = ["status" => "success", "message" => "Đã thêm sản phẩm"];
            } else {
                $response = ["status" => "fail", "message" => "Error adding product to cart"];
            }
            $stmtCart->close();
        } else {
            $response = ["status" => "fail", "message" => "Lỗi"];
        }
        $stmt->close();
    }
}
header('Content-Type: application/json');
echo json_encode($response);
?>
<?php
include("connect.php");
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $order_id = $_POST["order_id"];
    $product_id = $_POST["product_id"];
    $new_status = $_POST["new_status"];

    // Perform the SQL update
    $updateOrderStatusSql = "UPDATE order_details SET order_status = ? WHERE order_id = ? AND product_id = ?";
    $stmt = $conn->prepare($updateOrderStatusSql);
    $stmt->bind_param("sis", $new_status, $order_id, $product_id);

    if ($stmt->execute()) {
        $response = array('status' => 'success', 'message' => 'Cập nhật thành công');
    } else {
        $response = array('status' => 'error', 'message' => 'Cập nhật thất bại');
    }
    header('Content-Type: application/json');
    echo json_encode($response);

    $stmt->close();
} else {
    echo "Invalid request method.";
}
?>

<?php
include("connect.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enteredRestaurantName = $_POST["Lg_restaurantName"];
    $enteredPassword = $_POST["Lg_password"];

    $getUserDataSql = "SELECT restaurant_id, password FROM restaurants WHERE restaurant_name = ?";
    $stmt = $conn->prepare($getUserDataSql);
    $stmt->bind_param("s", $enteredRestaurantName);
    $stmt->execute();
    $stmt->bind_result($restaurant_id, $hashedPasswordInDb);
    
    if ($stmt->fetch()) {
        if (password_verify($enteredPassword, $hashedPasswordInDb)) {
            session_start();
            $_SESSION['restaurant_id'] = $restaurant_id;
            $_SESSION['restaurant_name'] = $enteredRestaurantName;
            $_SESSION['res_loggedin'] = true;
            $response = array("status" => "success", "message" => "Đăng nhập thành công.");
            echo json_encode($response);
        } else {
            $response = array("status" => "fail", "message" => "Sai tên đăng nhập hoặc mật khẩu.");
            echo json_encode($response);
        }
    } else {
        $response = array("status" => "fail", "message" => "Sai tên đăng nhập hoặc mật khẩu.");
        echo json_encode($response);
    }
    $stmt->close();
}
$conn->close();
?>

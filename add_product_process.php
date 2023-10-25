<?php
session_start();
include("connect.php");
if (!isset($_SESSION['res_loggedin']) || $_SESSION['res_loggedin'] !== true) {
    header("Location: res_register.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST["product_id"];
    $name = $_POST["name"];
    $description = $_POST["description"];
    $breakfast = isset($_POST["breakfast"]) ? 1 : 0;
    $lunch = isset($_POST["lunch"]) ? 1 : 0;
    $dinner = isset($_POST["dinner"]) ? 1 : 0;
    $dessert = isset($_POST["dessert"]) ? 1 : 0;
    $snacks = isset($_POST["snacks"]) ? 1 : 0;
    $drinks = isset($_POST["drinks"]) ? 1 : 0;
    $rices = isset($_POST["rices"]) ? 1 : 0;
    $soup = isset($_POST["soup"]) ? 1 : 0;
    $diet = isset($_POST["diet"]) ? 1 : 0;
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    $imagePaths = [];
    foreach ($_FILES["image"]["tmp_name"] as $index => $tmpName) {
        $imageFileName = $_FILES["image"]["name"][$index];
        $targetDir = "res_uploads/"; 
        $targetPath = $targetDir . $imageFileName;
        if (file_exists($targetPath)) {
            $_SESSION["error_messages"] = ["File đã tồn tại."];
            header("Location: res_home.php");
            exit();
        }
        if ($_FILES["image"]["size"][$index] > 100000 * 1024) {
            $_SESSION["error_messages"] = ["File ảnh lớn hơn quy định"];
            header("Location: res_home.php");
            exit();
        }
        $allowedExtensions = array("jpg", "jpeg", "png");
        $imageFileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
        if (!in_array($imageFileType, $allowedExtensions)) {
            $_SESSION["error_messages"] = ["Sai dạng file ảnh"];
            header("Location: res_home.php");
            exit();
        }
        if (move_uploaded_file($tmpName, $targetPath)) {
            $imagePaths[] = $targetPath;
        } else {
            $_SESSION["error_messages"] = ["Đăng file ảnh thất bại"];
            header("Location: res_home.php");
            exit();
        }
    }
    $insertProductSql = "INSERT INTO products (product_id, restaurant_id, name, description, breakfast, lunch, dinner, dessert, snacks, drinks, rices, soup, diet, price, quantity, image_1, image_2, image_3, image_4) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertProductSql);
    $stmt->bind_param("ssssssssssdidsdssss", $product_id, $_SESSION['restaurant_id'], $name, $description, $breakfast, $lunch, $dinner, $dessert, $snacks, $drinks, $rices, $soup, $diet, $price, $quantity, $imagePaths[0], $imagePaths[1], $imagePaths[2], $imagePaths[3]);
    if ($stmt->execute()) {
        $_SESSION["success_message"] = "Đăng sản phẩm thành công!";
    } else {
        $_SESSION["error_messages"] = ["Đăng sản phẩm thất bại. <br> Hãy thử lại"];
    }
    $stmt->close();
    $conn->close();
    header("Location: res_home.php");
} else {
    header("Location: res_home.php");
}
?>

<?php
include("connect.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUsername = $_POST['username'];
    $newPhone = $_POST['phone'];
    $newEmail = $_POST['email'];
    $userId = $_SESSION['user_id'];

    // Check trùng tên người dùng
    $checkUsernameSql = "SELECT * FROM users WHERE username = ? AND user_id != ?";
    $stmt = $conn->prepare($checkUsernameSql);
    $stmt->bind_param("si", $newUsername, $userId);
    $stmt->execute();
    $resultUsername = $stmt->get_result();

    // check trùng Email
    $checkEmailSql = "SELECT * FROM users WHERE email = ? AND user_id != ?";
    $stmt = $conn->prepare($checkEmailSql);
    $stmt->bind_param("si", $newEmail, $userId);
    $stmt->execute();
    $resultEmail = $stmt->get_result();

    // check trùng SĐT
    $checkPhoneSql = "SELECT * FROM users WHERE phone = ? AND user_id != ?";
    $stmt = $conn->prepare($checkPhoneSql);
    $stmt->bind_param("si", $newPhone, $userId);
    $stmt->execute();
    $resultPhone = $stmt->get_result(); 

    $errorMes=[];
    // Check if any of the data already exists in the database
    if ($resultUsername->num_rows > 0) {
        $errorMes[] = 'Tên người dùng đã tồn tại';
        $response = ["status" => "fail", "message" => $errorMes];
    } elseif ($resultEmail->num_rows > 0) {
        $errorMes[] = 'Email đã đăng ký.';
        $response = ["status" => "fail", "message" => $errorMes];
    } elseif ($resultPhone->num_rows > 0) {
        $errorMes[] = 'Số điện thoại đã đăng ký.';
        $response = ["status" => "fail", "message" => $errorMes];
    } else {

$response = []; 
if ($_FILES['file']['error'] === 0) {
    $targetDir = "uploads/";
    $uniqueFilename = uniqid() . '_' . $_FILES['file']['name'];
    $targetFile = $targetDir . $uniqueFilename;
    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
        $_SESSION['profile_image'] = $targetFile;
        $updateImageSql = "UPDATE users SET profile_image = ? WHERE user_id = ?";
        $stmt = $conn->prepare($updateImageSql);
        $stmt->bind_param("si", $targetFile, $userId);
        if ($stmt->execute()) {
            $response["newImage"] = $targetFile; // Include the new image in the response
        } else {
            $errorMes[] = 'Thay đổi ảnh thất bại';
            $response = ["status" => "fail", "message" => $errorMes];
        }
    }
}
$updateSql = "UPDATE users SET username = ?, phone = ?, email = ? WHERE user_id = ?";
$stmt = $conn->prepare($updateSql);
$stmt->bind_param("sssi", $newUsername, $newPhone, $newEmail, $userId);

if ($stmt->execute()) {
    if (!isset($response["status"])) {
        $response["status"] = "success";
        $response["message"] = "Thay đổi thành công";
    }
    $response["newUsername"] = $newUsername;
    $response["newPhone"] = $newPhone;
    $response["newEmail"] = $newEmail;
} else {
    error_log("Error updating record: " . $stmt->error);
    $response = ["status" => "fail", "message" => "Error updating record"];
}
        $stmt->close();
    }
    $conn->close();
    
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>


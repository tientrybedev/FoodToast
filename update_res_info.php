<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newRestaurantName = $_POST['name'];
    $newEmail = $_POST['email'];
    $newPhone = $_POST['phone'];
    $newDescription = $_POST['description'];
    $restaurantId = $_SESSION['restaurant_id'];

    // Establish a database connection (include your connect.php file here)
    include("connect.php");

    // Check if the new restaurant name already exists (excluding the current restaurant)
    $checkNameSql = "SELECT * FROM restaurants WHERE restaurant_name = ? AND restaurant_id != ?";
    $stmt = $conn->prepare($checkNameSql);
    $stmt->bind_param("si", $newRestaurantName, $restaurantId);
    $stmt->execute();
    $resultName = $stmt->get_result();

    // Check if the new email already exists (excluding the current restaurant)
    $checkEmailSql = "SELECT * FROM restaurants WHERE email = ? AND restaurant_id != ?";
    $stmt = $conn->prepare($checkEmailSql);
    $stmt->bind_param("si", $newEmail, $restaurantId);
    $stmt->execute();
    $resultEmail = $stmt->get_result();

    // Check if the new phone already exists (excluding the current restaurant)
    $checkPhoneSql = "SELECT * FROM restaurants WHERE phone = ? AND restaurant_id != ?";
    $stmt = $conn->prepare($checkPhoneSql);
    $stmt->bind_param("si", $newPhone, $restaurantId);
    $stmt->execute();
    $resultPhone = $stmt->get_result();

    // Check if any of the data already exists in the database
    $errorMessages = array();
    if ($resultName->num_rows > 0) {
        $errorMessages[] = "Tên nhà hàng đã tồn tại. <br>xin hãy chọn tên khác.<br>";
    }
    if ($resultEmail->num_rows > 0) {
        $errorMessages[] = "Email đã đăng ký. <br>xin hãy chọn email khác.<br>";
    }
    if ($resultPhone->num_rows > 0) {
        $errorMessages[] = "Số điện thoại đã đăng ký. <br>xin hãy chọn số khác.<br>";
    }

    if (empty($errorMessages)) {
        // File upload handling
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {
            $targetDir = "res_brands/"; // Directory where you want to store uploaded images
            $targetFile = $targetDir . basename($_FILES["image"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Check file size (1000KB limit)
            if ($_FILES["image"]["size"] > 1000 * 1024) {
                $errorMessages[] = "Ảnh vượt quá 1000KB.";
                $_SESSION["error_messages"] = $errorMessages;
                header("Location: res_home.php");
                exit;
            }

            // Allow only specific image file types (e.g., jpg, jpeg, png)
            $allowedExtensions = array("jpg", "jpeg", "png");
            if (!in_array($imageFileType, $allowedExtensions)) {
                $errorMessages[] = "Sai loại ảnh. Xin vui lòng tải lên ảnh JPG hoặc PNG.";
                $_SESSION["error_messages"] = $errorMessages;
                header("Location: res_home.php");
                exit;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk === 0) {
                $_SESSION["error_messages"] = $errorMessages;
                header("Location: res_home.php");
                exit;
            } else {
                // Move the uploaded file to the target directory
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                    // Successfully uploaded the image, now update the image path in the database
                    $imagePath = $targetFile;

                    // Prepare and execute the SQL update query for the image field
                    $updateImageSql = "UPDATE restaurants SET brand_image = ? WHERE restaurant_id = ?";
                    $stmtImage = $conn->prepare($updateImageSql);
                    $stmtImage->bind_param("si", $imagePath, $restaurantId);
                    $stmtImage->execute();

                    // Close the prepared statement for image update
                    $stmtImage->close();
                } else {
                    $errorMessages[] = "Up ảnh thất bại.";
                    $_SESSION["error_messages"] = $errorMessages;
                    header("Location: res_home.php");
                    exit;
                }
            }
        }

        // Update session variables
        $_SESSION['restaurant_name'] = $newRestaurantName;
        $_SESSION['email'] = $newEmail;
        $_SESSION['phone'] = $newPhone;

        // Prepare and execute the SQL update query for non-image fields
        $updateSql = "UPDATE restaurants SET restaurant_name = ?, email = ?, phone = ?, description = ? WHERE restaurant_id = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("ssssi", $newRestaurantName, $newEmail, $newPhone, $newDescription, $restaurantId);

        if ($stmt->execute()) {
            // Update successful
            $_SESSION["success_message"] = "Thay đổi thành công.";
            header("Location: res_home.php");
            exit();
        } else {
            // Error updating record
            echo "Error updating record: " . $stmt->error;
        }

        // Close the prepared statement
        $stmt->close();
    }

    // Close the database connection
    $conn->close();

    // Set the error message in the session and redirect
    $_SESSION["error_messages"] = $errorMessages;
    header("Location: res_home.php");
    exit;
}
?>

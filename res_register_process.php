<?php
session_start(); 
include("connect.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $restaurantName = htmlspecialchars($_POST["restaurantName"]);
    $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
    $phone = preg_replace("/[^0-9]/", "", $_POST["phone"]); // Remove non-numeric characters
    $description = htmlspecialchars($_POST["description"]);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    $checkQuery = "SELECT * FROM restaurants WHERE restaurant_name = ? OR email = ? OR phone = ?";
    $stmtCheck = $conn->prepare($checkQuery);
    $stmtCheck->bind_param("sss", $restaurantName, $email, $phone);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();

    if ($result->num_rows > 0) {
        $response = ["status" => "fail"];
        $messages = [];

        while ($row = $result->fetch_assoc()) {
            if ($row["restaurant_name"] === $restaurantName) {
                $messages[] = "Tên nhà hàng đã tồn tại";
            }
            if ($row["email"] === $email) {
                $messages[] = "Email đã được đăng ký";
            }
            if ($row["phone"] === $phone) {
                $messages[] = "SĐT đã được đăng ký";
            }
        }
        $response["message"] = implode(", ", $messages);
        echo json_encode($response);
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        if (isset($_FILES["file"]) && $_FILES["file"]["error"] === 0) {
            $targetDir = "res_brands/"; // Directory where you want to store uploaded images
            $targetFile = $targetDir . basename($_FILES["file"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            if (file_exists($targetFile)) {
                $response = ["status" => "fail", "message" => "Ảnh đã tồn tại"];
                echo json_encode($response);
            } else {
                if ($_FILES["file"]["size"] > 1000 * 1024) {
                    $response = ["status" => "fail", "message" => "Ảnh vượt quá 1000KB"];
                    echo json_encode($response);
                } else {
                    // Allow only specific image file types (e.g., jpg, jpeg, png)
                    $allowedExtensions = array("jpg", "jpeg", "png");
                    if (!in_array($imageFileType, $allowedExtensions)) {
                        $response = ["status" => "fail", "message" => "Sai loại ảnh"];
                        echo json_encode($response);
                    } else {
                        // Move the uploaded file to the target directory
                        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
                            
                            $imagePath = $targetFile;

                            // Insert the data into the database using prepared statements
                            $stmt = $conn->prepare("INSERT INTO restaurants (restaurant_name, email, phone, description, password, brand_image) VALUES (?, ?, ?, ?, ?, ?)");
                            $stmt->bind_param("ssssss", $restaurantName, $email, $phone, $description, $hashedPassword, $imagePath);

                            $response = ["status" => "fail"]; 

                            if ($stmt->execute()) {
                                $response = ["status" => "success", "message" => "Đăng ký thành công"];
                            }
                            echo json_encode($response);
                        } else {
                            $response = ["status" => "fail", "message" => "Up ảnh thất bại"];
                            echo json_encode($response);
                        }
                    }
                }
            }
        } else {
            $response = ["status" => "fail", "message" => "Lỗi khi tải lên ảnh"];
            echo json_encode($response);
        }
    }
    $stmtCheck->close();
}

// Close the database connection
$conn->close();
?>


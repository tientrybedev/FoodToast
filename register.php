<?php
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];

    $checkSql = "SELECT * FROM users WHERE username = ? OR email = ? OR phone = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("sss", $username, $email, $phone);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    while ($row = $checkResult->fetch_assoc()) {
        if ($row["username"] == $username) {
            $matchedFields[] = "Username";
        }
        if ($row["email"] == $email) {
            $matchedFields[] = "Email";
        }
        if ($row["phone"] == $phone) {
            $matchedFields[] = "Phone";
        }
    }

    if (!empty($matchedFields)) {
        $errorText = "Đã có : " . implode(", ", $matchedFields) . " Trong cơ sở dữ liệu";
        echo '<div class="error-message">' . $errorText . '</div>';
    } else {
        $date = $_POST["date"];
        $password = $_POST["password"];
        $confirmPassword = $_POST["confirmPassword"];
        $gender = $_POST["gender"];

        if ($password !== $confirmPassword) {
            echo '<div class="error-message">Mật khẩu không chính xác</div>';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["file"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            if (file_exists($target_file)) {
                echo '<div class="error-message">ảnh đã tồn tại</div>';
                $uploadOk = 0;
            }

            if ($_FILES["file"]["size"] > 1000 * 1024) {
                echo '<div class="error-message">File size is too large. Please upload a file under 120 KB.</div>';
                $uploadOk = 0;
            }

            $allowedExtensions = array("jpg", "jpeg", "png");

            if (!in_array($imageFileType, $allowedExtensions)) {
                echo '<div class="error-message">Lỗi ảnh.</div>';
                $uploadOk = 0;
            }

            if ($uploadOk === 0) {
                echo '<div class="error-message">Đăng ảnh thất bại.</div>';
            } else {
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                    $stmt = $conn->prepare("INSERT INTO users (username, email, phone, birthday, password, gender, profile_image) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssssss", $username, $email, $phone, $date, $hashedPassword, $gender, $target_file);

                    if ($stmt->execute()) {
                        echo 
                        '<div class="success-message">
                            <div class="redirect-circle">
                                <div class="tick"></div>
                            </div>  
                            <h2 class="s-header">Đăng ký thành công.</h2>
                            <div class="back-btn"> 
                                <a href="login.php">Trở lại đăng nhập</a>
                            </div>
                        </div>';
                    } else {
                        echo '<div class="error-message">Error: ' . $stmt->error . '</div>';
                    }
                    $stmt->close();
                } else {
                    echo '<div class="error-message">Image upload failed. Please check the file and try again.</div>';
                }
            }
        }
    }

    $checkStmt->close();
    $conn->close();
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/register.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="shortcut icon" href="Home-img/food-logo.png" type="image/x-icon">
    <script src="https://unpkg.com/scrollreveal"></script>
    <title>Đăng ký</title>
</head>
<body>
    <div class="all">
    <div class="container">
        <a href="login.php" class="backLogin"><i class="fa-solid fa-left-long"></i>Đăng nhập</a>
        <h2 class="title">Đăng ký</h2>
        <form action="register.php" method="post" id="registerForm" enctype="multipart/form-data" onsubmit="return validateForm();">
            <div class="user-detail">
                <div class="user-input-field">
                    <i class="fa-solid fa-circle-user" style="color: #539165;"></i>
                    <label for="username">Tên đăng nhập</label>
                    <input type="text" class="username-input" id="username" name="username" placeholder="username" required>
                </div>
                <div class="user-input-field">
                    <i class="fa-solid fa-envelope" style="color: #EE9322;"></i>
                    <label for="email">Địa chỉ Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="user-input-field">
                    <i class="fa-solid fa-phone" style="color: #0075F6;"></i>
                    <label for="phone">số điện thoại</label>
                    <input type="tel" id="phone" name="phone"  oninput="this.value = this.value.replace(/\D/g, '')" maxlength="10">
                </div>
                <div class="user-input-field">
                    <i class="fa-solid fa-cake-candles" style="color:#D789D7;"></i>
                    <label for="date">Ngày sinh</label>
                    <input type="date" id="date" name="date" min="1800-01-01" >
                </div>
                <div class="user-input-field">
                    <i class="fa-solid fa-image" style="color: #066dac;"></i>
                    <label for="file">Chọn ảnh đại diện</label>
                    <input type="file" id="file" name="file" accept="image/jpeg, image/jpg, image/png" onchange="checkFileSize(this)">
                </div>
                <div class="user-input-field">
                    <i class="fa-solid fa-key" style="color: #E9B824;"></i>
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" required autocomplete="off">
                </div>
                <div class="user-input-field">
                    <i class="fa-solid fa-key" style="color: #E9B824;"></i>
                    <label for="confirmPassword">Xác nhận mật khẩu</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required autocomplete="off"> 
                </div>
                <div class="Gender">
                    <p><i class="fa-solid fa-venus-mars"></i> Giới tính</p>
                    <div class="input-field">
                        <input type="radio" id="male" name="gender" value="male" checked>
                        <label for="male">Nam</label>
                    </div>
                    <div class="input-field">
                        <input type="radio" id="female" name="gender" value="female">
                        <label for="female">Nữ</label>
                    </div>
                    <div class="input-field">
                        <input type="radio" id="more" name="gender" value="more">
                        <label for="more">Khác</label>
                    </div>
                </div>
                <div class="btns">
                    <button type="reset">Xóa</button>
                    <button type="submit" name="submit">Đăng ký</button>
                </div>
            </div>
        </form>
        <div id="alert-container" class="alert-container"></div>
    </div>
    <div class="advertise">
        <div class="item item1">
            <img src="Home-img/logo-FT-AD.png" alt="">
        </div>
        <div class="item item2">
            <img src="Home-img/ad1.jpg" alt="">
        </div>
        <div class="item item3">
            <img src="Home-img/ad2.jpg" alt="">
        </div>
    </div>
    </div>
    <script src="js/register.js"></script>
</body>
</html>
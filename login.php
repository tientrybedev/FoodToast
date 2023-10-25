<?php
session_start();
include("connect.php");
if (isset($_SESSION['res_loggedin']) && $_SESSION['res_loggedin'] === true) {
    unset($_SESSION['res_loggedin']);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the user input
    $enteredUsername = $_POST["username"];
    $enteredPassword = $_POST["password"];

    $getUserDataSql = "SELECT user_id, password, email, phone, profile_image FROM users WHERE username = ?";
    $stmt = $conn->prepare($getUserDataSql);
    $stmt->bind_param("s", $enteredUsername);
    $stmt->execute();
    $stmt->bind_result($user_id, $hashedPasswordInDb, $email, $phone, $profileImage);
    
    if ($stmt->fetch()) {
        // First, check if the entered password matches the hashed password
        if (password_verify($enteredPassword, $hashedPasswordInDb)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['loggedin'] = true;

            // Redirect the user to index.php or wherever you want
            header("Location: index.php");
            exit();
        } else {
            // Passwords do not match
            echo '<div class="error-message">Sai tên đăng nhập hoặc mật khẩu. <br> Vui lòng thử lại</div>';
        }
    } else {
        // Username not found in the database
        echo  '<div class="error-message">Sai tên đăng nhập hoặc mật khẩu. <br> Vui lòng thử lại</div>';
    }
    
    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="Login-img/food-logo.png" type="image/x-icon">
    <script src="https://unpkg.com/scrollreveal"></script>
    <title>Login</title>
</head>
<body>
    <div class="all">
    <div id="responseMessage"></div>
        <div class="content-container">
            <div class="left-box">
                <div class="log-txt2">
                    <h2 class="top-txt"></h2>
                </div>
                <div class="slider-container">
                    <div class="slide">
                        <img src="Login-img/ManySpe.png" alt="">
                        <img src="Login-img/FastDe.png" alt="">
                        <img src="Login-img/BestPri.png" alt="">
                        <img src="Login-img/Manyopts.png" alt="">
                    </div>
                </div>
                <div class="log-txt1">
                    <h2>FOODTOAST</h2>
                    <p>Hãy ăn uống lành mạnh và tận hưởng niềm vui!</p>
                    <p><i>Created and developed by Tien</i></p>
                </div>
            </div>
            <div class="right-box">
                <h1 class="login-header">Đăng nhập</h1>
                <div class="field-form">
                <form action=" " method="POST" id="loginForm" >
                    <input class="form-input" type="text" id="username" name="username" placeholder=" " autocomplete="on" required>
                    <label class="form-label" for="username">Username</label>
                    <i class="fa-solid fa-circle-user fa-xl"></i>
                    <br>
                    <input class="password-input" type="password" id="password" placeholder=" " name="password" autocomplete="off" required>
                    <label class="password-label" for="password">Password</label>
                    <i class="fa-solid fa-key fa-xl"></i>
                    <div class="checkbx-box">
                        <div class="checkbox">
                            <input type="checkbox" value="check" id="check">
                            <label for="check">Ghi nhớ đăng nhập</label>
                        </div>
                        <a href="">Quên mật khẩu?</a>
                    </div>
                    <button class="login-btn" type="submit">Đăng nhập</button>
                </form>
                </div>
                <div class="Ask">
                    <p>Chưa có tài khoản? </p> <a href="register.php">Đăng ký ngay</a>
                </div>   
                <p class="Ask2"> hoặc </p>        
                <div class="social-btn">
                    <div class="btn" id="fb-btn">
                        <div class="icon" ><i class="fa-brands fa-facebook fa-xl" style="color: #326ad0;"></i></div>
                        <span>Facebook</span>
                    </div>
                    <div class="btn" id="gg-btn">
                        <div class="icon"><i class="fa-brands fa-google fa-xl"></i></div>
                        <span>Google</span>
                    </div>
                    <div class="btn" id="insta-btn">
                        <div class="icon" ><i class="fa-brands fa-instagram fa-xl"></i></div>
                        <span>Instagram</span>
                    </div>    
                </div>        
            </div>
        </div>
    </div>
        <script src="js/login.js"></script>
</body>
</html>
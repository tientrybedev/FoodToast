<?php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    unset($_SESSION['loggedin']); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/res_register.css">
    <link rel="shortcut icon" href="Home-img/food-logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <script src="https://unpkg.com/scrollreveal"></script>
    <title>Đăng nhập/Đăng ký Nhà Hàng</title>
</head>
<body>
    <div class="all">
        <div class="toast" id="toastMessage"></div>
        <div class="slider">
            <div class="login-container">
                <a href="index.php"  class="returnBtn">Trang chủ</a>
                <button id="registerBtn" class="registerBtn">Đăng ký</button>
                <div class="error-toast" id="errorToast"></div>
                <h1 class="title">Đăng Nhập</h1>
                <form action="res_login_process.php" method="post" id="resLoginForm" class="res-login-form">
                    <div class="st_input_detail">
                        <input type="text" id="Lg_restaurantName" name="Lg_restaurantName" autocomplete="on" required >
                        <label for="Lg_restaurantName">Tên nhà hàng</label>
                        <i class="fa-solid fa-shop"></i>
                    </div>
                    <div class="st_input_detail">
                        <input type="password" id="Lg_password" name="Lg_password" autocomplete="off" required>
                        <label for="Lg_password">Mật khẩu</label>
                        <i class="fa-solid fa-key"></i>
                    </div>
                    <div class="Ask">
                        <div class="checkAsk">
                            <div class="checkbox-wrapper-40">
                                <label for="check">
                                    <input type="checkbox" name="check" id="check"/>
                                    <span class="checkbox"></span>  
                                    <span>Ghi Nhớ Đăng Nhập</span>
                                </label>
                            </div>
                        </div>
                        <i><b><a href="/forgot-password">Quên mật khẩu</a></b></i>
                    </div>
                    <div class="btn-section">
                        <button class="btn" type="submit">Đăng nhập</button>
                    </div>
                </form>
                <div class="advertise-section">
                    <div class="ad-container">
                        <div class="logo">
                            <img src="Home-img/logo-FT-AD.png" alt="">
                        </div>   
                        <div class="aditem_ad">
                            <div class="aditem">
                                <img src="Home-img/ad4.jpg" alt="">
                            </div>   
                            <div class="aditem">
                                <img src="Home-img/ad3.jpg" alt="">
                            </div>   
                            <div class="aditem">
                                <img src="Home-img/ad5.jpg" alt="">
                            </div>   
                        </div>
                    </div>
                </div>     
            </div>
            <div class="register-container">
                <button id="loginBtn" class="loginBtn" >Đăng nhập</button>
                <h1 class="title">Đăng Ký</h1>
                <form action="res_register_process.php" method="post" id="resRegisterForm" class="res-register-form" enctype="multipart/form-data" >
                    <div class="vendor_input_detail">
                        <label for="restaurantName"><i class="fa-solid fa-store" style="color: #188119;"></i>Tên nhà hàng</label>
                        <input type="text" id="restaurantName" name="restaurantName" placeholder="Trên 4 chữ" required>
                        <div class="error-message" id="restaurantNameError"></div>
                    </div>
                    <div class="vendor_input_detail">
                        <label for="email"><i class="fa-solid fa-envelope" style="color: #bddf16;"></i>Email</label>
                        <input type="email" id="email" name="email" placeholder="example@gmail.com" required>
                        <div class="error-message" id="emailError"></div>
                    </div>
                    <div class="vendor_input_detail">
                        <label for="phone"><i class="fa-solid fa-phone" style="color: #1952b3;"></i>Số điện thoại</label>
                        <input type="tel" id="phone" name="phone" placeholder="SĐT phải là 10 chữ số" oninput="this.value = this.value.replace(/\D/g, '')" maxlength="10" required>
                        <div class="error-message" id="phoneError"></div>
                    </div>
                    <div class="vendor_input_detail">
                        <label for="file"><i class="fa-solid fa-image" style="color: #066dac;"></i>ảnh đại diện</label>
                        <input type="file" id="file" name="file" accept="image/*">
                        <div class="error-message" id=imgError></div>
                    </div>
                    <div class="vendor_input_detail">
                        <label for="description"><i class="fa-solid fa-pen" style="color: #116da6;"></i>Mô tả</label>
                        <textarea id="description" name="description" placeholder="Miêu tả về nhà hàng..."></textarea>
                        <div class="error-message" id=descriptionError></div>
                    </div>
                    <div class="vendor_input_detail">
                        <label for="password"><i class="fa-solid fa-key" style="color: #d6d01f;"></i>Mật khẩu</label>
                        <input type="password" id="password" name="password" autocomplete="off" required>
                        <div class="error-message" id=passwordError></div>
                    </div>
                    <div class="vendor_input_detail">
                        <label for="confirmPassword"><i class="fa-solid fa-key" style="color: #d6d01f;"></i>Xác nhận mật khẩu</label>
                        <input type="password" id="confirmPassword" name="confirmPassword"  autocomplete="off" required >
                        <div class="error-message" id=cpasswordError></div>
                    </div>
                    <div class="btn-section">
                        <button class="btn" type="reset">Xóa</button>
                        <button class="btn" type="submit" value="Register" >Đăng ký</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
        <script>
            ScrollReveal({ reset: false });
            ScrollReveal().reveal('.all', { duration: 950, delay: 500 , distance: '30px', origin: 'right'});
            ScrollReveal().reveal(' .login-container', { duration: 950, delay: 1000 , distance: '30px', origin: 'bottom'});
        </script>
    <script src="js/res_register.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" ></script>
</body>
</html>
<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="Home-img/food-logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css/thank_you.css">
    <title>Cảm ơn</title>
</head>
<body>
    <h2 class="thank-you-title" id="thankYouTitle" >Hoàn Tất Thanh Toán</h2>
    <div class="thank-container">
        <div id="overlay" class="overlay">
        <svg class="check-spinner" viewBox="0 0 100 100" width="100px" height="100px">
                <defs>
                    <linearGradient id="cs-grad-1" x1="0.5" y1="0" x2="0.5" y2="1">
                        <stop offset="0%" stop-color="hsl(0,0%,100%)" />
                        <stop offset="100%" stop-color="hsl(0,0%,80%)" />
                    </linearGradient>
                    <linearGradient id="cs-grad-2a" x1="0.5" y1="0" x2="0.5" y2="1" gradientTransform="rotate(90,0.5,0.5)">
                        <stop offset="0%" stop-color="hsl(123,90%,55%)" />
                        <stop offset="100%" stop-color="hsl(183,90%,25%)" />
                    </linearGradient>
                    <linearGradient id="cs-grad-2b" x1="0.5" y1="0" x2="0.5" y2="1">
                        <stop offset="0%" stop-color="hsl(123,90%,55%)" />
                        <stop offset="100%" stop-color="hsl(183,90%,25%)" />
                    </linearGradient>
                </defs>
                <circle class="check-spinner__circle" cx="50" cy="50" r="0" fill="url(#cs-grad-1)" />
                <circle class="check-spinner__worm-a" cx="50" cy="50" r="46" fill="none" stroke="url(#cs-grad-2a)" stroke-width="8" stroke-linecap="round" stroke-dasharray="72.2 216.8" stroke-dashoffset="36.1" transform="rotate(-90,50,50)" />
                <path class="check-spinner__worm-b" d="M 17.473 17.473 C 25.797 9.15 37.297 4 50 4 C 75.4 4 96 24.6 96 50 C 96 75.4 75.4 96 50 96 C 24.6 96 4 75.4 4 50 C 4 44.253 6.909 36.33 12.5 35 C 21.269 32.913 35 50 35 50 L 45 60 L 65 40" fill="none" stroke="url(#cs-grad-2b)" stroke-width="8" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="0 0 72.2 341.3" />
              </svg>
        </div>
        <div class="thank-content" id="thankContent">
            <div class="thank-img-container">
                <img src="Home-img/ontheway.png" alt="">
            </div>
            <div class="text">
                <h3 class="ex-word" >Đơn hàng đang được giao</h3> 
                <br>
                <p><b>FoodToast</b> xin cảm ơn khách hàng đã sử dụng dịch vụ</p>
            </div>
            

            <div class="link-back">
                <a href="cart.php" class="back-to-cart">
                Trở về giỏ hàng <i class="fa-solid fa-cart-shopping"></i> </a>
            </div>
        </div>
    </div>
    <script src="js/thank_you.js"></script>
</body>
</html>
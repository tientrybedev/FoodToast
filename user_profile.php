<?php
session_start();
include("connect.php");
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $user_id = $_SESSION['user_id'];
    $historysql = "SELECT 
        od.product_id, od.quantity, od.subtotal, od.order_status, od.show_time,
        o.order_id, o.order_date, o.total_price,
        p.final_payment, pd.payment_type, pd.delivery_cost
    FROM orders o
    JOIN order_details od ON o.order_id = od.order_id
    JOIN payment p ON o.order_id = p.order_id
    JOIN payment_details pd ON p.payment_id = pd.payment_id
    WHERE o.user_id = $user_id";
    $result = mysqli_query($conn, $historysql);
    

    $sql = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user_result = $stmt->get_result();
    if ($user_result->num_rows === 1) {
        $user_data = $user_result->fetch_assoc();
        $username = $user_data['username'];
        $user_email = $user_data['email'];
        $user_phone = $user_data['phone'];
        $profileImage = $user_data['profile_image'];
    } else{
        echo 'Lỗi xảy ra';
    }
}else{
    header("Location: index.php");
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/user_profile.css">
    <link rel="shortcut icon" href="Home-img/food-logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>User Page</title>
</head>
<body>
<div class="loader-container">
        <div class="waiting-img">
            <div class="waiting-card">
                <img src="Home-img/wp4.png" alt="">
            </div>
            <div class="waiting-card">
                <img src="Home-img/wp5.png" alt="">
            </div>
            <div class="waiting-card">
                <img src="Home-img/wp6.png" alt="">
            </div>
        </div>
        <div class="loader">
            <p class="loading">LOADING
                <span>.</span>
                <span>.</span>
                <span>.</span>
            </p>
        </div>
    </div>
    <div class="toast" id="toastMessage"></div>
    <h1 class="profile-title">Profile</h1>
    <div class="all">
        <div class="information">
            <div class="return"><a href="index.php">Quay về <i class="fa-solid fa-arrow-rotate-left"></i></a></div>
            <div class="card">
                <div class="return1"><a href="#"><i class="fa-solid fa-arrow-left-long" title="Quay về"></i></a></div>
                <figure class="img-container">
                    <img id="profileImage" src="<?php echo $profileImage; ?>" alt="User Image" class="img">
                </figure>    
            </div>
            <div class="main">
                <h1 class="title">Thông tin người dùng</h1>
                <div class="box-detail">
                    <div class="user-detail">
                        <p>Tên đăng nhập</p><b id="edit_username" ><?php echo $username; ?></b>
                    </div>
                    <div class="user-detail">
                        <p>Số điện thoại </p><b id="edit_phone" ><?php echo $user_phone; ?></b>
                    </div>
                    <div class="user-detail">
                        <p>Email </p><b id="edit_email" ><?php echo $user_email; ?></b>
                    </div>
                </div>
                <button class="btn" id="open_change_form">Thay đổi <i class="fa-solid fa-pen-to-square"></i></button>
                    <div id="change_form_container" class="change_form_container">
                        <div class="change_form_content">
                            <div class="box-detail">
                            <h2>Thay đổi thông tin</h2>
                            <form method="POST" action="update_user_profile.php" class="changeForm" id="changeProfileForm" enctype="multipart/form-data">
                                <div class="user-detail">
                                    <label for="username">Username:</label>
                                    <input type="text" id="username" name="username" value="<?php echo $username?>">
                                    <div class="fail-valid" id="usernameError"></div>
                                </div>
                                <div class="user-detail">
                                    <label for="phone">Phone:</label>
                                    <input type="tel" id="phone" name="phone" value="<?php echo $user_phone ?>">
                                    <div class="fail-valid" id="phoneError"></div>
                                </div>
                                <div class="user-detail">
                                    <label for="email">Email:</label>
                                    <input type="email" id="email" name="email" value="<?php echo $user_email; ?>">
                                    <div class="fail-valid" id="emailError"></div>
                                </div>
                                <div class="user-detail">
                                    <label for="file">Thay đổi ảnh</label>
                                    <input type="file" id="file" name="file" accept="image/*">
                                </div>
                                <button class="btn" type="submit" id="submitChangeBtn" >Thay đổi <i class="fa-solid fa-pen-to-square"></i></button>
                            </form>
                                <button id="close_change_form" class="close_change_form">X</button>
                            </div>
                        </div>
                    </div>
                <div class="line"></div>
                <div class="function">            
                    <div class="cart">
                        <a href="cart.php"><h3>Giỏ hàng <i class="fa-solid fa-cart-shopping"></i></h3></a>
                    </div>
                    <div class="log-out">
                        <a href="log_out.php"><h3>Đăng xuất <i class="fa-solid fa-arrow-right-from-bracket"></i></h3></a>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <div class="history">
        <h3>Theo dõi đơn hàng</h3>
        <div class="detail-history">
            
<?php
echo '<table>';
echo '<thead>';
echo '<tr>';
echo '<th>Mã đơn hàng</th>';
echo '<th>Mã sản phẩm</th>';
echo '<th>Số lượng</th>';
echo '<th>Tổng tiền sản phẩm</th>';
echo '<th>Trạng thái đơn hàng</th>';
echo '<th>Ngày cập nhật</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';
$previousOrderId = null; 
while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr>';
    if ($previousOrderId !== $row['order_id']) {
        echo '<td>' . $row['order_id'] . '</td>';
        echo '<td>' . $row['product_id'] . '</td>';
        echo '<td>' . $row['quantity'] . '</td>';
        echo '<td>' . $row['subtotal'] . '</td>';
        switch ($row['order_status']) {
            case 'Pending':
                $statusText = 'Đang chờ';
                break;
            case 'Processing':
                $statusText = 'Đang xử lý';
                break;
            case 'Delivering':
                $statusText = 'Đang giao';
                break;
            case 'Finished':
                $statusText = 'Hoàn thành';
                break;
            default:
                $statusText = $row['order_status'];
        }
        
        echo '<td>' . $statusText . '</td>';
        echo '<td>' . $row['show_time'] . '</td>';
        $previousOrderId = $row['order_id'];
    } else {
        echo '<td></td>';
        echo '<td>' . $row['product_id'] . '</td>';
        echo '<td>' . $row['quantity'] . '</td>';
        echo '<td>' . $row['subtotal'] . '</td>';
        
        // Add the switch statement for order_status
        switch ($row['order_status']) {
            case 'Pending':
                $statusText = 'Đang chờ';
                break;
            case 'Processing':
                $statusText = 'Đang xử lý';
                break;
            case 'Delivering':
                $statusText = 'Đang giao';
                break;
            case 'Finished':
                $statusText = 'Hoàn thành';
                break;
            default:
                $statusText = $row['order_status'];
        }
        
        echo '<td>' . $statusText . '</td>';
        echo '<td>' . $row['show_time'] . '</td>';
    }
}
echo '</tbody>';
echo '</table>';
?>

</div>

?>

    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="js/user_profile.js"></script>
</body>
</html>
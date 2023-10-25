<?php
session_start();
$isUserLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
if ($isUserLoggedIn) {
$user_id = $_SESSION['user_id'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/advance_search.css">
    <link rel="shortcut icon" href="Home-img/food-logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>Tìm kiếm nâng cao</title>
</head>
<body>
<div class="toast" id="toastMessage"></div>
    <h1 class="advance-search-header">Tìm kiếm nâng cao</h1>
    <section class="filter-function">
    <a class="return-prev" href="javascript:history.go(-1)" ><i class="fa-solid fa-rotate-left"></i> Quay về</a>
        <form id="advancedSearchForm" method="post" action="advance_search_process.php">
            <div class="category-section">
                <div class="category-choices-result">
                    <b>Tìm kiếm theo:</b> <p id="showCategoryChoices"> bạn chưa chọn gì</p>
                    <div id="validationMessage" class="validation_mess"></div>
                    <button type="submit" class="search">Tìm<i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
                <div class="category-choices">
                    <div class="checkbox-wrapper-47">
                        <input type="checkbox" name="meals[]" id="cb-47" value="breakfast" />
                        <label for="cb-47">Bữa sáng</label>
                    </div>
                    <div class="checkbox-wrapper-48">
                        <input type="checkbox" name="meals[]" id="cb-48" value="lunch" />
                        <label for="cb-48">Bữa trưa</label>
                    </div>
                    <div class="checkbox-wrapper-49">
                        <input type="checkbox" name="meals[]" id="cb-49" value="dinner" />
                        <label for="cb-49">Bữa tối</label>
                    </div>
                    <div class="checkbox-wrapper-53">
                        <input type="checkbox" name="meals[]" id="cb-53" value="rices" />
                        <label for="cb-53">Món cơm</label>
                    </div>
                    <div class="checkbox-wrapper-54">
                        <input type="checkbox" name="meals[]" id="cb-54" value="soup" />
                        <label for="cb-54">Món nước</label>
                    </div>
                    <div class="checkbox-wrapper-55">
                        <input type="checkbox" name="meals[]" id="cb-55" value="diet" />
                        <label for="cb-55">Ăn kiêng</label>
                    </div>
                    <div class="checkbox-wrapper-50">
                        <input type="checkbox" name="meals[]" id="cb-50" value="dessert" />
                        <label for="cb-50">Tráng miệng</label>
                    </div>
                    <div class="checkbox-wrapper-52">
                        <input type="checkbox" name="meals[]" id="cb-52" value="drinks" />
                        <label for="cb-52">Thức uống</label>
                    </div>
                    <div class="checkbox-wrapper-51">
                        <input type="checkbox" name="meals[]" id="cb-51" value="snacks" />
                        <label for="cb-51">Ăn vặt</label>
                    </div>
                </div>
            </div>
        </form>
    </section>
    <section class="filter-result"></section>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" ></script>
    <script src="js/advance_search.js"></script>
</body>
</html>
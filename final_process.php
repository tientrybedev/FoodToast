<?php
session_start();
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $realname = $_POST["realname"];
    $address = $_POST["address"];
    $phone = $_POST["phone"];
    $username = $_POST["username"];
    $payment_type = $_POST['paymentType'];
    $total_amount = $_POST['totalAmount'];
    $delivery_cost = $_POST['deliveryCost'];
    $final_payment = $total_amount + $delivery_cost;

    // Insert into the orders table
    $insertOrdersSQL = "INSERT INTO orders (user_id, total_price, user_phone, address) VALUES ('$user_id', '$total_amount', '$phone', '$address')";
    mysqli_query($conn, $insertOrdersSQL);
    
    // Get the auto-generated order_id
    $order_id = mysqli_insert_id($conn);

    // Get the auto-generated payment_id
    $payment_id = mysqli_insert_id($conn);

    // Insert payment data into the payment table
    $paymentInsertQuery = "INSERT INTO payment (order_id, final_payment) VALUES ('$order_id', '$final_payment')";
    mysqli_query($conn, $paymentInsertQuery);

    if (isset($_POST['card-number']) && isset($_POST['cvv']) && isset($_POST['Year']) && isset($_POST['Month'])) {
        $card_number = $_POST['card-number'];
        $cvv = $_POST['cvv'];
        $card_year = $_POST['Year'];
        $card_month = $_POST['Month'];
        $paymentDetailsQuery = "INSERT INTO payment_details (payment_id, realname, payment_type, delivery_cost, card_number, card_month , card_year, card_cvv) VALUES ('$payment_id', '$realname', '$payment_type', '$delivery_cost', '$card_number', '$card_month', '$card_year', '$cvv')";
        mysqli_query($conn, $paymentDetailsQuery);
    }else{
        $paymentDetailsQuery = "INSERT INTO payment_details (payment_id, realname, payment_type, delivery_cost) VALUES ('$payment_id', '$realname', '$payment_type', '$delivery_cost')";
        mysqli_query($conn, $paymentDetailsQuery);
        }
    $productDetails = $_POST['product_ids'];
    $prices = $_POST['prices'];
    $quantities = $_POST['quantities'];
    $subtotals = $_POST['subtotals'];

    // Loop through product details and insert into order_details table
    for ($i = 0; $i < count($productDetails); $i++) {
        $product_id = $productDetails[$i];
        $price = $prices[$i];
        $quantity = $quantities[$i];
        $subtotal = $subtotals[$i];

        $OrderDetailsQuery = "INSERT INTO order_details (order_id, product_id, price, quantity, subtotal) VALUES ('$order_id', '$product_id', '$price', '$quantity', '$subtotal')";
        mysqli_query($conn, $OrderDetailsQuery);
    }
    for ($i = 0; $i < count($productDetails); $i++) {
        $product_id = $productDetails[$i];
        $deleteFromCartQuery = "DELETE FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
        mysqli_query($conn, $deleteFromCartQuery);
    }

    header("Location: thank_you.php");
}

// Close the database connection
mysqli_close($conn);
?>

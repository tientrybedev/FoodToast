<?php
// include("connect.php");
// session_start();

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $totalAmount = $_POST['totalAmount'];
//     $productIds = $_POST['product_ids'];
//     $quantities = $_POST['quantities'];
//     $cardNumber = $_POST['card-number'];

//     if (isset($_SESSION['user_id'])) {
//         $user_id = $_SESSION['user_id'];
//     } else {
//         echo "User not logged in. Please log in and try again.";
//         exit;
//     }
//     $sql = "INSERT INTO payments (user_id, total_price) VALUES ('$user_id', '$totalAmount')";
//     $result = mysqli_query($conn, $sql);

//     if ($result) {
//         $paymentId = mysqli_insert_id($conn);
//         for ($i = 0; $i < count($productIds); $i++) {
//             $productId = $productIds[$i];
//             $quantity = $quantities[$i];
//             $sql = "SELECT price FROM products WHERE product_id = '$productId'";
//             $result = mysqli_query($conn, $sql);
//             if ($result) {
//                 $row = mysqli_fetch_assoc($result);
//                 if ($row) {
//                     $price = $row['price'];
//                     $subtotal = $price * $quantity;
//                     // Insert product details into payment_details table
//                     $sql = "INSERT INTO payment_details (payment_id, product_id, quantity, subtotal) VALUES ('$paymentId', '$productId', '$quantity', '$subtotal')";
//                     $result = mysqli_query($conn, $sql);
//                     if (!$result) {
//                         echo "Payment processing failed.";
//                         $productData = http_build_query([
//                             'product_ids' => $productIds,
//                             'totalAmount' => $totalAmount,
//                             'quantities' => $quantities]);
//                         exit;
//                     }
//                 }
//             }
//         }
//         foreach ($productIds as $productId) {
//             $deleteQuery = "DELETE FROM cart WHERE user_id = '$user_id' AND product_id = '$productId'";
//             $deleteResult = mysqli_query($conn, $deleteQuery);
//         }
//         header("Location:cart.php");
//     } else {
//         echo "Payment processing failed.";
//     }
// } else {
//     // Redirect the user to the payment page if the form was not submitted
//     header("Location: cart.php");
//     exit();
// }
include("connect.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $totalAmount = $_POST['totalAmount'];
    $productIds = $_POST['product_ids'];
    $quantities = $_POST['quantities'];
    $cardNumber = $_POST['card-number'];

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    }

    $sql = "INSERT INTO payments (user_id, total_price) VALUES ('$user_id', '$totalAmount')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $paymentId = mysqli_insert_id($conn);
        for ($i = 0; $i < count($productIds); $i++) {
            $productId = $productIds[$i];
            $quantity = $quantities[$i];
            $sql = "SELECT price FROM products WHERE product_id = '$productId'";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $row = mysqli_fetch_assoc($result);
                if ($row) {
                    $price = $row['price'];
                    $subtotal = $price * $quantity;
                    // Insert product details into payment_details table
                    $sql = "INSERT INTO payment_details (payment_id, product_id, quantity, subtotal) VALUES ('$paymentId', '$productId', '$quantity', '$subtotal')";
                    $result = mysqli_query($conn, $sql);
                    if (!$result) {
                        // Send an error message and product data back to the payment page
                        $errorData = http_build_query([
                            'error' => 'Payment processing failed.',
                            'product_ids' => $productIds,
                            'totalAmount' => $totalAmount,
                            'quantities' => $quantities
                        ]);
                        header("Location: payment.php?" . $errorData);
                        exit;
                    }
                }
            }
        }

        foreach ($productIds as $productId) {
            $deleteQuery = "DELETE FROM cart WHERE user_id = '$user_id' AND product_id = '$productId'";
            $deleteResult = mysqli_query($conn, $deleteQuery);
        }

        // Redirect to the thank you page
        header("Location: thank_you.php");
    } else {
        // Send an error message and product data back to the payment page
        $errorData = http_build_query([
            'error' => 'Payment processing failed.',
            'product_ids' => $productIds,
            'totalAmount' => $totalAmount,
            'quantities' => $quantities
        ]);
        header("Location: payment.php?" . $errorData);
        exit;
    }
} else {
    // Redirect the user to the payment page if the form was not submitted
    header("Location: payment.php");
    exit();
}

?>

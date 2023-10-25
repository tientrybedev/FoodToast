<?php
// Include your database connection script here
include("connect.php");

// Retrieve data from the form
$product_id = $_POST['product_id'];
$user_id = $_POST['user_id'];
$rating = $_POST['rate'];
$comment = $_POST['comment']; // Assuming you've added a 'name' attribute to your textarea

// Insert data into the ratings table
$sql = "INSERT INTO ratings (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("siis", $product_id, $user_id, $rating, $comment); // Adjust the types based on your database schema
$result = $stmt->execute();

if ($result) {
    // Rating and comment successfully inserted
    echo "Rating and comment posted successfully!";
    header("Location: Single-product-detail.php?product_id=$product_id");
    exit();
} else {
    // Handle the case where insertion failed
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

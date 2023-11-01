<?php
session_start();
include("connect.php");

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT COUNT(*) AS count FROM favorite_products WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $count = $row['count'];
        $response = array('count' => $count);
        echo json_encode($response);
    } else {
        $response = array('count' => 0); 
        echo json_encode($response);
    }

    $stmt->close();
} else {
    $response = array('count' => 0); 
    echo json_encode($response);
}
?>

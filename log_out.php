<?php
session_start();
if (isset($_SESSION['res_loggedin']) && $_SESSION['res_loggedin'] === true){
    session_destroy();
    header("Location: res_register.php");
}else{
    session_destroy();
    header("Location: index.php");
}
exit();
?>
<?php
session_start();
require "option.php";

$iduser = $_COOKIE['iduser'] ?? '';
$iddetail = $_POST['iddetail'] ?? 0;
$quantity = $_POST['quantity'] ?? 1;

if ($iduser && $iddetail > 0 && $quantity > 0) {
    // Проверяем, что товар принадлежит текущему пользователю
    $check_query = mysqli_query($dbcnx, "SELECT d.iddetail 
                                         FROM detail d
                                         JOIN sale s ON d.idsale = s.idsale
                                         WHERE d.iddetail = $iddetail 
                                         AND s.iduser = $iduser 
                                         AND s.kind = 'Корзина'");
    
    if (mysqli_num_rows($check_query) > 0) {
        mysqli_query($dbcnx, "UPDATE detail SET quantity = $quantity WHERE iddetail = $iddetail");
    }
}

header("Location: sale2.php");
exit();
?>
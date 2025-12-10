<?php
require "option.php";

$iduser = $_COOKIE['iduser'] ?? '';
$iddetail = $_GET['iddetail'] ?? 0;

if ($iduser && $iddetail > 0) {
    // Проверяем, что товар принадлежит текущему пользователю
    $check_query = mysqli_query($dbcnx, "SELECT d.iddetail 
                                         FROM detail d
                                         JOIN sale s ON d.idsale = s.idsale
                                         WHERE d.iddetail = $iddetail 
                                         AND s.iduser = $iduser 
                                         AND s.kind = 'Корзина'");
    
    if (mysqli_num_rows($check_query) > 0) {
        mysqli_query($dbcnx, "DELETE FROM detail WHERE iddetail = $iddetail");
    }
}

header("Location: sale2.php");
exit();
?>
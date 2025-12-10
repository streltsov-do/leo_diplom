<?
require "option.php";
?>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<?
mysqli_query($dbcnx,"DELETE  FROM sale where iduser=$iduser and kind='Корзина'");

?>
 <script language="javascript">
location.href='sale1.php?category=merch.idcategory';
 </script>

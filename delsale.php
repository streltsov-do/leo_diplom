<?
require "option.php";
?>

<?
$Arr=$_POST['Arrsale'];
$idsale=$Arr[0];
mysqli_query($dbcnx,"DELETE  FROM sale where idsale=$idsale");
?>
 <script language="javascript">
location.href='sale.php';
 </script>

	<?
require "option.php";//���� � ����������� ����������� � ��

$Arr=$_POST['Arr'];
$id=$Arr[0]; 
mysqli_query($dbcnx,"DELETE FROM category  WHERE idcategory=$id");

?>
 <script language="javascript">
 location.href='category.php';
 </script>

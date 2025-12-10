	<?
require "option.php";//���� � ����������� ����������� � ��

$Arr=$_POST['Arr'];
$id=$Arr[0]; 
mysqli_query($dbcnx,"DELETE FROM user  WHERE iduser=$id");

?>
 <script language="javascript">
 location.href='user.php';
 </script>

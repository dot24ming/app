<?php
$mysql_server_name='115.29.104.45';
$mysql_username='root';
$mysql_password='river123';
$mysql_database='car_db_2';



$conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password) or die("error connecting") ;
mysql_query("set names 'utf8'");
mysql_select_db($mysql_database);
$sql ="select * from model_goods_info2 ";
$result = mysql_query($sql,$conn);
var_dump($result);
while($row = mysql_fetch_array($result))
{
	var_dump($row);
	break;
}

?>

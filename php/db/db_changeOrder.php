<?php 

function db_changeOrder()
{
	
	$sql1 = "UPDATE page_content SET contentorder='999999999' WHERE contentorder={$_POST['thisOrder']}";
	$sql2 = "UPDATE page_content SET contentorder='{$_POST['thisOrder']}' WHERE contentorder={$_POST['predecessor']}";
	$sql3 = "UPDATE page_content SET contentorder='{$_POST['predecessor']}' WHERE contentorder=999999999";
	$GLOBALS['DB']->query($sql1);
	$GLOBALS['DB']->query($sql2);
	$GLOBALS['DB']->query($sql3);		

}
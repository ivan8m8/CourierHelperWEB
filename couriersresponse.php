<?php
	ini_set('error_reporting', 0);
	ini_set('display_errors', 0);
	ini_set('display_startup_errors', 0);

	mb_internal_encoding("UTF-8");

	$mysql_host = "localhost";
	$mysql_user = "host1585490";
	$mysql_password = "a5523393";
	$mysql_dbname = "host1585490";
	$mysql_tablename = "allDeliveriesFromOnlineStoreEngine";

	$orderNumber = $_GET["orderNumber"];
    $deliveryDate = $_GET["deliveryDate"];
    $secureCode = $_GET["secure7Code"];

    if ($secureCode == "придумайтеКод") {
    	$link = mysql_connect($mysql_host, $mysql_user, $mysql_password) 
        or die('Не удалось соединиться: ' . mysql_error());
    	mysql_select_db($mysql_dbname) or die('Не удалось выбрать базу данных: ' . mysql_error());
    	mysql_set_charset('utf8');
    	$result = mysql_query("UPDATE $mysql_tablename SET deliveryStatus = '1', deliveryDate = '".mysql_real_escape_string($deliveryDate)."' WHERE orderNumber = '".mysql_real_escape_string($orderNumber)."';") or die('Не удалось отметить, что курьер выполнил доставку: ' . mysql_error());
    } // else нужно записывать и банить IP

	mysql_close($link);
?>

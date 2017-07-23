<?php
	ini_set('error_reporting', E_ALL & ~E_DEPRECATED);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);

	mb_internal_encoding("UTF-8");

	$mysql_host = "localhost";
	$mysql_user = "host1585490";
	$mysql_password = "a5523393";
	$mysql_dbname = "host1585490";
	$mysql_tablename = "allDeliveriesFromOnlineStoreEngine";

	$link = mysql_connect($mysql_host, $mysql_user, $mysql_password) 
        or die('Не удалось соединиться: ' . mysql_error());
    mysql_select_db($mysql_dbname) or die('Не удалось выбрать базу данных: ' . mysql_error());
    mysql_set_charset('utf8');

    $deliveryCourier = $_GET["deliveryCourier"];
    
    $result = mysql_query("SELECT * FROM allDeliveriesFromOnlineStoreEngine WHERE deliveryStatus = '0' AND deliveryCourier = '".mysql_real_escape_string($deliveryCourier)."'");
	$rows = array();
	while($r = mysql_fetch_assoc($result)) {
    	$rows[] = $r;
	}

	header('Content-Type:Application/json; charset=utf-8');
	echo json_encode($rows, JSON_UNESCAPED_UNICODE);

	mysql_close($link);
?>
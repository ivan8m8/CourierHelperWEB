<?php
	ini_set('error_reporting', E_ALL & ~E_DEPRECATED);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);

	mb_internal_encoding("UTF-8");

	require_once 'config/config.php';

    $link = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_dbname);
	if (!$link) {
	    echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
	    echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
	    echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
	    exit;
	}

	mysqli_set_charset($link, "utf8");

    $deliveryCourier = $_GET["deliveryCourier"];
    
    $result = mysqli_query($link, "SELECT * FROM $mysql_tablename WHERE deliveryStatus = '0' AND deliveryCourier = '".mysqli_real_escape_string($link, $deliveryCourier)."'");
	$rows = array();
	while($r = mysqli_fetch_assoc($result)) {
    	$rows[] = $r;
	}

	header('Content-Type:Application/json; charset=utf-8');
	echo json_encode($rows, JSON_UNESCAPED_UNICODE);

	//mysqli_free_result($result);
	mysqli_close($link);
?>
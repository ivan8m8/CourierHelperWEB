<?php
	ini_set('error_reporting', 0);
	ini_set('display_errors', 0);
	ini_set('display_startup_errors', 0);

	mb_internal_encoding("UTF-8");

    require_once 'config/config.php';

	$orderNumber = $_GET["orderNumber"];
    $deliveryDate = $_GET["deliveryDate"];

    $link = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_dbname);
    if (!$link) {
        echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
        echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }
    ysqli_set_charset($link, "utf8");
    $result = mysqli_query($link, "UPDATE $mysql_tablename SET deliveryStatus = '1', deliveryDate = '".mysqli_real_escape_string($link, $deliveryDate)."' WHERE orderNumber = '".mysqli_real_escape_string($link, $orderNumber)."';") or die('Не удалось отметить, что курьер выполнил доставку: ' . mysqli_error($link));
    mysqli_free_result($result);

	mysqli_close($link);
?>
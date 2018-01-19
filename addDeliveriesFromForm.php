<?php
	require_once 'config/config.php';
	$query = "INSERT INTO `deliveries` (`orderNumber`, `deliveryAddress`, `deliveryTimeLimit`, `clientName`, `clientPhoneNumber`, `clientComment`, `itemName`, `itemPrice`) VALUES ";
	$orderNumbers = array_values($_POST['order-number']);
	$deliveryAddresses = array_values($_POST['delivery-address']);
	$deliveryTimeLimits = array_values($_POST['delivery-time-limit']);
	$clientNames = array_values($_POST['client-name']);
	$clientPhoneNumbers = array_values($_POST['client-phone-number']);
	$clientComments = array_values($_POST['client-comment']);
	$itemNames = array_values($_POST['item-name']);
	$itemPrices = array_values($_POST['item-price']);
	for ($i=0; $i < count($orderNumbers) - 1; $i++) { 
        $query .= "('";
        $query .= $orderNumbers[$i];
        $query .= "', '";
        $query .= $deliveryAddresses[$i];
        $query .= "', '";
        $query .= $deliveryTimeLimits[$i];
        $query .= "', '";
        $query .= $clientNames[$i];
        $query .= "', '";
        $query .= $clientPhoneNumbers[$i];
        $query .= "', '";
        $query .= $clientComments[$i];
        $query .= "', '";
        $query .= $itemNames[$i];
        $query .= "', ";
        if ($itemPrices[$i] == "") {
        	$query .= "NULL";
        } else {
        	$query .= "'";
        	$query .= $itemPrices[$i];
        	$query .= "'";
        }
        $query .= "), ";
	}
	$query = substr($query, 0, -2);
	$query .= ";";
	echo '<b>Был сформирован следующий SQL-запрос к базе: </b><br />';
	echo $query;
	echo '<br />';
	echo '<br />';

	$conn = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_dbname);
	mysqli_set_charset($conn, "utf8");
    if (!$conn) {
        die("Не удалось пожключиться к БД: " . mysqli_connect_error());
    } 
	if (mysqli_query($conn, $query)) {
        echo "SQL-запроc отпрвлен.<br /><b>Проверьте, чтобы ниже не было ошибок.</b> <br /><br />";
    } else {
        echo "<b>Ошибка:</b> " . $sql . "<br>" . mysqli_error($conn);
    }
	echo '<br /><a href ="https://test.courierhelper.ru/">Вернуться</a>';
?>
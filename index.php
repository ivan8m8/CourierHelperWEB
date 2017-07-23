<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Панель управления менеджера курьеров</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Custom styles for this template -->
    <link href="css/justified-nav.css" rel="stylesheet">
  </head>

  <body>

    <div class="container">

      <!-- The justified navigation menu is meant for single line per list item.
           Multiple lines will require custom code not provided by Bootstrap. -->
      <!-- <div class="masthead">
        <h3 class="text-muted">Панель управления менеджера курьеров</h3>
        <nav>
          <ul class="nav nav-justified">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#">Projects</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Downloads</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Contact</a></li>
          </ul>
        </nav>
      </div> -->

      <!-- Jumbotron -->
      <div class="jumbotron">
        <h2>Текущие заказы</h2>         
  			<table class="table table-condensed table-hover">
  			<!-- <thead>
      			<tr>
        			<th>№ заказа</th>
        			<th>Адрес доставки</th>
        			<th>До</th>
        			<th>АО</th>
        			<th>Метро №1</th>
        			<th>Метро №2</th>
        			<th>Курьер</th>
      			</tr>
    		</thead> -->
    		<tbody>
        

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
mysql_select_db($mysql_dbname) or die('Не удалось выбрать базу данных');
mysql_set_charset('utf8');

$query = 'SELECT * FROM `allDeliveriesFromOnlineStoreEngine` WHERE deliveryStatus = "0" ORDER BY deliveryTimeLimit DESC';
$result = mysql_query($query) or die('Запрос не удался: ' . mysql_error());

while($row = mysql_fetch_array($result)){
	echo "<tr>";
		echo '<td>'.$row['orderNumber'].'</td>';
		echo '<td>'.$row['deliveryAddress'].'</td>';
		echo '<td>'.$row['deliveryDistrict'].'</td>';
		echo '<td>'.$row['deliveryUndergroundStation1'].' ('.$row['deliveryUndergroundStation1Distance'].') </td>';
		echo '<td>'.$row['deliveryUndergroundStation2'].' ('.$row['deliveryUndergroundStation2Distance'].') </td>';
		echo '<td>'.$row['deliveryTimeLimit'].'</td>';
		echo '<td>'.$row['deliveryCourier'].'</td>';
	echo "</tr>";
}

// while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
//     echo "\t<tr>\n";
//     foreach ($line as $col_value) {
//         echo "\t\t<td>$col_value</td>\n";
//     }
//     echo "\t</tr>\n";
// }
echo "</tbody></table>\n";

// Освобождаем память от результата
mysql_free_result($result);

// Закрываем соединение
mysql_close($link);
?>

<p>
	<form action="" method="post">
		<input class="btn btn-lg btn-success" type="submit" name="calculate" value="Рассчитать параметры">
    	<input class="btn btn-lg btn-info" type="submit" name="sort_by_districts" value="Сортировать по округам">
	</form>
</p>

<!-- <p>
	<form action="" method="post">
		<input class="btn btn-lg btn-success" type="submit" name="calculate" value="Рассчитать параметры">
	</form>
	</br>
	<form action="" method="post">
    	<input class="btn btn-lg btn-info" type="submit" name="sort_by_districts" value="Сортировать по округам">
	</form>
</p> -->

<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


if (isset($_POST["calculate"]))  {  

    function calculate_distance_from_underground_station ($φA, $λA, $φB, $λB) {
    	/*
 		 * Расстояние между двумя точками
 		 * $φA, $λA - широта, долгота 1-й точки,
 		 * $φB, $λB - широта, долгота 2-й точки
 		 * Написано по мотивам http://gis-lab.info/qa/great-circles.html
 		 * Михаил Кобзарев <kobzarev@inforos.ru>
 		 * kobzarev.com/programming/calculation-of-distances-between-cities-on-their-coordinates/
 		 *
 		*/

    	//define('EARTH_RADIUS', 6372795);
    	// перевести координаты в радианы
    	$lat1 = $φA * M_PI / 180;
    	$lat2 = $φB * M_PI / 180;
    	$long1 = $λA * M_PI / 180;
    	$long2 = $λB * M_PI / 180;
 
    	// косинусы и синусы широт и разницы долгот
    	$cl1 = cos($lat1);
    	$cl2 = cos($lat2);
    	$sl1 = sin($lat1);
    	$sl2 = sin($lat2);
    	$delta = $long2 - $long1;
    	$cdelta = cos($delta);
    	$sdelta = sin($delta);
 
    	// вычисления длины большого круга
    	$y = sqrt(pow($cl2 * $sdelta, 2) + pow($cl1 * $sl2 - $sl1 * $cl2 * $cdelta, 2));
    	$x = $sl1 * $sl2 + $cl1 * $cl2 * $cdelta;
 
    	$ad = atan2($y, $x);
    	$dist = $ad * 6372795 / 1000;
 
    	return mb_strimwidth($dist, 0, 4);
	}

	//echo "string";
	//echo get_lat_lng($deliveryAddress);
	//header("Location: index.php"); 
	//header("Location: ".$_SERVER["REQUEST_URI"].""); 

	$link = mysql_connect($mysql_host, $mysql_user, $mysql_password) 
		or die('Не удалось соединиться: ' . mysql_error());
	mysql_select_db($mysql_dbname) or die('Не удалось выбрать базу данных');
	mysql_set_charset('utf8');
	$query = 'SELECT * FROM `allDeliveriesFromOnlineStoreEngine`';
	$source_db_data = mysql_query($query) or die('Запрос не удался: ' . mysql_error());

    $count_geocode = 0; // Общее ко-во адресов
    $count_geocode_fault = 0; // Ко-во адресов, в обработке которых произошла ошибка

    // https://yandex.ru/blog/ymapsapi/81
    while ($row = mysql_fetch_assoc($source_db_data)) {
    	if ($row['latLng'] == "") {
    		$count_geocode++; // разве тут, а не вконце?
    	$xml = simplexml_load_file('http://geocode-maps.yandex.ru/1.x/?geocode='.urlencode($row["deliveryAddress"]).'&ll=37.618920,55.756994&spn=0.552069,0.400552&results=1');
    	$found = $xml->GeoObjectCollection->metaDataProperty->GeocoderResponseMetaData->found;
    	if ($found > 0) {
        	$coords = str_replace(' ', ',', $xml->GeoObjectCollection->featureMember->GeoObject->Point->pos);
        	$coords_array = explode(",", $coords);
            mysql_query("UPDATE `$mysql_tablename` SET latLng = '".mysql_real_escape_string($coords)."' WHERE orderNumber = {$row['orderNumber']}") or die("Не удалось занести в таблицу координаты");
            $xml3 = simplexml_load_file('http://geocode-maps.yandex.ru/1.x/?geocode='.urlencode($coords).'&kind=district');
            $district = str_replace(', Москва, Россия', '', $xml3->GeoObjectCollection->featureMember->GeoObject->description);
            $district = str_replace('район Восточное Дегунино, ', '', $district);
            $district = str_replace('район Щукино, ', '', $district);

            switch ($district) {
            	case 'Центральный административный округ':
            		$district = 'ЦАО';
            		break;
            	case 'Северо-Западный административный округ':
            		$district = 'СЗАО';
            		break;
            	case 'Северный административный округ':
            		$district = 'САО';
            		break;	
            	case 'Северо-Восточный административный округ':
            		$district = 'СВАО';
            		break;
            	case 'Восточный административный округ':
            		$district = 'ВАО';
            		break;	
            	case 'Юго-Восточный административный округ':
            		$district = 'ЮВАО';
            		break;
            	case 'Южный административный округ':
            		$district = 'ЮАО';
            		break;	
            	case 'Юго-Западный административный округ':
            		$district = 'ЮЗАО';
            		break;	
            	case 'Западный административный округ':
            		$district = 'ЗАО';
            		break;
            }

            mysql_query("UPDATE `$mysql_tablename` SET deliveryDistrict = '".mysql_real_escape_string($district)."' WHERE orderNumber = {$row['orderNumber']}") or die("Не удалось занести в таблицу район");
            $xml2 = simplexml_load_file('https://geocode-maps.yandex.ru/1.x/?geocode='.$coords.'&kind=metro');
            $found2 = $xml2->GeoObjectCollection->metaDataProperty->GeocoderResponseMetaData->found;
            if ($found2 > 0) {
            	$metro1 = str_replace('метро ', '', $xml2->GeoObjectCollection->featureMember[0]->GeoObject->name);
                mysql_query("UPDATE `$mysql_tablename` SET deliveryUndergroundStation1 = '".mysql_real_escape_string($metro1)."' WHERE orderNumber = {$row['orderNumber']}") or die("Не удалось занести в таблицу метро1");
            	$metro1_coords = str_replace(' ', ',', $xml2->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos);
            	$metro1_array = explode(",", $metro1_coords);
            	$metro2 = str_replace('метро ', '', $xml2->GeoObjectCollection->featureMember[1]->GeoObject->name);
                mysql_query("UPDATE `$mysql_tablename` SET deliveryUndergroundStation2 = '".mysql_real_escape_string($metro2)."' WHERE orderNumber = {$row['orderNumber']}") or die("Не удалось занести в таблицу метро2");
            	$metro2_coords = str_replace(' ', ',', $xml2->GeoObjectCollection->featureMember[1]->GeoObject->Point->pos);
            	$metro2_array = explode(",", $metro2_coords);
            	$metro1_distance = calculate_distance_from_underground_station($coords_array[1], $coords_array[0], $metro1_array[1], $metro1_array[0]);
                $metro2 = str_replace('метро ', '', $xml2->GeoObjectCollection->featureMember[1]->GeoObject->name);
                mysql_query("UPDATE `$mysql_tablename` SET deliveryUndergroundStation1Distance = '".mysql_real_escape_string($metro1_distance)."' WHERE orderNumber = {$row['orderNumber']}") or die("Не удалось занести в таблицу расстояние от метро1");
            	$metro2_distance = calculate_distance_from_underground_station($coords_array[1], $coords_array[0], $metro2_array[1], $metro2_array[0]);
                $metro2 = str_replace('метро ', '', $xml2->GeoObjectCollection->featureMember[1]->GeoObject->name);
                mysql_query("UPDATE `$mysql_tablename` SET deliveryUndergroundStation2Distance = '".mysql_real_escape_string($metro2_distance)."' WHERE orderNumber = {$row['orderNumber']}") or die("Не удалось занести в таблицу расстояние от метро2");
            } else {
            	$metro1 = "Не удалось определить";
            	$metro2 = "Не удалось определить";
            }
    	} else {
            $countGeocodeFault++;
    	}
    	}
	};
    mysql_close($link);
    if ($count_geocode) {
        echo '<div style="margin-top:1em">Всего обработано адресов: '.$count_geocode.'</div>';
        if ($count_geocode_fault) {
            echo '<div style="color:red">Не удалось прогеокодировать: '.$count_geocode_fault.'</div>';
        }
    } else {
        echo '<div>Таблица с адресами пуста или заполнена полностью.</div>';
    }
    //header("Location: index.php");
    // Освобождаем память от результата
    mysql_free_result($result);

}

if (isset($_POST["sort_by_districts"]))  {
    $link = mysql_connect($mysql_host, $mysql_user, $mysql_password) 
        or die('Не удалось соединиться: ' . mysql_error());
    mysql_select_db($mysql_dbname) or die('Не удалось выбрать базу данных');
    mysql_set_charset('utf8');

    echo "<form action=\"\" method=\"post\">";

    /******
      * ЦАО
    *******/

    $query = 'SELECT * FROM `allDeliveriesFromOnlineStoreEngine` WHERE deliveryDistrict = "ЦАО";' or die("Не удалось загрузить доставки по ЦАО");
    $result = mysql_query($query) or die('Запрос не удался: ' . mysql_error());
    $query2 = 'SELECT * FROM couriers' or die('Не удалось загрузить курьеров' . mysql_error());

    if (mysql_num_rows($result) != 0) {
    	echo "<h3>ЦАО</h3>";
    	echo '<table class="table table-condensed table-hover">';
    	while($row = mysql_fetch_array($result)){
			echo "<tr>";
			echo '<td>'.$row['orderNumber'].'</td>';
			echo '<td>'.$row['deliveryAddress'].'</td>';
			echo '<td>'.$row['deliveryDistrict'].'</td>';
			echo '<td>'.$row['deliveryUndergroundStation1'].' ('.$row['deliveryUndergroundStation1Distance'].') </td>';
			echo '<td>'.$row['deliveryUndergroundStation2'].' ('.$row['deliveryUndergroundStation2Distance'].') </td>';
			echo '<td>'.$row['deliveryTimeLimit'].'</td>';
			echo '<td><select name="choose_courier['.$row['orderNumber'].']" required>';
            echo "<option value=\"$row[deliveryCourier]\">$row[deliveryCourier]</option>";
            $result2 = mysql_query($query2) or die('Запрос не удался' . mysql_error());
            while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
                if ($row2[name] != $row[deliveryCourier]) {
                    echo "<option value=\"$row2[name]\">$row2[name]</option>";
                }
            }
            echo "</select></td>";

			echo "</tr>";
    	}
    	echo "</table>";
	}

	// Освобождаем память от результата
    mysql_free_result($result);
    mysql_free_result($result2);

    /******
      * ЗАО
    ******/

    $query = 'SELECT * FROM `allDeliveriesFromOnlineStoreEngine` WHERE deliveryDistrict = "ЗАО";' or die("Не удалось загрузить доставки по ЗАО");
    $result = mysql_query($query) or die('Запрос не удался: ' . mysql_error());
    $query2 = 'SELECT * FROM couriers' or die('Не удалось загрузить курьеров' . mysql_error());

    if (mysql_num_rows($result) != 0) {
    	echo "<h3>ЗАО</h3>";
    	echo '<table class="table table-condensed table-hover">';
    	while($row = mysql_fetch_array($result)){
			echo "<tr>";
			echo '<td>'.$row['orderNumber'].'</td>';
			echo '<td>'.$row['deliveryAddress'].'</td>';
			echo '<td>'.$row['deliveryDistrict'].'</td>';
			echo '<td>'.$row['deliveryUndergroundStation1'].' ('.$row['deliveryUndergroundStation1Distance'].') </td>';
			echo '<td>'.$row['deliveryUndergroundStation2'].' ('.$row['deliveryUndergroundStation2Distance'].') </td>';
			echo '<td>'.$row['deliveryTimeLimit'].'</td>';
			echo '<td><select name="choose_courier['.$row['orderNumber'].']" required>';
            echo "<option value=\"$row[deliveryCourier]\">$row[deliveryCourier]</option>";
            $result2 = mysql_query($query2) or die('Запрос не удался' . mysql_error());
            while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
                if ($row2[name] != $row[deliveryCourier]) {
                    echo "<option value=\"$row2[name]\">$row2[name]</option>";
                }
            }
            echo "</select></td>";

			echo "</tr>";
    	}
    	echo "</table>";
	}

	// Освобождаем память от результата
    mysql_free_result($result);
    mysql_free_result($result2);

    /*******
      * CЗАО
    *******/

    $query = 'SELECT * FROM `allDeliveriesFromOnlineStoreEngine` WHERE deliveryDistrict = "СЗАО";' or die("Не удалось загрузить доставки по СЗАО");
    $result = mysql_query($query) or die('Запрос не удался: ' . mysql_error());
    $query2 = 'SELECT * FROM couriers' or die('Не удалось загрузить курьеров' . mysql_error());

    if (mysql_num_rows($result) != 0) {
    	echo "<h3>СЗАО</h3>";
    	echo '<table class="table table-condensed table-hover">';
    	while($row = mysql_fetch_array($result)){
			echo "<tr>";
			echo '<td>'.$row['orderNumber'].'</td>';
			echo '<td>'.$row['deliveryAddress'].'</td>';
			echo '<td>'.$row['deliveryDistrict'].'</td>';
			echo '<td>'.$row['deliveryUndergroundStation1'].' ('.$row['deliveryUndergroundStation1Distance'].') </td>';
			echo '<td>'.$row['deliveryUndergroundStation2'].' ('.$row['deliveryUndergroundStation2Distance'].') </td>';
			echo '<td>'.$row['deliveryTimeLimit'].'</td>';
			echo '<td><select name="choose_courier['.$row['orderNumber'].']" required>';
            echo "<option value=\"$row[deliveryCourier]\">$row[deliveryCourier]</option>";
            $result2 = mysql_query($query2) or die('Запрос не удался' . mysql_error());
            while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
                if ($row2[name] != $row[deliveryCourier]) {
                    echo "<option value=\"$row2[name]\">$row2[name]</option>";
                }
            }
            echo "</select></td>";

			echo "</tr>";
    	}
    	echo "</table>";
	}

	// Освобождаем память от результата
    mysql_free_result($result);
    mysql_free_result($result2);

    /******
      * САО
    ******/

    $query = 'SELECT * FROM `allDeliveriesFromOnlineStoreEngine` WHERE deliveryDistrict = "САО";' or die("Не удалось загрузить доставки по САО");
    $result = mysql_query($query) or die('Запрос не удался: ' . mysql_error());
    $query2 = 'SELECT * FROM couriers' or die('Не удалось загрузить курьеров' . mysql_error());

    if (mysql_num_rows($result) != 0) {
    	echo "<h3>САО</h3>";
    	echo '<table class="table table-condensed table-hover">';
    	while($row = mysql_fetch_array($result)){
			echo "<tr>";
			echo '<td>'.$row['orderNumber'].'</td>';
			echo '<td>'.$row['deliveryAddress'].'</td>';
			echo '<td>'.$row['deliveryDistrict'].'</td>';
			echo '<td>'.$row['deliveryUndergroundStation1'].' ('.$row['deliveryUndergroundStation1Distance'].') </td>';
			echo '<td>'.$row['deliveryUndergroundStation2'].' ('.$row['deliveryUndergroundStation2Distance'].') </td>';
			echo '<td>'.$row['deliveryTimeLimit'].'</td>';
			echo '<td><select name="choose_courier['.$row['orderNumber'].']" required>';
            echo "<option value=\"$row[deliveryCourier]\">$row[deliveryCourier]</option>";
            $result2 = mysql_query($query2) or die('Запрос не удался' . mysql_error());
            while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
                if ($row2[name] != $row[deliveryCourier]) {
                    echo "<option value=\"$row2[name]\">$row2[name]</option>";
                }
            }
            echo "</select></td>";

			echo "</tr>";
    	}
    	echo "</table>";
	}

	// Освобождаем память от результата
    mysql_free_result($result);
    mysql_free_result($result2);

    /******
      * СВАО
    ******/

    $query = 'SELECT * FROM `allDeliveriesFromOnlineStoreEngine` WHERE deliveryDistrict = "СВАО";' or die("Не удалось загрузить доставки по СВАО");
    $result = mysql_query($query) or die('Запрос не удался: ' . mysql_error());
    $query2 = 'SELECT * FROM couriers' or die('Не удалось загрузить курьеров' . mysql_error());

    if (mysql_num_rows($result) != 0) {
    	echo "<h3>СВАО</h3>";
    	echo '<table class="table table-condensed table-hover">';
    	while($row = mysql_fetch_array($result)){
			echo "<tr>";
			echo '<td>'.$row['orderNumber'].'</td>';
			echo '<td>'.$row['deliveryAddress'].'</td>';
			echo '<td>'.$row['deliveryDistrict'].'</td>';
			echo '<td>'.$row['deliveryUndergroundStation1'].' ('.$row['deliveryUndergroundStation1Distance'].') </td>';
			echo '<td>'.$row['deliveryUndergroundStation2'].' ('.$row['deliveryUndergroundStation2Distance'].') </td>';
			echo '<td>'.$row['deliveryTimeLimit'].'</td>';
			echo '<td><select name="choose_courier['.$row['orderNumber'].']" required>';
            echo "<option value=\"$row[deliveryCourier]\">$row[deliveryCourier]</option>";
            $result2 = mysql_query($query2) or die('Запрос не удался' . mysql_error());
            while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
                if ($row2[name] != $row[deliveryCourier]) {
                    echo "<option value=\"$row2[name]\">$row2[name]</option>";
                }
            }
            echo "</select></td>";

			echo "</tr>";
    	}
    	echo "</table>";
	}

	// Освобождаем память от результата
    mysql_free_result($result);
    mysql_free_result($result2);

    /******
      * ВАО
    ******/

    $query = 'SELECT * FROM `allDeliveriesFromOnlineStoreEngine` WHERE deliveryDistrict = "ВАО";' or die("Не удалось загрузить доставки по ВАО");
    $result = mysql_query($query) or die('Запрос не удался: ' . mysql_error());
    $query2 = 'SELECT * FROM couriers' or die('Не удалось загрузить курьеров' . mysql_error());

    if (mysql_num_rows($result) != 0) {
    	echo "<h3>ВАО</h3>";
    	echo '<table class="table table-condensed table-hover">';
    	while($row = mysql_fetch_array($result)){
			echo "<tr>";
			echo '<td>'.$row['orderNumber'].'</td>';
			echo '<td>'.$row['deliveryAddress'].'</td>';
			echo '<td>'.$row['deliveryDistrict'].'</td>';
			echo '<td>'.$row['deliveryUndergroundStation1'].' ('.$row['deliveryUndergroundStation1Distance'].') </td>';
			echo '<td>'.$row['deliveryUndergroundStation2'].' ('.$row['deliveryUndergroundStation2Distance'].') </td>';
			echo '<td>'.$row['deliveryTimeLimit'].'</td>';
			echo '<td><select name="choose_courier['.$row['orderNumber'].']" required>';
            echo "<option value=\"$row[deliveryCourier]\">$row[deliveryCourier]</option>";
            $result2 = mysql_query($query2) or die('Запрос не удался' . mysql_error());
            while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
                if ($row2[name] != $row[deliveryCourier]) {
                    echo "<option value=\"$row2[name]\">$row2[name]</option>";
                }
            }
            echo "</select></td>";

			echo "</tr>";
    	}
    	echo "</table>";
	}

	// Освобождаем память от результата
    mysql_free_result($result);
    mysql_free_result($result2);

    /******
      * ЮВАО
    ******/

    $query = 'SELECT * FROM `allDeliveriesFromOnlineStoreEngine` WHERE deliveryDistrict = "ЮВАО";' or die("Не удалось загрузить доставки по ЮВАО");
    $result = mysql_query($query) or die('Запрос не удался: ' . mysql_error());
    $query2 = 'SELECT * FROM couriers' or die('Не удалось загрузить курьеров' . mysql_error());

    if (mysql_num_rows($result) != 0) {
    	echo "<h3>ЮВАО</h3>";
    	echo '<table class="table table-condensed table-hover">';
    	while($row = mysql_fetch_array($result)){
			echo "<tr>";
			echo '<td>'.$row['orderNumber'].'</td>';
			echo '<td>'.$row['deliveryAddress'].'</td>';
			echo '<td>'.$row['deliveryDistrict'].'</td>';
			echo '<td>'.$row['deliveryUndergroundStation1'].' ('.$row['deliveryUndergroundStation1Distance'].') </td>';
			echo '<td>'.$row['deliveryUndergroundStation2'].' ('.$row['deliveryUndergroundStation2Distance'].') </td>';
			echo '<td>'.$row['deliveryTimeLimit'].'</td>';
			echo '<td><select name="choose_courier['.$row['orderNumber'].']" required>';
            echo "<option value=\"$row[deliveryCourier]\">$row[deliveryCourier]</option>";
            $result2 = mysql_query($query2) or die('Запрос не удался' . mysql_error());
            while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
                if ($row2[name] != $row[deliveryCourier]) {
                    echo "<option value=\"$row2[name]\">$row2[name]</option>";
                }
            }
            echo "</select></td>";

			echo "</tr>";
    	}
    	echo "</table>";
	}

	// Освобождаем память от результата
    mysql_free_result($result);
    mysql_free_result($result2);

    /******
      * ЮАО
    ******/

    $query = 'SELECT * FROM `allDeliveriesFromOnlineStoreEngine` WHERE deliveryDistrict = "ЮАО";' or die("Не удалось загрузить доставки по ЮАО");
    $result = mysql_query($query) or die('Запрос не удался: ' . mysql_error());
    $query2 = 'SELECT * FROM couriers' or die('Не удалось загрузить курьеров' . mysql_error());

    if (mysql_num_rows($result) != 0) {
    	echo "<h3>ЮАО</h3>";
    	echo '<table class="table table-condensed table-hover">';
    	while($row = mysql_fetch_array($result)){
			echo "<tr>";
			echo '<td>'.$row['orderNumber'].'</td>';
			echo '<td>'.$row['deliveryAddress'].'</td>';
			echo '<td>'.$row['deliveryDistrict'].'</td>';
			echo '<td>'.$row['deliveryUndergroundStation1'].' ('.$row['deliveryUndergroundStation1Distance'].') </td>';
			echo '<td>'.$row['deliveryUndergroundStation2'].' ('.$row['deliveryUndergroundStation2Distance'].') </td>';
			echo '<td>'.$row['deliveryTimeLimit'].'</td>';
			echo '<td><select name="choose_courier['.$row['orderNumber'].']" required>';
            echo "<option value=\"$row[deliveryCourier]\">$row[deliveryCourier]</option>";
            $result2 = mysql_query($query2) or die('Запрос не удался' . mysql_error());
            while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
                if ($row2[name] != $row[deliveryCourier]) {
                    echo "<option value=\"$row2[name]\">$row2[name]</option>";
                }
            }
            echo "</select></td>";

			echo "</tr>";
    	}
    	echo "</table>";
	}

	// Освобождаем память от результата
    mysql_free_result($result);
    mysql_free_result($result2);

    /******
      * ЮЗАО
    ******/

    $query = 'SELECT * FROM `allDeliveriesFromOnlineStoreEngine` WHERE deliveryDistrict = "ЮЗАО";' or die("Не удалось загрузить доставки по ЮЗАО");
    $result = mysql_query($query) or die('Запрос не удался: ' . mysql_error());
    $query2 = 'SELECT * FROM couriers' or die('Не удалось загрузить курьеров' . mysql_error());

    if (mysql_num_rows($result) != 0) {
    	echo "<h3>ЮЗАО</h3>";
    	echo '<table class="table table-condensed table-hover">';
    	while($row = mysql_fetch_array($result)){
			echo "<tr>";
			echo '<td>'.$row['orderNumber'].'</td>';
			echo '<td>'.$row['deliveryAddress'].'</td>';
			echo '<td>'.$row['deliveryDistrict'].'</td>';
			echo '<td>'.$row['deliveryUndergroundStation1'].' ('.$row['deliveryUndergroundStation1Distance'].') </td>';
			echo '<td>'.$row['deliveryUndergroundStation2'].' ('.$row['deliveryUndergroundStation2Distance'].') </td>';
			echo '<td>'.$row['deliveryTimeLimit'].'</td>';
			echo '<td><select name="choose_courier['.$row['orderNumber'].']" required>';
            echo "<option value=\"$row[deliveryCourier]\">$row[deliveryCourier]</option>";
            $result2 = mysql_query($query2) or die('Запрос не удался' . mysql_error());
            while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
                if ($row2[name] != $row[deliveryCourier]) {
                    echo "<option value=\"$row2[name]\">$row2[name]</option>";
                }
            }
            echo "</select></td>";

			echo "</tr>";
    	}
    	echo "</table>";
	}

	// Освобождаем память от результата
    mysql_free_result($result);
    mysql_free_result($result2);

    echo '<input type="submit" name="send_to_couriers" value="Отправить курьерам">';
    echo "</form>";

    // Закрываем соединение
    mysql_close($link);
}

if (isset($_POST["send_to_couriers"])) {
    $link = mysql_connect($mysql_host, $mysql_user, $mysql_password) 
        or die('Не удалось соединиться: ' . mysql_error());
    mysql_select_db($mysql_dbname) or die('Не удалось выбрать базу данных: ' . mysql_error());
    mysql_set_charset('utf8');
    foreach ($_REQUEST["choose_courier"] as $key => $value) {
        mysql_query("UPDATE allDeliveriesFromOnlineStoreEngine SET deliveryCourier = '".mysql_real_escape_string($value)."' WHERE orderNumber = '".mysql_real_escape_string($key)."';") or die('Не удалось записать курьеров: ' . mysql_error());
    }
    mysql_close($link);

}
?>

	<!-- Site footer -->
      <footer class="footer">
        <p>&copy; 2017 IVAN MAKACHKA</p>
      </footer>

    </div> <!-- /container -->
    </body>
</html>
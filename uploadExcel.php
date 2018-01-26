<?php

mb_internal_encoding("UTF-8");

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'Classes/PHPExcel/IOFactory.php';

require_once 'config/config.php';

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["excelFileToUpload"]["name"]);
$uploadOk = 1;

$fileType = pathinfo($target_file,PATHINFO_EXTENSION);

// Check file size
if ($_FILES["excelFileToUpload"]["size"] > 5000000) {
    echo "Очень большой файл.";
    $uploadOk = 0;
}

// Allow certain file formats
if($fileType != "xlsx" && $fileType != "xls" ) {
    echo "Поддерживаются только следующие форматы: XLSX, XLS.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Ваш файл НЕ был загуржен.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["excelFileToUpload"]["tmp_name"], $target_file)) {
        echo "Ваш файл ". basename($_FILES["excelFileToUpload"]["name"]). " был успешно загружен.";
        echo '<br />';

        $inputfilename = $target_dir . basename($_FILES["excelFileToUpload"]["name"]);

        $exceldata = array();

        $conn = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_dbname);
        mysqli_set_charset($conn, "utf8");
        if (!$conn) {
            die("Не удалось подключиться к БД: " . mysqli_connect_error());
        } 
 
        try {
            $inputfiletype = PHPExcel_IOFactory::identify($inputfilename);
            $objReader = PHPExcel_IOFactory::createReader($inputfiletype);
            $objPHPExcel = $objReader->load($inputfilename);
        }
        catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputfilename,PATHINFO_BASENAME).'": '.$e->getMessage());
        }
 
        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0); 
        $highestRow = $sheet->getHighestRow(); 
        $highestColumn = $sheet->getHighestColumn();
 
        //  Loop through each row of the worksheet in turn
        for ($row = 1; $row <= $highestRow; $row++) { 
            //  Read a row of data into an array
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

            // if ($rowData[0][7] == '') {
            //     $sql = "INSERT INTO deliveries (orderNumber, deliveryAddress, deliveryTimeLimit, clientName, clientPhoneNumber, clientComment, itemName, itemPrice)
            //     VALUES ('".$rowData[0][0]."', '".$rowData[0][1]."', '".$rowData[0][2]."', '".$rowData[0][3]."', '".$rowData[0][4]."', '".$rowData[0][5]."', '".$rowData[0][6]."', NULL)";
            // } else {
            //     $sql = "INSERT INTO deliveries (orderNumber, deliveryAddress, deliveryTimeLimit, clientName, clientPhoneNumber, clientComment, itemName, itemPrice)
            // VALUES ('".$rowData[0][0]."', '".$rowData[0][1]."', '".$rowData[0][2]."', '".$rowData[0][3]."', '".$rowData[0][4]."', '".$rowData[0][5]."', '".$rowData[0][6]."', '".$rowData[0][7]."')";
            // }

            $sql = "INSERT INTO deliveries (orderNumber, deliveryAddress, deliveryTimeLimit, clientName, clientPhoneNumber, clientComment, itemName, itemPrice)
            VALUES ('".$rowData[0][0]."', '".$rowData[0][1]."', '".$rowData[0][2]."', '".$rowData[0][3]."', '".$rowData[0][4]."', '".$rowData[0][5]."', '".$rowData[0][6]."', '".$rowData[0][7]."')";

            echo "<br />";
            echo "Был выполнен следующий SQL-запрос: <br />";
            echo $sql;
            echo "<br />";
    
            if (mysqli_query($conn, $sql)) {
                $exceldata[] = $rowData[0];
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }
 
        echo '<br />';
        echo "<b>Следующие доставки были импортированы: </b>";
        echo '<br />';
        echo "<table>";
        foreach ($exceldata as $index => $excelraw) {
            echo "<tr>";
            foreach ($excelraw as $excelcolumn) {
                echo "<td>".$excelcolumn."</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        echo '<br />';
        echo '<a href ="https://test.courierhelper.ru">Вернуться на главную</a>';
 
        mysqli_close($conn);
    } else {
        echo "НЕ удалось загрузить Ваш файл.";
    }
}
?>
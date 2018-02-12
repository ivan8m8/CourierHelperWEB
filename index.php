<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="Ivan Makachka">

    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Панель управления менеджера курьеров</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>

    <style>
        .modalwin { 
            width: 300px;
                background: white;
                top: 20%; /* отступ сверху */
                right: 0;
                left: 0;
                font-size: 14px; 
                margin: 0 auto;
                z-index:2; /* поверх всех */
                display: none;  /* сначала невидим */
                position: fixed; /* фиксированное позиционирование, окно стабильно при прокрутке */
                padding: 15px;
                border: 1px solid #000;
                border-radius: 6px;
            }
            #shadow { 
                position: fixed;
                width:100%;
                height:100%;
                z-index:1; /* поверх всех  кроме окна*/
                background:#000;
                opacity: 0.5; /*прозрачность*/
                left:0;
                top:0;
            }
    </style>

    <style>
        .displaynone {
            display: none;
        }
    </style>

    <script type="text/javascript">
            function showModalWin() {
 
                var darkLayer = document.createElement('div'); // слой затемнения
                darkLayer.id = 'shadow'; // id чтобы подхватить стиль
                document.body.appendChild(darkLayer); // включаем затемнение
 
                var modalWin = document.getElementById('popupWin'); // находим наше "окно"
                modalWin.style.display = 'block'; // "включаем" его
 
                darkLayer.onclick = function () {  // при клике на слой затемнения все исчезнет
                    darkLayer.parentNode.removeChild(darkLayer); // удаляем затемнение
                    modalWin.style.display = 'none'; // делаем окно невидимым
                    return false;
                };
            }
    </script>
    <script>
        function checkSQLView() {
            var sqlstring, container, inputs, index;
            sqlstring = "INSERT INTO `deliveries` (`orderNumber`, `deliveryAddress`, `deliveryTimeLimit`, `clientName`, `clientPhoneNumber`, `clientComment`, `itemName`, `itemPrice`) VALUES ";
            container = document.getElementById('addDeliveryForm');
            inputs = container.getElementsByTagName('input');
            for (index = 0; index < inputs.length-8; index++) {
                if (index == 0) {
                    sqlstring += "(";
                }
                if (index != 0 && index % 8 == 0) {
                    sqlstring = sqlstring.slice(0, -2);
                    sqlstring += "), (";
                }
                sqlstring += "'";
                sqlstring += inputs[index].value;
                sqlstring += "'";
                sqlstring += ", ";
            }
            sqlstring = sqlstring.slice(0, -8);
            sqlstring += ");";
            alert(sqlstring);
        }
        $(document).ready(function() {
            var inputs2, container2, index2;
            $('#addDeliveryForm')
                .on('click', '.addButton', function() {
                    var $template = $('#addDeliveryTemplate'),
                    $clone        = $template
                                    .clone()
                                    .removeAttr('id')
                                    .removeClass('displaynone')
                                    .insertBefore($template);
                    container2 = document.getElementById('addDeliveryForm');
                    inputs2 = container2.getElementsByTagName('input');
                    for (index2 = 0; index2 < inputs2.length-16; index2++) {
                        if (index2 % 8 == 0) {
                            inputs2[index2].required = true;
                        }
                    }
                }
            )
            .on('click', '.removeButton', function() {
                var $row  = $(this).parents('.form-row');
                $row.remove();
            });
        });
    </script>
    <script type="text/javascript">
        function goToMainPage() {
            setTimeout(function () {
                window.location.href= 'index.php'; // the redirect goes here
            },1600);
        }
    </script>
</head>

<body>
    <!-- Upload From Excel Modal -->
    <div id="uploadExcel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="uploadExcel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Импортировать доставки из excel-файла</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <b>Внимание!</b> <br />Ознакомьтесь с <a target="_blank" href="https://github.com/ivan8m8/CourierHelperWEB/wiki/%D0%9A%D0%B0%D0%BA-%D0%B8%D0%BC%D0%BF%D0%BE%D1%80%D1%82%D0%B8%D1%80%D0%BE%D0%B2%D0%B0%D1%82%D1%8C-%D0%B4%D0%BE%D1%81%D1%82%D0%B0%D0%B2%D0%BA%D0%B8-%D0%B2-%D1%81%D0%B8%D1%81%D1%82%D0%B5%D0%BC%D1%83#%D0%A2%D1%80%D0%B5%D0%B1%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D0%B5-%D0%BA-%D0%B8%D0%BC%D0%BF%D0%BE%D1%80%D1%82%D0%B8%D1%80%D1%83%D0%B5%D0%BC%D0%BE%D0%BC%D1%83-ex%D1%81el-%D1%84%D0%B0%D0%B9%D0%BB%D1%83-%D1%81-%D0%B4%D0%BE%D1%81%D1%82%D0%B0%D0%B2%D0%BA%D0%B0%D0%BC%D0%B8">требованиями к excel-файлу</a>. <br /><br />
            <form action="uploadExcel.php" method="post" enctype="multipart/form-data">
                <input type="file" name="excelFileToUpload" id="excelFileToUpload"/> <br /><br />
                <input style="background-color: #0069d9; color: #fff;" class="form-control input-sm pull-right" type="submit" name="submit" value="Загрузить" />
            </form>
          </div>
          <!-- <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div> -->
        </div>
      </div>
    </div>

    <!-- Remove all the deliveries -->
    <div id="removeAllTheDeliveries" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="removeAllTheDeliveries" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Удалить все доставки из системы</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <b>Вы уверены?</b> <br /><br />
            <form action="" method="post" enctype="multipart/form-data">
                <input style="background-color: #0069d9; color: #fff;" class="form-control input-sm pull-right" type="submit" name="removeAllTheDeliveries" value="Удалить" />
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Add random deliveries -->
    <div id="addRandomDeliveries" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addRandomDeliveries" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Импортировать случайные доставки в систему</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Будут импортированы 26 доставок. <br />Для некоторых из них параметры не будут рассчитаны.
            <br /> Чтобы это сделать, после импортирования нажмите кнопку "Рассчитать параметры".
            <br /><br />
            <form action="" method="post" enctype="multipart/form-data">
                <input style="background-color: #0069d9; color: #fff;" class="form-control input-sm pull-right" type="submit" name="addRandomDeliveries" value="Импортировать" />
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Deliveries Modal -->
    <div id="addDeliveries" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="addDeliveries" aria-hidden="true">
        <div class="modal-dialog" role="document" style="max-width: 1200px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Добавить доставки</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="addDeliveriesFromForm.php" method="post" id="addDeliveryForm">
                        <a onclick="checkSQLView();">Посмотреть SQL</a><br />
                        <div class="form-row">
                            <div class="col">
                                <input id="order-n" type="text" class="form-control" placeholder="Номер заказа (обязательно)" name="order-number[]" required="true" onkeypress='return (event.charCode >= 48 && event.charCode <= 57)'>
                            </div>
                            <div class="col-3">
                                <input type="text" class="form-control" placeholder="Адрес доставки (обязательно)" name="delivery-address[]" required="true">
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Ограничения по времени" name="delivery-time-limit[]">
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Имя" name="client-name[]">
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Номер телефона" name="client-phone-number[]" onkeypress='return (event.charCode >= 48 && event.charCode <= 57)'>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Комментарий" name="client-comment[]">
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Идентификатор товара" name="item-name[]">
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Цена товара" name="item-price[]" onkeypress='return (event.charCode >= 48 && event.charCode <= 57)'>
                            </div>
                            <div class="col-xs-1">
                                <button type="button" class="btn btn-secondary addButton"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>

                        <div class="form-row displaynone" id="addDeliveryTemplate">
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Номер заказа (обязательно)" name="order-number[]" onkeypress='return (event.charCode >= 48 && event.charCode <= 57)'>
                            </div>
                            <div class="col-3">
                                <input type="text" class="form-control" placeholder="Адрес доставки (обязательно)" name="delivery-address[]">
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Ограничения по времени" name="delivery-time-limit[]">
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Имя" name="client-name[]">
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Номер телефона" name="client-phone-number[]" onkeypress='return (event.charCode >= 48 && event.charCode <= 57)'>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Комментарий" name="client-comment[]">
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Идентификатор товара" name="item-name[]">
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Цена товара" name="item-price[]" onkeypress='return (event.charCode >= 48 && event.charCode <= 57)'>
                            </div>
                            <div class="col-xs-1">
                                <button type="button" class="btn btn-default removeButton"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>

                        <br />
                        <div class="form-row">
                            <input style="width: 100%;" type="submit" name="sdtm" class="btn btn-primary" value="Добавить все эти доставки в базу данных" />
                        </div>
                    </form>
                </div>
            </div>
      </div>
    </div>
    
        <div align="left" style="margin-top: 8px; margin-left: 8px; display: inline-block;">
            <a target="_blank" href="https://github.com/ivan8m8/CourierHelperWEB/wiki/%D0%9A%D0%B0%D0%BA-%D0%BF%D0%BE%D0%BB%D1%8C%D0%B7%D0%BE%D0%B2%D0%B0%D1%82%D1%8C%D1%81%D1%8F-%D1%81%D0%B8%D1%81%D1%82%D0%B5%D0%BC%D0%BE%D0%B9">Как пользоваться?</a>
        </div>

        <div align="right" style="margin-top: 8px; margin-right: 8px; display: inline-block; float: right;">
            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Импортировать доставки</button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" data-toggle="modal" href="#" data-target="#uploadExcel">Из excel-файла</a>
                    <a class="dropdown-item" href="#">SQL-запрос</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" data-toggle="modal" href="#" data-target="#addDeliveries">Добавить доставки</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" data-toggle="modal" href="#" data-target="#addRandomDeliveries">Заполнить рандомно</a>
                    <a class="dropdown-item" data-toggle="modal" href="#" data-target="#removeAllTheDeliveries">Удалить все доставки</a>
                </div>
            </div>
        </div>
    
    <center><div class="container">

        <h2><a href="/">Текущие заказы</a></h2>        
            <table class="table table-sm table-bordered table-hover">
            <style>td,th {text-align: center;}</style>
            <thead>
                <tr>
                    <th>№ заказа</th>
                    <th>Адрес доставки</th>
                    <th>Округ</th>
                    <th>Район</th>
                    <th>Метро №1</th>
                    <th>Метро №2</th>
                    <th>Ограничения</th>
                    <th>Курьер</th>
                </tr>
            </thead>
            <tbody>
        

<?php

require_once 'config/config.php';

ini_set('error_reporting', E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

mb_internal_encoding("UTF-8");

$link = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_dbname);
if (!$link) {
    echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
    echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
mysqli_set_charset($link, "utf8");

if (isset($_POST["removeAllTheDeliveries"])) {
    if (mysqli_query($link, "DELETE FROM `deliveries` WHERE 1=1")) {
        echo "SQL-запроc отпрвлен.<br /><b>Проверьте, чтобы ниже не было ошибок.</b> <br /><br />";
        echo '<script type="text/javascript">',
                'goToMainPage();',
                '</script>'
        ;
    } else {
        echo "<b>Ошибка:</b><br>" . mysqli_error($link);
    }
}

if (isset($_POST["addRandomDeliveries"])) {
    $rndddump = "INSERT INTO `deliveries` (`orderNumber`, `clientName`, `deliveryAddress`, `longLat`, `deliveryUndergroundStation1`, `deliveryUndergroundStation2`, `deliveryUndergroundStation1Distance`, `deliveryUndergroundStation2Distance`, `adminArea`, `district`, `clientPhoneNumber`, `clientComment`, `deliveryTimeLimit`, `itemName`, `itemPrice`, `deliveryDate`, `deliveryStatus`, `deliveryCourier`) VALUES
(1, NULL, 'Тверская, 11', '', '', '', '', '', '', '', '89991112233', NULL, 'до 23', NULL, NULL, NULL, 0, NULL),
(2, 'Иван', 'Старая Басманная, 1', '', '', '', '', '', '', '', '89001234568', '', 'до 18', 'GA-120B-7A', '5890', '', 0, 'АстаховПП_kV00'),
(3, 'Дмитрий', 'Кунцевская, 8', '', '', '', '', '', '', '', '89008947649', 'сразу позвонить', 'до 22', 'GA-400B-7C', '9890', '', 0, 'АстаховПП_kV00'),
(4, 'Константин', 'Вяземская, 8', '37.400674,55.713785', 'Молодёжная', 'Кунцевская', '3.20', '3.37', 'ЗАО', 'Можайский', '89001459837', 'вручить подарок', 'до 18', 'QEMC-MJ2', '1890', '', 0, 'АстаховПП_kV00'),
(5, 'Александр', 'Брянская, 2', '37.563853,55.744389', 'Киевская', 'Киевская', '0.14', '0.16', 'ЗАО', 'Дорогомилово', '89008741373', '', '', 'EFR-1200-2B', '11890', '', 0, 'АстаховПП_kV00'),
(6, 'Олег', 'Одинцовская, 9', '37.400045,55.786025', 'Строгино', 'Крылатское', '1.97', '3.28', 'СЗАО', 'Строгино', '89009801039', '', '', 'Восток 112897', '2490', '', 0, 'СадиковЕЕ_zeU7'),
(7, NULL, 'Тверская, 8', '', '', '', '', '', '', '', '89991112234', NULL, 'до 17', NULL, '1290', NULL, 0, NULL),
(8, NULL, 'Тверская, 18', '37.604241,55.766147', 'Пушкинская', 'Тверская', '0.04', '0.21', 'ЦАО', 'Тверской', '89991112235', NULL, 'после 17', NULL, '4190', NULL, 0, 'ИвановСП_mT3v'),
(9, NULL, 'Новая Басманная, 8', '37.654996,55.769205', 'Красные Ворота', 'Комсомольская', '0.38', '0.52', 'ЦАО', 'Басманный', NULL, NULL, 'до 15', NULL, NULL, NULL, 0, NULL),
(10, NULL, 'Тверская, 19', '37.603586,55.765154', 'Пушкинская', 'Тверская', '0.06', '0.16', 'ЦАО', 'Тверской', '89991112236', NULL, '', 'очки RB-510', '8000', NULL, 0, 'ИвановСП_mT3v'),
(11, NULL, 'Митинская, 17', '', '', '', '', '', '', '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL),
(12, NULL, 'Тверская, 22', '37.601169,55.767418', 'Пушкинская', 'Маяковская', '0.25', '0.40', 'ЦАО', 'Тверской', '89991112237', NULL, 'после 20', NULL, NULL, NULL, 0, 'ИвановСП_mT3v'),
(13, 'Сергей', 'Балчуг, 7', '', '', '', '', '', '', '', '', '', '', '', NULL, NULL, 0, NULL),
(14, '', 'Варшавское шоссе, 42', '37.624741,55.675306', 'Нагатинская', 'Нагорная', '0.90', '0.91', 'ЮАО', 'Нагорный', '89001001010', '', 'до 15', '', NULL, NULL, 0, NULL),
(15, 'Антон', 'Варшавское шоссе, 68', '', '', '', '', '', '', '', '89001001010', '', 'до 15', '', NULL, NULL, 0, NULL),
(16, '', 'Нагатинская, 2', '', '', '', '', '', '', '', '', '', '', '', NULL, NULL, 0, NULL),
(17, 'Олег', 'Звенигородская, 7', '37.447099,55.734805', 'Кунцевская', 'Кунцевская', '0.44', '0.47', 'ЗАО', 'Фили-Давыдково', '89151236789', 'позвонить за час', 'до 17', 'GA-100A-7A', '6890', NULL, 0, NULL),
(18, '', 'Перерва, 58', '37.7608,55.662816', 'Братиславская', 'Люблино', '0.75', '1.43', 'ЮВАО', 'Марьино', '', '', '', '', NULL, NULL, 0, NULL),
(19, '', 'Батайский пр-д, 5', '37.713036,55.642728', 'Марьино', 'Борисово', '2.14', '2.18', 'ЮВАО', 'Марьино', '', 'под. 1, кв. 7, эт. 2, сделать подарок', '', 'GB-2000-2B', '11890', NULL, 0, NULL),
(20, '', 'Воронежская, 8к1', '', '', '', '', '', '', '', '', '', 'после 18', '', NULL, NULL, 0, NULL),
(21, '', 'Педагогическая, 6', '37.666971,55.595176', 'Орехово', 'Царицыно', '2.59', '2.93', 'ЮАО', 'Бирюлёво Восточное', '', '', '', '', '1190', NULL, 0, NULL),
(22, 'Артём', 'Харьковский пр-д, 1к3', '37.646085,55.595781', 'Улица Академика Янгеля', 'Царицыно', '2.84', '3.20', 'ЮАО', 'Бирюлёво Западное', '89999771234', 'на проходной позвонить', '', 'CK-4000', '12990', NULL, 0, 'ИвановСП_mT3v'),
(23, '', 'Верхние поля, 53к2', '37.799068,55.657773', 'Братиславская', 'Люблино', '3.05', '3.07', 'ЮВАО', 'Люблино', '89774445566', '', '', '', NULL, NULL, 0, NULL),
(24, 'Виктор', 'Кошкина, 8', '', '', '', '1.13', '1.96', '', '', '89998887766', 'заранее позвонить', 'до 23', 'GA-110B-1A', '6890', NULL, 0, NULL),
(25, 'Антон', 'Кошкина, 8', '37.670537,55.642352', 'Кантемировская', 'Каширская', '1.13', '1.96', 'ЮАО', 'Москворечье-Сабурово', '8999988887', 'п. 2, кв. 376к1234, эт. 11', 'после 17', 'GA-100A-7A', '5280', NULL, 0, 'ИвановСП_mT3v'),
(26, 'Михаил', 'Рязанская, 26', '37.666513,55.771661', 'Бауманская', 'Комсомольская', '0.78', '0.81', 'ЦАО', 'Басманный', '', '', '', '', '3390', NULL, 0, 'ИвановСП_mT3v');";
    if (mysqli_query($link, $rndddump)) {
        echo "SQL-запроc отпрвлен.<br /><b>Убедитесь, что ниже нет сообщений об ошибках.</b> <br /><br />";
        echo '<script type="text/javascript">',
                'goToMainPage();',
                '</script>'
        ;
    } else {
        echo "<b>Ошибка:</b><br>" . mysqli_error($link);
    }
}

$query  = "SELECT * FROM $mysql_tablename WHERE deliveryStatus = 0 ORDER BY CASE WHEN deliveryTimeLimit = '' OR deliveryTimeLimit IS NULL THEN 2 ELSE 1 END, deliveryTimeLimit";
$result = mysqli_query($link, $query);

if (!$result) {
    echo "Не удалось выполнить запрос";
}

while ($row = mysqli_fetch_array($result)) {
    echo "<tr>";
    echo '<td>' . $row['orderNumber'] . '</td>';
    echo '<td>' . $row['deliveryAddress'] . '</td>';
    echo '<td>' . $row['adminArea'] . '</td>';
    echo '<td>' . $row['district'] . '</td>';
    if ($row['deliveryUndergroundStation1'] == "") {
        echo '<td>' . $row['deliveryUndergroundStation1'] . '</td>';
    } else {
        echo '<td>' . $row['deliveryUndergroundStation1'] . ' (' . $row['deliveryUndergroundStation1Distance'] . ') </td>';
    }
    if ($row['deliveryUndergroundStation2'] == "") {
        echo '<td>' . $row['deliveryUndergroundStation2'] . '</td>';
    } else {
        echo '<td>' . $row['deliveryUndergroundStation2'] . ' (' . $row['deliveryUndergroundStation2Distance'] . ') </td>';
    }
    echo '<td>' . $row['deliveryTimeLimit'] . '</td>';
    echo '<td>' . $row['deliveryCourier'] . '</td>';
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
mysqli_free_result($result);

// Закрываем соединение
mysqli_close($link);
?>

<p>
    <form action="" method="post">
        <input class="btn btn-lg btn-success" type="submit" name="calculate" onclick="showModalWin()" value="Рассчитать параметры">
        <input class="btn btn-lg btn-info" type="submit" name="sort_by_admin_areas" value="Сортировать по округам">
    </form>
</p>

<div id="popupWin" class="modalwin">
    <h3>Загружаем данные...</h3>  
    <div class="progress progress-striped active" style="margin-bottom:0;"><div class="progress-bar" style="width: 100%"></div></div>
</div>

<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


if (isset($_POST["calculate"])) {

    function calculate_distance_from_underground_station($φA, $λA, $φB, $λB) {
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
        $lat1  = $φA * M_PI / 180;
        $lat2  = $φB * M_PI / 180;
        $long1 = $λA * M_PI / 180;
        $long2 = $λB * M_PI / 180;
        
        // косинусы и синусы широт и разницы долгот
        $cl1    = cos($lat1);
        $cl2    = cos($lat2);
        $sl1    = sin($lat1);
        $sl2    = sin($lat2);
        $delta  = $long2 - $long1;
        $cdelta = cos($delta);
        $sdelta = sin($delta);
        
        // вычисления длины большого круга
        $y = sqrt(pow($cl2 * $sdelta, 2) + pow($cl1 * $sl2 - $sl1 * $cl2 * $cdelta, 2));
        $x = $sl1 * $sl2 + $cl1 * $cl2 * $cdelta;
        
        $ad   = atan2($y, $x);
        $dist = $ad * 6372795 / 1000;
        
        return mb_strimwidth($dist, 0, 4);
    }
    
    //echo "string";
    //echo get_lat_lng($deliveryAddress);
    //header("Location: index.php"); 
    //header("Location: ".$_SERVER["REQUEST_URI"].""); 
    
    $link = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_dbname);
    if (!$link) {
        echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
        echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }
    mysqli_set_charset($link, "utf8");
    
    //$query = 'SELECT * FROM `$mysql_tablename`';
    $query = "SELECT * FROM $mysql_tablename";
    $source_db_data = mysqli_query($link, $query) or die('Запрос не удался: ' . mysqli_error($link));
    
    $count_geocode       = 0; // Общее ко-во адресов
    $count_geocode_fault = 0; // Ко-во адресов, в обработке которых произошла ошибка
    
    // https://yandex.ru/blog/ymapsapi/81
    while ($row = mysqli_fetch_assoc($source_db_data)) {
        if ($row['adminArea'] == "") {
            $count_geocode++; // разве тут, а не вконце?
            $xml   = simplexml_load_file('http://geocode-maps.yandex.ru/1.x/?geocode=' . urlencode($row["deliveryAddress"]) . '&ll=37.618920,55.756994&spn=0.552069,0.400552&results=1');
            $found = $xml->GeoObjectCollection->metaDataProperty->GeocoderResponseMetaData->found;
            if ($found > 0) {
                $coords       = str_replace(' ', ',', $xml->GeoObjectCollection->featureMember->GeoObject->Point->pos);
                $coords_array = explode(",", $coords);
                mysqli_query($link, "UPDATE `$mysql_tablename` SET longLat = '" . mysqli_real_escape_string($link, $coords) . "' WHERE orderNumber = {$row['orderNumber']}") or die("Не удалось занести в таблицу координаты");
                $xml3     = simplexml_load_file('http://geocode-maps.yandex.ru/1.x/?geocode=' . urlencode($coords) . '&kind=district');
                $admin_area = str_replace(', Москва, Россия', '', $xml3->GeoObjectCollection->featureMember->GeoObject->metaDataProperty->GeocoderMetaData->Address->Component[4]->name);
                $admin_area = str_replace('район Восточное Дегунино, ', '', $admin_area);
                $admin_area = str_replace('район Щукино, ', '', $admin_area);
                $admin_area = str_replace('район Арбат, ', '', $admin_area);

                $district = str_replace('район', '', $xml3->GeoObjectCollection->featureMember->GeoObject->metaDataProperty->GeocoderMetaData->Address->Component[5]->name);

                //$district = str_replace('район ', '', $xml3->GeoObjectCollection->featureMember->GeoObject->metaDataProperty->GeocoderMetaData->Address->Component[5]->name);
                
                switch ($admin_area) {
                    case 'Центральный административный округ':
                        $admin_area = 'ЦАО';
                        break;
                    case 'Северо-Западный административный округ':
                        $admin_area = 'СЗАО';
                        break;
                    case 'Северный административный округ':
                        $admin_area = 'САО';
                        break;
                    case 'Северо-Восточный административный округ':
                        $admin_area = 'СВАО';
                        break;
                    case 'Восточный административный округ':
                        $admin_area = 'ВАО';
                        break;
                    case 'Юго-Восточный административный округ':
                        $admin_area = 'ЮВАО';
                        break;
                    case 'Южный административный округ':
                        $admin_area = 'ЮАО';
                        break;
                    case 'Юго-Западный административный округ':
                        $admin_area = 'ЮЗАО';
                        break;
                    case 'Западный административный округ':
                        $admin_area = 'ЗАО';
                        break;
                }
                
                mysqli_query($link, "UPDATE `$mysql_tablename` SET adminArea = '" . mysqli_real_escape_string($link, $admin_area) . "' WHERE orderNumber = {$row['orderNumber']}") or die("Не удалось занести в таблицу район");

                mysqli_query($link, "UPDATE `$mysql_tablename` SET district = '" . mysqli_real_escape_string($link, $district) . "' WHERE orderNumber = {$row['orderNumber']}") or die("Не удалось занести в таблицу район");

                $xml2   = simplexml_load_file('https://geocode-maps.yandex.ru/1.x/?geocode=' . $coords . '&kind=metro');
                $found2 = $xml2->GeoObjectCollection->metaDataProperty->GeocoderResponseMetaData->found;
                if ($found2 > 0) {
                    $metro1 = str_replace('метро ', '', $xml2->GeoObjectCollection->featureMember[0]->GeoObject->name);
                    mysqli_query($link, "UPDATE `$mysql_tablename` SET deliveryUndergroundStation1 = '" . mysqli_real_escape_string($link, $metro1) . "' WHERE orderNumber = {$row['orderNumber']}") or die("Не удалось занести в таблицу метро1");
                    $metro1_coords = str_replace(' ', ',', $xml2->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos);
                    $metro1_array  = explode(",", $metro1_coords);
                    $metro2        = str_replace('метро ', '', $xml2->GeoObjectCollection->featureMember[1]->GeoObject->name);
                    mysqli_query($link, "UPDATE `$mysql_tablename` SET deliveryUndergroundStation2 = '" . mysqli_real_escape_string($link, $metro2) . "' WHERE orderNumber = {$row['orderNumber']}") or die("Не удалось занести в таблицу метро2");
                    $metro2_coords   = str_replace(' ', ',', $xml2->GeoObjectCollection->featureMember[1]->GeoObject->Point->pos);
                    $metro2_array    = explode(",", $metro2_coords);
                    $metro1_distance = calculate_distance_from_underground_station($coords_array[1], $coords_array[0], $metro1_array[1], $metro1_array[0]);
                    $metro2          = str_replace('метро ', '', $xml2->GeoObjectCollection->featureMember[1]->GeoObject->name);
                    mysqli_query($link, "UPDATE `$mysql_tablename` SET deliveryUndergroundStation1Distance = '" . mysqli_real_escape_string($link, $metro1_distance) . "' WHERE orderNumber = {$row['orderNumber']}") or die("Не удалось занести в таблицу расстояние от метро1");
                    $metro2_distance = calculate_distance_from_underground_station($coords_array[1], $coords_array[0], $metro2_array[1], $metro2_array[0]);
                    $metro2          = str_replace('метро ', '', $xml2->GeoObjectCollection->featureMember[1]->GeoObject->name);
                    mysqli_query($link, "UPDATE `$mysql_tablename` SET deliveryUndergroundStation2Distance = '" . mysqli_real_escape_string($link, $metro2_distance) . "' WHERE orderNumber = {$row['orderNumber']}") or die("Не удалось занести в таблицу расстояние от метро2");
                } else {
                    $metro1 = "Не удалось определить";
                    $metro2 = "Не удалось определить";
                }
            } else {
                $countGeocodeFault++;
            }
        }
    }
    mysqli_free_result($source_db_data); 
    mysqli_close($link);
    if ($count_geocode) {
        echo '<div style="margin-top:1em">Всего обработано адресов: ' . $count_geocode . '</div>';
        if ($count_geocode_fault) {
            echo '<div style="color:red">Не удалось прогеокодировать: ' . $count_geocode_fault . '</div>';
        }
    } else {
        echo '<div>Таблица с адресами пуста или заполнена полностью.</div>';
    }
    echo "<script language='JavaScript' type='text/javascript'>window.location.replace('http://test.courierhelper.ru/')</script>";
}

if (isset($_POST["sort_by_admin_areas"])) {
    
    $link = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_dbname);
    if (!$link) {
        echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
        echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }
    mysqli_set_charset($link, "utf8");
    
    echo "<form action=\"\" method=\"post\">"; // ??
    
    /******
     * ЦАО
     *******/
    
    $query = "SELECT * FROM $mysql_tablename WHERE adminArea = 'ЦАО' ORDER BY district, CASE WHEN deliveryTimeLimit = '' OR deliveryTimeLimit IS NULL THEN 2 ELSE 1 END, deliveryTimeLimit";
    $result = mysqli_query($link, $query) or die('Запрос не удался: ' . mysqli_error($link));
    $query2 = "SELECT * FROM $mysql_courierstablename" or die('Не удалось загрузить курьеров' . mysqli_error($link));
    
    if (mysqli_num_rows($result) != 0) {
        echo "<h3>ЦАО</h3>";
        echo '<table class="table table-sm table-bordered table-hover">';
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo '<td>' . $row['orderNumber'] . '</td>';
            echo '<td>' . $row['deliveryAddress'] . '</td>';
            echo '<td>' . $row['district'] . '</td>';
            echo '<td>' . $row['deliveryUndergroundStation1'] . ' (' . $row['deliveryUndergroundStation1Distance'] . ') </td>';
            echo '<td>' . $row['deliveryUndergroundStation2'] . ' (' . $row['deliveryUndergroundStation2Distance'] . ') </td>';
            echo '<td>' . $row['deliveryTimeLimit'] . '</td>';
            echo '<td><select name="choose_courier[' . $row['orderNumber'] . ']" required>';
            echo "<option value=\"$row[deliveryCourier]\">$row[deliveryCourier]</option>";
            $result2 = mysqli_query($link, $query2) or die('Запрос не удался' . mysqli_error($link));
            while ($row2 = mysqli_fetch_array($result2)) {
                if ($row2[name] != $row[deliveryCourier]) {
                    echo "<option value=\"$row2[name]\">$row2[name]</option>";
                }
            }
            echo "</select></td>";
            echo "</tr>";
        }
        echo "</table>";

        // Освобождаем память от результата
        mysqli_free_result($result);
        mysqli_free_result($result2);
    }
    
    /******
     * ЗАО
     ******/
    
    $query = "SELECT * FROM $mysql_tablename WHERE adminArea = 'ЗАО' ORDER BY district, CASE WHEN deliveryTimeLimit = '' OR deliveryTimeLimit IS NULL THEN 2 ELSE 1 END, deliveryTimeLimit";
    $result = mysqli_query($link, $query) or die('Запрос не удался: ' . mysqli_error($link));
    $query2 = "SELECT * FROM $mysql_courierstablename" or die('Не удалось загрузить курьеров' . mysqli_error($link));
    
    if (mysqli_num_rows($result) != 0) {
        echo "<h3>ЗАО</h3>";
        echo '<table class="table table-sm table-bordered table-hover">';
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo '<td>' . $row['orderNumber'] . '</td>';
            echo '<td>' . $row['deliveryAddress'] . '</td>';
            echo '<td>' . $row['district'] . '</td>';
            echo '<td>' . $row['deliveryUndergroundStation1'] . ' (' . $row['deliveryUndergroundStation1Distance'] . ') </td>';
            echo '<td>' . $row['deliveryUndergroundStation2'] . ' (' . $row['deliveryUndergroundStation2Distance'] . ') </td>';
            echo '<td>' . $row['deliveryTimeLimit'] . '</td>';
            echo '<td><select name="choose_courier[' . $row['orderNumber'] . ']" required>';
            echo "<option value=\"$row[deliveryCourier]\">$row[deliveryCourier]</option>";
            $result2 = mysqli_query($link, $query2) or die('Запрос не удался' . mysqli_error($link));
            while ($row2 = mysqli_fetch_array($result2)) {
                if ($row2[name] != $row[deliveryCourier]) {
                    echo "<option value=\"$row2[name]\">$row2[name]</option>";
                }
            }
            echo "</select></td>";
            echo "</tr>";
        }
        echo "</table>";

        // Освобождаем память от результата
        mysqli_free_result($result);
        mysqli_free_result($result2);
    }
    
    /*******
     * CЗАО
     *******/
    
    $query = "SELECT * FROM $mysql_tablename WHERE adminArea = 'СЗАО' ORDER BY district, CASE WHEN deliveryTimeLimit = '' OR deliveryTimeLimit IS NULL THEN 2 ELSE 1 END, deliveryTimeLimit";
    $result = mysqli_query($link, $query) or die('Запрос не удался: ' . mysqli_error($link));
    $query2 = "SELECT * FROM $mysql_courierstablename" or die('Не удалось загрузить курьеров' . mysqli_error($link));
    
    if (mysqli_num_rows($result) != 0) {
        echo "<h3>СЗАО</h3>";
        echo '<table class="table table-sm table-bordered table-hover">';
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo '<td>' . $row['orderNumber'] . '</td>';
            echo '<td>' . $row['deliveryAddress'] . '</td>';
            echo '<td>' . $row['district'] . '</td>';
            echo '<td>' . $row['deliveryUndergroundStation1'] . ' (' . $row['deliveryUndergroundStation1Distance'] . ') </td>';
            echo '<td>' . $row['deliveryUndergroundStation2'] . ' (' . $row['deliveryUndergroundStation2Distance'] . ') </td>';
            echo '<td>' . $row['deliveryTimeLimit'] . '</td>';
            echo '<td><select name="choose_courier[' . $row['orderNumber'] . ']" required>';
            echo "<option value=\"$row[deliveryCourier]\">$row[deliveryCourier]</option>";
            $result2 = mysqli_query($link, $query2) or die('Запрос не удался' . mysqli_error($link));
            while ($row2 = mysqli_fetch_array($result2)) {
                if ($row2[name] != $row[deliveryCourier]) {
                    echo "<option value=\"$row2[name]\">$row2[name]</option>";
                }
            }
            echo "</select></td>";
            echo "</tr>";
        }
        echo "</table>";

        // Освобождаем память от результата
        mysqli_free_result($result);
        mysqli_free_result($result2);
    }
    
    /******
     * САО
     ******/
    
    $query = "SELECT * FROM $mysql_tablename WHERE adminArea = 'САО' ORDER BY district, CASE WHEN deliveryTimeLimit = '' OR deliveryTimeLimit IS NULL THEN 2 ELSE 1 END, deliveryTimeLimit";
    $result = mysqli_query($link, $query) or die('Запрос не удался: ' . mysqli_error($link));
    $query2 = "SELECT * FROM $mysql_courierstablename" or die('Не удалось загрузить курьеров' . mysqli_error($link));
    
    if (mysqli_num_rows($result) != 0) {
        echo "<h3>САО</h3>";
        echo '<table class="table table-sm table-bordered table-hover">';
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo '<td>' . $row['orderNumber'] . '</td>';
            echo '<td>' . $row['deliveryAddress'] . '</td>';
            echo '<td>' . $row['district'] . '</td>';
            echo '<td>' . $row['deliveryUndergroundStation1'] . ' (' . $row['deliveryUndergroundStation1Distance'] . ') </td>';
            echo '<td>' . $row['deliveryUndergroundStation2'] . ' (' . $row['deliveryUndergroundStation2Distance'] . ') </td>';
            echo '<td>' . $row['deliveryTimeLimit'] . '</td>';
            echo '<td><select name="choose_courier[' . $row['orderNumber'] . ']" required>';
            echo "<option value=\"$row[deliveryCourier]\">$row[deliveryCourier]</option>";
            $result2 = mysqli_query($link, $query2) or die('Запрос не удался' . mysqli_error($link));
            while ($row2 = mysqli_fetch_array($result2)) {
                if ($row2[name] != $row[deliveryCourier]) {
                    echo "<option value=\"$row2[name]\">$row2[name]</option>";
                }
            }
            echo "</select></td>";
            echo "</tr>";
        }
        echo "</table>";

        // Освобождаем память от результата
        mysqli_free_result($result);
        mysqli_free_result($result2);
    }
    
    /******
     * СВАО
     ******/
    
    $query = "SELECT * FROM $mysql_tablename WHERE adminArea = 'СВАО' ORDER BY district, CASE WHEN deliveryTimeLimit = '' OR deliveryTimeLimit = NULL THEN 2 ELSE 1 END, deliveryTimeLimit";
    $result = mysqli_query($link, $query) or die('Запрос не удался: ' . mysqli_error($link));
    $query2 = "SELECT * FROM $mysql_courierstablename" or die('Не удалось загрузить курьеров' . mysqli_error($link));
    
    if (mysqli_num_rows($result) != 0) {
        echo "<h3>СВАО</h3>";
        echo '<table class="table table-sm table-bordered table-hover">';
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo '<td>' . $row['orderNumber'] . '</td>';
            echo '<td>' . $row['deliveryAddress'] . '</td>';
            echo '<td>' . $row['district'] . '</td>';
            echo '<td>' . $row['deliveryUndergroundStation1'] . ' (' . $row['deliveryUndergroundStation1Distance'] . ') </td>';
            echo '<td>' . $row['deliveryUndergroundStation2'] . ' (' . $row['deliveryUndergroundStation2Distance'] . ') </td>';
            echo '<td>' . $row['deliveryTimeLimit'] . '</td>';
            echo '<td><select name="choose_courier[' . $row['orderNumber'] . ']" required>';
            echo "<option value=\"$row[deliveryCourier]\">$row[deliveryCourier]</option>";
            $result2 = mysqli_query($link, $query2) or die('Запрос не удался' . mysqli_error($link));
            while ($row2 = mysqli_fetch_array($result2)) {
                if ($row2[name] != $row[deliveryCourier]) {
                    echo "<option value=\"$row2[name]\">$row2[name]</option>";
                }
            }
            echo "</select></td>";
            echo "</tr>";
        }
        echo "</table>";

        // Освобождаем память от результата
        mysqli_free_result($result);
        mysqli_free_result($result2);
    }
    
    /******
     * ВАО
     ******/
    
    $query = "SELECT * FROM $mysql_tablename WHERE adminArea = 'ВАО' ORDER BY district, CASE WHEN deliveryTimeLimit = '' OR deliveryTimeLimit IS NULL THEN 2 ELSE 1 END, deliveryTimeLimit";
    $result = mysqli_query($link, $query) or die('Запрос не удался: ' . mysqli_error($link));
    $query2 = "SELECT * FROM $mysql_courierstablename" or die('Не удалось загрузить курьеров' . mysqli_error($link));
    
    if (mysqli_num_rows($result) != 0) {
        echo "<h3>ВАО</h3>";
        echo '<table class="table table-sm table-bordered table-hover">';
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo '<td>' . $row['orderNumber'] . '</td>';
            echo '<td>' . $row['deliveryAddress'] . '</td>';
            echo '<td>' . $row['district'] . '</td>';
            echo '<td>' . $row['deliveryUndergroundStation1'] . ' (' . $row['deliveryUndergroundStation1Distance'] . ') </td>';
            echo '<td>' . $row['deliveryUndergroundStation2'] . ' (' . $row['deliveryUndergroundStation2Distance'] . ') </td>';
            echo '<td>' . $row['deliveryTimeLimit'] . '</td>';
            echo '<td><select name="choose_courier[' . $row['orderNumber'] . ']" required>';
            echo "<option value=\"$row[deliveryCourier]\">$row[deliveryCourier]</option>";
            $result2 = mysqli_query($link, $query2) or die('Запрос не удался' . mysqli_error($link));
            while ($row2 = mysqli_fetch_array($result2)) {
                if ($row2[name] != $row[deliveryCourier]) {
                    echo "<option value=\"$row2[name]\">$row2[name]</option>";
                }
            }
            echo "</select></td>";
            echo "</tr>";
        }
        echo "</table>";

        // Освобождаем память от результата
        mysqli_free_result($result);
        mysqli_free_result($result2);
    }
    
    /******
     * ЮВАО
     ******/
    
    $query = "SELECT * FROM $mysql_tablename WHERE adminArea = 'ЮВАО' ORDER BY district, CASE WHEN deliveryTimeLimit = '' OR deliveryTimeLimit = NULL THEN 2 ELSE 1 END, deliveryTimeLimit";
    $result = mysqli_query($link, $query) or die('Запрос не удался: ' . mysqli_error($link));
    $query2 = "SELECT * FROM $mysql_courierstablename" or die('Не удалось загрузить курьеров' . mysqli_error($link));
    
    if (mysqli_num_rows($result) != 0) {
        echo "<h3>ЮВАО</h3>";
        echo '<table class="table table-sm table-bordered table-hover">';
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo '<td>' . $row['orderNumber'] . '</td>';
            echo '<td>' . $row['deliveryAddress'] . '</td>';
            echo '<td>' . $row['district'] . '</td>';
            echo '<td>' . $row['deliveryUndergroundStation1'] . ' (' . $row['deliveryUndergroundStation1Distance'] . ') </td>';
            echo '<td>' . $row['deliveryUndergroundStation2'] . ' (' . $row['deliveryUndergroundStation2Distance'] . ') </td>';
            echo '<td>' . $row['deliveryTimeLimit'] . '</td>';
            echo '<td><select name="choose_courier[' . $row['orderNumber'] . ']" required>';
            echo "<option value=\"$row[deliveryCourier]\">$row[deliveryCourier]</option>";
            $result2 = mysqli_query($link, $query2) or die('Запрос не удался' . mysqli_error($link));
            while ($row2 = mysqli_fetch_array($result2)) {
                if ($row2[name] != $row[deliveryCourier]) {
                    echo "<option value=\"$row2[name]\">$row2[name]</option>";
                }
            }
            echo "</select></td>";
            echo "</tr>";
        }
        echo "</table>";

        // Освобождаем память от результата
        mysqli_free_result($result);
        mysqli_free_result($result2);
    }
    
    /******
     * ЮАО
     ******/
    
    $query = "SELECT * FROM $mysql_tablename WHERE adminArea = 'ЮАО' ORDER BY district, CASE WHEN deliveryTimeLimit = '' OR deliveryTimeLimit IS NULL THEN 2 ELSE 1 END, deliveryTimeLimit";
    $result = mysqli_query($link, $query) or die('Запрос не удался: ' . mysqli_error($link));
    $query2 = "SELECT * FROM $mysql_courierstablename" or die('Не удалось загрузить курьеров' . mysqli_error($link));
    
    if (mysqli_num_rows($result) != 0) {
        echo "<h3>ЮАО</h3>";
        echo '<table class="table table-sm table-bordered table-hover">';
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo '<td>' . $row['orderNumber'] . '</td>';
            echo '<td>' . $row['deliveryAddress'] . '</td>';
            echo '<td>' . $row['district'] . '</td>';
            echo '<td>' . $row['deliveryUndergroundStation1'] . ' (' . $row['deliveryUndergroundStation1Distance'] . ') </td>';
            echo '<td>' . $row['deliveryUndergroundStation2'] . ' (' . $row['deliveryUndergroundStation2Distance'] . ') </td>';
            echo '<td>' . $row['deliveryTimeLimit'] . '</td>';
            echo '<td><select name="choose_courier[' . $row['orderNumber'] . ']" required>';
            echo "<option value=\"$row[deliveryCourier]\">$row[deliveryCourier]</option>";
            $result2 = mysqli_query($link, $query2) or die('Запрос не удался' . mysqli_error($link));
            while ($row2 = mysqli_fetch_array($result2)) {
                if ($row2[name] != $row[deliveryCourier]) {
                    echo "<option value=\"$row2[name]\">$row2[name]</option>";
                }
            }
            echo "</select></td>";
            echo "</tr>";
        }
        echo "</table>";

        // Освобождаем память от результата
        mysqli_free_result($result);
        mysqli_free_result($result2);
    }
    
    /******
     * ЮЗАО
     ******/
    
    $query = "SELECT * FROM $mysql_tablename WHERE adminArea = 'ЮЗАО' ORDER BY district, CASE WHEN deliveryTimeLimit = '' OR deliveryTimeLimit IS NULL THEN 2 ELSE 1 END, deliveryTimeLimit";
    $result = mysqli_query($link, $query) or die('Запрос не удался: ' . mysqli_error($link));
    $query2 = "SELECT * FROM $mysql_courierstablename" or die('Не удалось загрузить курьеров' . mysqli_error($link));
    
    if (mysqli_num_rows($result) != 0) {
        echo "<h3>ЮЗАО</h3>";
        echo '<table class="table table-sm table-bordered table-hover">';
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo '<td>' . $row['orderNumber'] . '</td>';
            echo '<td>' . $row['deliveryAddress'] . '</td>';
            echo '<td>' . $row['district'] . '</td>';
            echo '<td>' . $row['deliveryUndergroundStation1'] . ' (' . $row['deliveryUndergroundStation1Distance'] . ') </td>';
            echo '<td>' . $row['deliveryUndergroundStation2'] . ' (' . $row['deliveryUndergroundStation2Distance'] . ') </td>';
            echo '<td>' . $row['deliveryTimeLimit'] . '</td>';
            echo '<td><select name="choose_courier[' . $row['orderNumber'] . ']" required>';
            echo "<option value=\"$row[deliveryCourier]\">$row[deliveryCourier]</option>";
            $result2 = mysqli_query($link, $query2) or die('Запрос не удался' . mysqli_error($link));
            while ($row2 = mysqli_fetch_array($result2)) {
                if ($row2[name] != $row[deliveryCourier]) {
                    echo "<option value=\"$row2[name]\">$row2[name]</option>";
                }
            }
            echo "</select></td>";
            echo "</tr>";
        }
        echo "</table>";

        // Освобождаем память от результата
        mysqli_free_result($result);
        mysqli_free_result($result2);
    }
    
    echo '<input class="btn btn-lg btn-primary" type="submit" name="send_to_couriers" value="Отправить курьерам">';
    echo "</form><br />";
    
    // Закрываем соединение
    mysqli_close($link);
}

if (isset($_POST["send_to_couriers"])) {
    $link = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_dbname);
    if (!$link) {
        echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
        echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }
    mysqli_set_charset($link, "utf8");
    foreach ($_REQUEST["choose_courier"] as $key => $value) {
        mysqli_query($link, "UPDATE $mysql_tablename SET deliveryCourier = '" . mysqli_real_escape_string($link, $value) . "' WHERE orderNumber = '" . mysqli_real_escape_string($link, $key) . "';") or die('Не удалось записать курьеров: ' . mysqli_error($link));
    }
    mysqli_close($link);
    
}
?>

    <!-- Site footer -->
      <footer class="footer">
        <p>CourierHelper.ru by Ivan Makachka &copy; 2017</p>
        
      </footer>

    </div></center> <!-- /container -->
    </body>
</html>
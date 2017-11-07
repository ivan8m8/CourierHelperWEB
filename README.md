# Описание
**CourierHelper** – программное обеспечение, которое состоит из **web-приложения для менеджера курьеров** и **Anadroid-приложения для курьеров**.

### Как работает web-приложение
   1. менеджер импортирует доставки (из exel-файла или sql)
   2. CourierHelper для каждой доставки определяет
      * административный округ
      * район
      * две ближайшие станции метро и расстояния до них
   3. CourierHelper максимально удобно сортирует доставки внутри каждого административного округа сначала по районам, а затем по времени доставки
   4. менеджер назначает курьеров сортированным доставкам
   5. CourierHelper отправляет эти доставки курьерам на Android-устройства

Как выглядит сортировка:
картинка тут

### Возможности мобильного приложения для Android
   * звонок клиенту в один клик
   * sms клиенту в один клик
   * sms сразу всем клиентам в один клик

# Web-часть

В данном репозитории хранятся файлы, которые должны быть размещены на сервере. 
(если необходимо, рады предоставить свой сервер бесплатно)

**Внимание!**
WEB-часть проекта реализована сыро, однако работоспособность полностью проверена и безопасноть гранатирована.

## index.php
Данная страница предназначена для распределения доставок между курьерами.

## jsonforcouriers.php
Данная страница предназначена для преобразования данных из MySQL-таблицы и последующего их вывода.

## couriersresponse.php
Данная страница предназначена для принятия данных от курьеров (успешное выполненение доставки).
Обратите внимание на переменную ``$secureCode``. Она должна совпадать с переменной, указанной в файле в Android-приложении.
Такой подход был выбран для обеспечения повышенной безопасности.

# Безопасность
Во всех трёх php-файлах необходимо указать данные для доступа к MySQL-таблице, где хранятся данные о доставках. Дополнительно в файле index.php необходимо указать название для таблицы, в которой перечилены курьеры. Как Вы можете видеть, данные никуда не передаются и необходимы лишь

# Установка
Со стороны сервера необходимо налачие следующих таблиц:
Если у Вас другая структура, необходимо, будет модифицировать те три php-файла.
Однако, это не должно занять у Вас много времени.

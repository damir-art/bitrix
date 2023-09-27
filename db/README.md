# CDatabase
- https://dev.1c-bitrix.ru/api_help/main/reference/cdatabase/index.php
- SQL сниппеты: https://nikaverro.ru/blog/bitrix/sql/
- СОздание таблицы в БД: https://blog.lisogorsky.ru/insert-table-bitrix

`CDatabase` - класс для работы с базой данной.

    // Старое API
    global $DB;
    $record = $DB->Query("select 1+1;")->Fetch();
    AddMessage2Log($record);

    // D7
    use Bitrix\Main\Application;
    use Bitrix\Main\Diag\Debug;

    $record = Application::getConnection()
      ->query("select 1+1;")
      ->fetch();
    Debug::writeToFile($record);

При каждой загрузке страницы автоматически создается переменная `$DB`, содержащая глобальный объект, который является объектом класса `CDatabase`. Используя этот объект можно проводить все действия с базой данных. Класс для работы с базой данных `CDatabase` содержит множество методов.

Работа с базой данных в CMS 1C-Битрикс осуществляется с помощью глобального объекта `global $DB`. Класс позволяет осуществлять различные операции с базой данных, такие как например добавление, удаление, обновление и вывод данных. Аналог в CMS WrodPress - класс `global $wpdb`.

    require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");
    global $DB;

После этого, с помощью метода Query, можно управлять любыми таблицами в текущей базе данных, даже теми, которые вы создали вручную. Точно так же, как если бы вы использовали для подключения PHP-функцию `mysql_connect()`.

Пример:

    require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");
    global $DB;

    $results = $DB->Query("SELECT * FROM `my_table`");

    while($row = $results->Fetch()){
      echo '<pre>';
      print_r($row);
      echo '</pre>';
    }

## Метод CDatabase::Query
Метод выполняет запрос к базе данных и в случае успешного выполнения возвращает результат в виде объекта класса CDBResult.

    mixed
    CDatabase::Query(
      string sql,
      bool ignore_errors=false,
      string error_position="",
      array Options=array() 
    )

Параметры:
- **sql** - строка SQL запроса.
- **ignore_errors** - игнорирование возникновения ошибок при выполнении. Если `true`, то в случае ошибки функция возвращает `false`. Если параметр `ignore_errors` равен `false`, то в случае ошибки функция прекращает выполнение всей страницы. Необязательный параметр, по умолчанию - `false`.
- **error_position** - строка идентифицирующая позицию в коде, откуда был вызван данный метод (CDatabase::Query). Если в SQL запросе будет ошибка и если в файле `/bitrix/php_interface/dbconn.php` установлена переменная `$DBDebug=true;`, то на экране будет выведена данная информация и сам SQL запрос, так же является необязательным параметром.
- **Options** - дополнительные опции, необязательный параметр, появился в методе начиная с версии 9.5.10.

Если параметр `ignore_errors` равен `true`, и возникла ошибка при запросе, то метод вернет `false`. В ином случае, метод прерывает выполнение страницы, выполняя перед этим следующие действия:
- Вызов функции `AddMessage2Log`,
- Если текущий пользователь является администратором сайта, либо в файле `/bitrix/php_interface/dbconn.php` была инициализирована переменная `$DBDebug=true;`, то на экран будет выведен полный текст ошибки, в противном случае будет вызвана функция `SendError`.
- Будет подключен файл `/bitrix/php_interface/dbquery_error.php`, если он не существует, то будет подключен файл `/bitrix/modules/main/include/dbquery_error.php`.

Пример:

Пример запроса:

    require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");
    global $DB;

    $results = $DB->Query("SELECT `VALUE` FROM `b_option` WHERE `NAME` LIKE 'email_from'");
    while($row = $results->Fetch()) {
      echo '<pre>';
      print_r($row);
      echo '</pre>';
    }

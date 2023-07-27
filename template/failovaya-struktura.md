# Файловая структура
Страница и порядок её выполнения - https://dev.1c-bitrix.ru/api_help/main/general/page/pageplan.php

Страницы могут выводить статическую информацию или динамическую.

Системные папки и файлы:
- /bitrix/ - компоненты, модули
- /upload/ - загружаемые файлы
- .access.php - указания по правам на папки
- .section.php - информация о текущем разделе
- urlrewrite.php - правила для работы чпу

## /bitrix/php_interface/
`init.php` - подкличается при каждом хите (открытии страницы), в начале страницы. Хранит код исполняемый на каждой странице. Желательно код подключать через `require_once()`, использовать `__autoload()`. Если его нет, то его нужно создать.

Пример хранения функций для `init.php`
- /bitrix/php_interface/include/functions.php

Код `functions.php`:

    // функция принимает три параметра
    function dump($var, $die = false, $all = false) {
        global $USER;
        if ($USER->IsAdmin || ($all == true)) {
        ?>
            <pre>
            <?php var_dump($var); ?>
            </pre>
        <?php
        }
        if($die) {
            die;
        }
    }

Код `init.php`:

    if( file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/include/functions.php" )) {
        require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/include/functions.php");
    }

## init.php для сайта
Создаём файл `init.php` для конкретного файла.

- /bitrix/php_interface/id_site/init.php

## Закрытие сайта
Закрываем публичную часть сайта на некоторое время `php_interface/include/site_closed.php`. Настраивается в главном модуле:

    Админ > Настройки > Настройки продукта > Настройки модулей > Главный модуль > Временное закрытие публичной части сайта

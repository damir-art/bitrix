# php_interface
Папка где хранятся важные файлы проекта. Располагается в `/bitrix/php_interface/` и в `/local/php_interface/`.

Там могут располагаться следующие файлы:
- init.php - дополнительные параметры
- /id-site/init.php - подключается на определенном сайте
- dbconn.php - параметры соединения с базой данных, с 20.9 версии параметры берутся из `/bitrix/.settings.php`
- after_connect.php - подключается сразу после создания соединения с БД
- after_connect_d7.php
- dbconn_error.php - настройка вида сообщений об ошибке соединения к БД
- dbquery_error.php - настройка вида сообщений об ошибке запроса к БД

## Технические работы сайта
Дизайн сайта при его закрытии, при технических работах. Копируем `/bitrix/modules/main/include/site_closed.php` помещаем в `/bitrix/php_interface/include/`.

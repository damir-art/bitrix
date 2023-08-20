# Битрикс D7
https://www.intervolga.ru/blog/projects/d7-analogi-lyubimykh-funktsiy-v-1s-bitriks/ - примеры D7 со старыми аналогами  
https://dev.1c-bitrix.ru/community/blogs/dev_bx/connection-of-css-and-js-files-in-the-component-.php - подключение стилей в шаблоне компонентов  
https://dev.1c-bitrix.ru/community/webdev/user/87386/blog/11342/ - кеширование  
https://www.youtube.com/watch?v=1_xYUQzQHj8

- Стили и скрипты:     `style-script.md`
- Подключение модулей: `module.md`
- Локализация:         `local.md`
- Файловая структура:  `file.md`
- Отладка:             `debug.md`
- $_GET, $_POST:       `get-post.md`
- Cookie:              `cookie.md`
- Почтовые события:    `send.php`
- Разное
  - `header-php.md`

D7 - новый API Битрикс, с использование простарнства имён. Перед началом разработки, убедитесь что в выбранном вами модуле (чье АПИ вы будете использовать) имеются классы и методы нового ядра D7.

Отличия от старого API:
- поддержка базы данных MySQL
- используется ORM
- поддержка ООП, весь код в одном месте, классе, наборе классов
- единообразный код, однаковые названия, вызовы, параметры, возрат
- поддержка простраства имён
- отсутствие глобальных переменных
- унифицированные события
- поддержка автозагрузки (autoload)

## Настройка параметров ядра
Располагается в файле `/bitrix/.settings.php`. Раньше располагался в `/bitrix/php_interface/dbconn.php`

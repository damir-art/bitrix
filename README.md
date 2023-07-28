# 1С-Битрикс
Руководство по темизации 1С-Битрикс

## Установка 1С-Битрикс
- OpenServer:
  - Apache_2.4-PHP_7.2-7.4
  - PHP_7.4
  - MySQL-5.7
- Скрипт проверки сервера: https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=135&LESSON_ID=5412
- Создаём БД в phpMyAdmin (root без пароля) utf8_general_ci
- Скрипт установки Битрикс: https://www.1c-bitrix.ru/download/cms.php
- 1C-Битрикс: Управление сайтом -> Бизнес
- Снимаем галочку "Регистрация продукта"
- База данных:
  - имя пользователя root
  - тип БД по-умолчанию или innoDB
- Авторизация: http://site.loc/bitrix/admin/index.php

## После установки
- Админ: Настройки > Инструменты > Проверка системы
- В .htaccess: `php_value max_input_vars 11000`
- Режим работы MySQL: `Ошибка! innodb_strict_mode=ON, требуется OF` (перед исправлением нужно решить проблему со стилями)
- `Админ > Настройки > Настройки продукта > Настройки модулей > Главный модуль`:
  - Название сайта: Имя компании
  - Оптимизация CSS: все галочки убраны
- `Админ > Настройки > Настройки продукта > Настройки модулей > Информационные блоки`:
  - Совместный просмотр разделов и элементов

## Проблема со стилями внутри админки
Например если не прогрузились стили в элементах новостей и т.п.
- OpenServer > Дополнительно > Конфигурация > Apache_2.4-PHP_7.2-7.4
- В файле Apache_2.4-PHP_7.2-7.4_server.conf ищем `well-known` заменяем на `well-known|default`
- Перезагружаем OpenServer

### Для Apache 2.4 PHP 7.2-7.4 Nginx_1.19
- Если не прогрузились стили админки Битрикс: https://ospanel.io/forum/viewtopic.php?f=1&t=1818&start=10
- Нужно поставить модуль `Apache 2.4 PHP 7.2-7.4 Nginx_1.19`
- Открыть файл `Apache_2.4-PHP_7.2-7.4+Nginx_1.19_vhostn.conf`
- По адресу: `Флажок > Дополнительно > Конфигурация`

Закомментировать следующий код (в поздних версиях код начинается с `location`):

    # Disable access to hidden files/folders
    # if ($uri ~* /\.(?!well-known)) {
    #     return 404;
    # }

PS: попробовать сначала `well-known` заменить на `well-known|default`.

## Разное
- Часть данных Битрикс хранит в файлах, а остальное в БД

## Безонасность
- запретить регистрацию пользователей "Настройка главного модуля",

## Настройки
Настройка продукта:
- Сайты > Список сайтов - можно создать несколько сайтов.

## Ссылки
- Документация: https://dev.1c-bitrix.ru/docs/php.php
- Видеокурс: https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=95

https://www.youtube.com/watch?v=FjVs7JVOD3U&list=PL8Kt1m2oRch5M1bA334BRwFybdDe38Byd&index=6  
https://www.youtube.com/watch?v=4pawCupacWo&list=PLY4rE9dstrJyqAWUVlT7PLGjdmPEwhfg6&index=1  
https://hmarketing.ru/blog/bitrix/rabota-s-elementami-infoblokov-cherez-orm/  
https://hmarketing.ru/blog/bitrix/  
https://hmarketing.ru/blog/php/  

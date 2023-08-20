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

## Безопасность
- запретить регистрацию пользователей "Настройка главного модуля",

## Настройки
Настройка продукта:
- Сайты > Список сайтов - можно создать несколько сайтов.

## Ссылки
- Документация: https://dev.1c-bitrix.ru/docs/php.php
- Видеокурс: https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=95
- Курс Битрикс 24: https://www.bitrix24.ru/features/pages/training.php
- Приложения REST и интеграция с Б24: https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=99
- Документация REST:     https://dev.1c-bitrix.ru/rest_help/
- Документация REST API: https://www.bitrix24.ru/apps/app/bitrix.restapi/
- Курс REST API:         https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=266
- Бот платформа Б24:     https://dev.1c-bitrix.ru/learning/course/?COURSE_ID=93

https://www.acrit-studio.ru/pantry-programmer/knowledge-base/  
https://href.kz/blog/list/bitrix  
https://www.youtube.com/watch?v=Eid7ggQrM5E&list=PL8Kt1m2oRch5M1bA334BRwFybdDe38Byd&index=8  
https://www.youtube.com/watch?v=4pawCupacWo&list=PLY4rE9dstrJyqAWUVlT7PLGjdmPEwhfg6&index=1  
https://hmarketing.ru/blog/bitrix/rabota-s-elementami-infoblokov-cherez-orm/  
https://hmarketing.ru/blog/bitrix/  
https://hmarketing.ru/blog/php/  

Создаём форму: https://doka.guide/js/deal-with-forms/  
PHP: https://snipp.ru/php?page=8  
20 PHP правил для начинающих: https://ydmitry.ru/blog/php-20-praktik-kotorym-stoit-sledovat/

Подключение к Битрикс24: https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=41&CHAPTER_ID=08545  
События Битрикс: https://www.youtube.com/@bx-vadim/videos  
Вебхуки Б24: https://www.youtube.com/watch?v=A4J3rChhhYY  
Интеграция Б24 + сайт: https://www.youtube.com/watch?v=gfFk2AB-X-U  
Создаём приложение для Б24 через REST API: https://www.youtube.com/watch?v=4-AHCMKmvZo  
Интеграции Б24 и сайт: https://www.intervolga.ru/blog/bitrix24/integratsiya-bitriks24-s-saytom-vosem-krasivykh-i-ne-ochen-sposobov/?ysclid=lkppso97f290787591  

MySQL https://www.youtube.com/watch?v=xeDYyIkb2GQ&list=PLJmPn1WIe0aCFJfxp2g0EMIcPtqNR28Q1

Инструкция по установке на виртуальную машину: https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=34&LESSON_ID=13032 (внизу страницы)

Создание интернет магазина, торговых предложений, умного фильтра, спецпредложений: https://1ps.ru/blog/sites/2019/sozdanie-internet-magazina-na-bitriks/

Здравствуйте
Всё выполнил
Сложно морально, переоценил свои возможности
Желаю успехов

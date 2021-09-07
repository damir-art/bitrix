# 1С-Битрикс
Руководство по темизации 1С-Битрикс

## Установка 1С-Битрикс
- OpenServer PHP 7.4, MySQL 5.7
- Скрипт проверки сервера: https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=135&LESSON_ID=5412
- Скрипт установки Битрикс: https://www.1c-bitrix.ru/download/cms.php
- Выбираем Бизнес
- Снимаем галочку "Регистрация продукта"
- Создание базы данных (если создавать то utf8_general_ci):
    - имя пользователя root
    - тип БД innoDB
- Авторизация: http://okna.loc/bitrix/admin/index.php

## После установки
- Админ: Настройки > Инструменты > Проверка системы
- В .htaccess: php_value max_input_vars 11000

## Ссылки
- Документация: https://dev.1c-bitrix.ru/docs/php.php
- Видеокурс: https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=95

## Разное
- Часть данных Битрикс хранит в файлах, а остальное в БД

## Проблема со стилями внутри админки
- Если не прогрузились стили админки Битрикс: https://ospanel.io/forum/viewtopic.php?f=1&t=1818&start=10
- Нужно поставить модуль `Apache 2.4 PHP 7.2-7.4 Nginx_1.19`
- Открыть файл `Apache_2.4-PHP_7.2-7.4+Nginx_1.19_vhostn.conf`
- По адресу: `Флажок > Дополнительно > Конфигурация`

Закомментировать следующий код:

    # Disable access to hidden files/folders
    # if ($uri ~* /\.(?!well-known)) {
    #     return 404;
    # }

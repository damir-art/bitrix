# lang
С помощью папки lang внутри папки с темой, можно реализовывать мультиязычность темы. Язык сайта меняется в `Админ > Настройки > Настройки продукта > Сайты > Список сайтов > ID сайта > Параметры > Язык`.

    /lang/
        /en/
        /ru/
            description.php - описание темы
            footer.php - переводы футера
            header.php - переводы хедера
            logo.png

description.php, footer.php, header.php - файлы хранящие в себе массив, с уникальными ключами с переводами фраз.

## description.php

    $MESS["CFST_TEMPLATE_NAME"] = "Фиксированный";
    $MESS["CFST_TEMPLATE_DESC"] = "Легкий и светлый шаблон с фиксированной шириной.";

## header.php

    $MESS["CFT_MAIN"] = "На главную страницу";
    $MESS["CFT_SEARCH"] = "Поиск";
    $MESS["CFT_FEEDBACK"] = "Обратная связь";
    $MESS["CFT_NEWS"] = "Новости компании";
    $MESS["CFT_FEATURED"] = "Спецпредложение";

## footer.php

    $MESS["FOOTER_DISIGN"] = '<a href="http://www.1c-bitrix.ru" title="Работает на &laquo;1С-Битрикс: Управление сайтом&raquo;">Работает на &laquo;1С-Битрикс: Управление сайтом&raquo;</a>';

## Размещаем в шаблоне
В шаблоне выводим элементы массива:

    GetMessage('CFT_NEWS')

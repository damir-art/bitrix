# Создание шаблона (темы)
- В корне сайта создаём папку `local/templates/template_index/` шаблон главной страницы
- Внутри папки `/template_index/` создаём два файла:
    - header.php
    - footer.php
- В корне сайта изменяем `index.php`
- Наш шаблон появился в `Настройки > Настройки продукта > Сайты > Шаблоны сайтов`
- В админке переходим в `Настройки > Настройки продукта > Сайты > Список сайтов` и изменяем на свой шаблон

## header.php
В начале файла `header.php` помещаем код, который не позволит напрямую обратиться к данному файлу.

    <?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

## footer.php
В начале файла `footer.php` помещаем код, который не позволит напрямую обратиться к данному файлу.

    <?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

## index.php
В `index.php` записываем:

    <?php
        require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
        $APPLICATION->SetTitle("Пластиковые окна");
    ?>

    <p>
        CONTENT
    </p>

    <?php
        require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
    ?>

## Разное
- Нельзя изменять папки и файлы внутри `/bitrix/`.
- favicon.ico помещают в корень шаблона

# header.php
В header.php попадает код верстки шапки и левого сайдбара (если он есть).

Рассмотрим как подключать стили, скрипты, изображения.

Для начала импортируйте пространство имен `use Bitrix\Main\Page\Asset;`:

    <?php
        if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
        use Bitrix\Main\Page\Asset; // Импортируем пространство имён
    ?>

Чтобы подключения заработали обращаемся к методу `ShowHead()`:

    <head>
        <?php $APPLICATION->ShowHead(); ?> // Подключение мета-данных и внешних файлов

## Устанавливаем title
`<title><?php $APPLICATION->ShowTitle(); ?></title>` берется из index.php `$APPLICATION->SetTitle("Пластиковые окна");`

## Подключаем стили
$APPLICATION->SetAdditionalCSS() - устаревшая функция

    <?php
        if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
        use Bitrix\Main\Page\Asset; // Импортируем пространство имён
    ?>

    <head>
        <?php $APPLICATION->ShowHead(); ?> // Подключение мета-данных и внешних файлов
        <title><?php $APPLICATION->ShowTitle(); ?></title>
        <?php
            Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/css/bootstrap.css"); // код подключения CSS-файла
            Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/css/style.css"); 
        ?>

- SITE_TEMPLATE_PATH - путь к активному шаблону, открытой страницы

## Подключаем скрипты
$APPLICATION->AddHeadScript() - устаревшая функция

    <?php
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/bootstrap.bundle.js"); // код подключения JS-файла
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/custom.js");
    ?>

## Подключаем строки
Можно подключать отдельные мета-теги, фавиконку или шрифты гугл:

    Asset::getInstance()->addString('<meta name="viewport" content="width=device-width, initial-scale=1" />');

## Подключаем изображения

    <img src="<?php echo SITE_TEMPLATE_PATH ?>/img/brand.png" alt="" width="50" height="50" />

## Подключаем панель администратора

    <body>
        <?php $APPLICATION->ShowPanel(); ?>

## Разное
- Подключение через `Asset::getInstance()` оптимизирует файлы (объединяет, сжимает)
- Во время разработки сайта, можно отключить сжатие файлов: `Настройки > Настройки модулей > Главный модуль > Оптимизация CSS`
- Во время разработки сайта, можно отключить автокеширование: `Настройки > Настройки модулей > Автокеширование > Выключить автокеширование/Выключить управляемый кеш/Очистить все файлы кеша`
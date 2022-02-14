# header.php
В `header.php` попадает код верстки шапки и левого сайдбара (если он есть).

Рассмотрим как подключать стили, скрипты, изображения.

Для начала импортируйте пространство имен `use Bitrix\Main\Page\Asset;`:

    <?php
        if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); // Защищает от открытия файла из браузера напрямую
        use Bitrix\Main\Page\Asset; // Импортируем пространство имён, D7
        // Подключает библиотеку для использования Asset::getInstance()->addCss()
    ?>

    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="<?php echo LANG_CHARSET ?>">


Чтобы подключения заработали обращаемся к методу `ShowHead()`:

        <?php $APPLICATION->ShowHead(); ?> // Подключение мета-данных и внешних файлов
    </head>

`ShowHead()` - выводит стили, метатеги, скрипты.

## Устанавливаем title
    
    <title><?php $APPLICATION->ShowTitle(); ?></title>
    <?$APPLICATION->ShowMeta("keywords");?>
    <?$APPLICATION->ShowMeta("description");?>
    // Берется из index.php `$APPLICATION->SetTitle("Пластиковые окна");`

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

- `<?=SITE_TEMPLATE_PATH?>` - путь к корню шаблона
    -  /local/templates/test_template
- `<?=SITE_DIR?>` - путь к корню сайта
    - /
- `template_styles.css` - стили дизайна шаблона

## Подключаем скрипты
`$APPLICATION->AddHeadScript()` - устаревшая функция

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
        <div id="panel"><?php $APPLICATION->ShowPanel(); ?></div> <!-- div не нужен -->

## Разное
- Подключение через `Asset::getInstance()` оптимизирует файлы (объединяет, сжимает)
- Во время разработки сайта, можно отключить сжатие файлов: `Настройки > Настройки модулей > Главный модуль > Оптимизация CSS`
- Во время разработки сайта, также можно отключить автокеширование: `Настройки > Настройки модулей > Автокеширование > Выключить автокеширование/Выключить управляемый кеш/Очистить все файлы кеша`

# header.php
В `header.php` попадает код верстки шапки и левого сайдбара (если он есть).  
Рассмотрим как подключать стили, скрипты, изображения.  

Для начала импортируйте пространство имен `use Bitrix\Main\Page\Asset;`:

    <!DOCTYPE html>
    <html lang="<?php echo LANGUAGE_ID; ?>">
      <head>
        <meta charset="<?php echo LANG_CHARSET ?>"> // не нужно если есть ShowHead()

Чтобы подключения заработали обращаемся к методу `ShowHead()`:

        <?php $APPLICATION->ShowHead(); ?> // Подключение мета-данных и внешних файлов
      </head>

`ShowHead()` - выводит стили, метатеги, скрипты.

## Устанавливаем title
    
    <title><?php $APPLICATION->ShowTitle(); ?></title>
    // <?$APPLICATION->ShowMeta("keywords");?> // не нужно если есть в структуре
    // <?$APPLICATION->ShowMeta("description");?> // не нужно если есть в структуре
    // Берется из index.php `$APPLICATION->SetTitle("Пластиковые окна");` или из структуры

## Подключаем стили
`$APPLICATION->SetAdditionalCSS()` - подключение стилей:

    <?php $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/css/style.css" ); ?>

## Подключаем скрипты
`$APPLICATION->AddHeadScript()` - подключение скриптов:

    <?php $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/script.js" ); ?>

## Подключаем строки
Можно подключать отдельные мета-теги, фавиконку или шрифты гугл:

    <?php $APPLICATION->AddHeadString("<meta name='viewport' content='width=device-width, initial-scale=1'>"); ?>

## Подключаем изображения

    <img src="<?php echo SITE_TEMPLATE_PATH ?>/img/logo.png" alt="" width="50" height="50" />

Разное:
- `<?php echo SITE_TEMPLATE_PATH; ?>` - путь к корню шаблона: `/local/templates/test_template`,
- `<?php echo SITE_DIR; ?>` - путь к корню сайта: `/`,
- `template_styles.css` - стили дизайна шаблона.

## Подключаем панель администратора

    <body>
      <div id="panel">
        <?php $APPLICATION->ShowPanel(); ?>
      </div>

## Разное
- Подключение через `Asset::getInstance()` оптимизирует файлы (объединяет, сжимает)
- Во время разработки сайта, можно отключить сжатие файлов: `Настройки > Настройки модулей > Главный модуль > Оптимизация CSS`
- Во время разработки сайта, также можно отключить автокеширование: `Настройки > Настройки модулей > Автокеширование > Выключить автокеширование/Выключить управляемый кеш/Очистить все файлы кеша`

# header.php

Подключаем стили и скрипты:

Стили:

    <?php
      if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
      use Bitrix\Main\Page\Asset; // импортируем пространство имён D7
      // Подключает библиотеку для использования Asset::getInstance()->addCss()
    ?>

    <head>
      <title><?php $APPLICATION->ShowTitle(); ?></title>
      <?php
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/css/bootstrap.css"); // код подключения CSS-файла
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/css/style.css");
        Asset::getInstance()->addString("<meta name='viewport' content='width=device-width, initial-scale=1'>"); // подключение произвольной строки в head или например шрифтов гугл
      ?>
      <?php $APPLICATION->ShowHead(); ?> // Подключение мета-данных и внешних файлов, template_style.css
    </head>

Скрипты (при подключении через D7 можно вставить в head):

    <?php
      Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/bootstrap.bundle.js"); // код подключения JS-файла
      Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/custom.js");
    ?>

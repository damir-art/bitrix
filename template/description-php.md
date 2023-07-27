# description.php
description.php - файл описания шаблона, хранится в корне шаблона.

    <?php
        if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
    ?>
    <?php
        $arTemplate = Array(
            "NAME"=>GetMessage("CSST_TEMPLATE_NAME"), 
            "DESCRIPTION"=>GetMessage("CSST_TEMPLATE_DESC"), 
        );
    ?>

Вот так можно добавить описание шаблона сайта в админке:

    <?php
        if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

        $arTemplate = [
            "NAME" => 'Шаблон главной страницы сайта',
            "DESCRIPTION" => 'Описание шаблона',
        ]
    ?>

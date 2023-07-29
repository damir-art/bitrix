# Стартовый шаблон

## style.css

    section {
      background-color: #333;
      min-height: 50px;
    }
    h1 {
      color: #eee;
    }
    p {
      color: #eee;
    }

## header.php

    <?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
    <!DOCTYPE html>
    <html lang="<?php echo LANGUAGE_ID; ?>">
    <head>
      <title><?php $APPLICATION->ShowTitle(); ?></title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <?php $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/assets/bootstrap/bootstrap.css" ); ?>
      <?php $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/assets/style.css" ); ?>
      <?php $APPLICATION->ShowHead(); ?>
    </head>
    <body>
    <div id="panel">
      <?php $APPLICATION->ShowPanel(); ?>
    </div>
    <section class="section section__nav">
      <div class="container">
        <h1><?php $APPLICATION->ShowTitle("false"); ?></h1>
      </div>
    </section>

    <section class="section section__header">
      <div class="container">
        <div class="row">
          <div class="col">
            <img src="<?php echo SITE_TEMPLATE_PATH ?>/assets/image/konteiner.png" alt="" width="600" height="400" />
          </div>
        </div>
      </div>
    </section>

## index.php

    <?php
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
    $APPLICATION->SetPageProperty("description", "Описание страницы");
    $APPLICATION->SetPageProperty("keywords", "ключевые слова");
    $APPLICATION->SetPageProperty("title", "Заголовок страницы");
    $APPLICATION->SetTitle("Заголовок статьи");
    ?>

    <section>
      <div class="container">
        <div class="row">
          <div class="col">
            <h1>Добро пожаловать!</h1>
            <p>
              Сайт компании супер дупер.</p>
          </div>
        </div>
      </div>
    </section>

    <?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>

## footer.php

    <?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
        <?php $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/assets/bootstrap/bootstrap.bundle.js" ); ?>
        <?php $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/assets/custom.js" ); ?>
      </body>
    </html>

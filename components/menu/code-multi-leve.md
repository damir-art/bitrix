# Код многоуровневого меню
Код многоуровневого меню с использованием бутстрап 3.

    <?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

    <style>
    .main-menu .navbar-brand {
      padding: 0 15px;
      font-size: 0;
      line-height: 0;
    }
    .main-menu .navbar-brand img {
      max-width: 100%;
      height: 50px;
    }
    .main-menu .navbar-nav > li > a {
      color: #333333;
      font-size: 13px;
      text-transform: uppercase;
    }
    .main-menu .phone {
      color: rgb(76, 175, 80);
      font-size: 16px;
      font-weight: bold;
    }
    .main-menu .phone:hover {
      color: rgb(76, 175, 80);
      text-decoration: none;
    }
    .main-menu .navbar-btn {
      font-size: 12px;
      text-transform: uppercase;
    }
    </style>

    <?if (!empty($arResult)):?>
    <nav class="navbar navbar-default main-menu navbar-fixed-top" style="margin-bottom: 0;">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
            data-target="#main-menu" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="https://atkterminal.ru">
            <img
              src="https://atkterminal.ru/bitrix/templates/shopATK/images/logo.png"
              alt="Логотип АТК Групп"
              title="АТК групп - продажа и аренда контейнеров"
              width="90"
              height="50"
            />
          </a>
        </div>
        <div class="collapse navbar-collapse" id="main-menu">

          <ul class="nav navbar-nav">
            <?
            $previousLevel = 0;
            foreach($arResult as $arItem):?>

              <?if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):?>
                <?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
              <?endif?>

              <?if ($arItem["IS_PARENT"]):?>

                <?if ($arItem["DEPTH_LEVEL"] == 1):?>
                  <li class="dropdown">
                    <a href="#<?/*=$arItem["LINK"]*/?>"

                      class="<?if ($arItem["SELECTED"]):?>root-item-selected <?else:?>root-item dropdown-toggle<?endif?>"
                      data-toggle="dropdown"
                      role="button"
                      aria-haspopup="true"
                      aria-expanded="false"
                      >
                      <?=$arItem["TEXT"]?>
                      <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                <?else:?>
                  <li<?if ($arItem["SELECTED"]):?> class="item-selected"<?endif?>>
                    <a href="<?=$arItem["LINK"]?>" class="parent"><?=$arItem["TEXT"]?></a>
                  <ul>
                <?endif?>

              <?else:?>

                <?if ($arItem["PERMISSION"] > "D"):?>

                  <?if ($arItem["DEPTH_LEVEL"] == 1):?>
                    <li>
                      <a href="<?=$arItem["LINK"]?>" class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item<?endif?>"><?=$arItem["TEXT"]?></a></li>
                  <?else:?>
                    <li<?if ($arItem["SELECTED"]):?> class="item-selected"<?endif?>>
                      <a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
                    </li>
                  <?endif?>

                <?else:?>

                  <?if ($arItem["DEPTH_LEVEL"] == 1):?>
                    <li>
                      <a href="" class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item<?endif?>" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>"><?=$arItem["TEXT"]?></a>
                    </li>
                  <?else:?>
                    <li>
                      <a href="" class="denied" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>"><?=$arItem["TEXT"]?></a>
                    </li>
                  <?endif?>
                <?endif?>
              <?endif?>

              <?$previousLevel = $arItem["DEPTH_LEVEL"];?>

            <?endforeach?>

            <?if ($previousLevel > 1)://close last item tags?>
              <?=str_repeat("</ul></li>", ($previousLevel-1) );?>
            <?endif?>
          </ul> <!-- nav navbar-nav -->
        <div class="navbar-text" style="margin-top:13px;margin-bottom:12px;">
          <a href="tel:+78005553783" class="navbar-link phone">8 (800) 555-37-83</a>
        </div>
        <div class="navbar-right" style="margin-right:0">
          <button style="margin-top:10px;margin-bottom:10px" type="button" data-toggle="modal" data-target="#myModal" class="btn btn-success navbar-btn">Заказать звонок</button>
        </div>
        </div><!-- navbar-collapse -->
      </div><!-- container-fluid -->
    </nav>

    <?endif?>
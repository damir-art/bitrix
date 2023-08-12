# Код одноуровневого меню
Код одноуровневого меню с использованием бутстрап 5.

    <?php
    if (!empty($arResult)):

    /*echo "<pre style='color:#eee;'>";
    echo print_r($arResult);
    echo "</pre>";*/

    ?>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#landingMenu">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="landingMenu">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <?
            foreach($arResult as $arItem):
              if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
                continue;
            ?>
              <?if($arItem["SELECTED"]):?>
                <li class="nav-item">
                  <a class="nav-link active" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
                </li>
              <?else:?>
                <li class="nav-item">
                  <a class="nav-link" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
                </li>
              <?endif?>
            <?endforeach?>
          </ul>
        </div>
      </div>
    </nav>
    <?endif?>

<?php
// Подключаем классы
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;

// Подключаем языковые файлы
Loc::loadMessages(__FILE__);

// Получаем ID модуля
$request = HttpApplication::getInstance()->getContext()->getRequest();
$module_id = htmlspecialcharsbx($request["mid"] != "" ? $request["mid"] : $request["id"]);

// Подключаем модуль damir.button
Loader::includeModule($module_id);

// Описываем настройки модуля и помещаем их в массив.
// Через административную часть мы позволим пользователю изменять:
// - Включать/выключать модуль (кнопку может, а не модуль?),
// - Изменять ширину/высоту/радиус кнопки,
// - Менять цвет кнопки,
// - Менять положение кнопки и скорость анимации.

$aTabs = array(
  array(
    "DIV"     => "edit",
    "TAB"     => Loc::getMessage("DAMIR_BUTTONUP_OPTIONS_TAB_NAME"),
    "TITLE"   => Loc::getMessage("DAMIR_BUTTONUP_OPTIONS_TAB_NAME"),
    "OPTIONS" => array(
      Loc::getMessage("DAMIR_BUTTONUP_OPTIONS_TAB_COMMON"),
      array(
        "switch_on",
        Loc::getMessage("DAMIR_BUTTONUP_OPTIONS_TAB_SWITCH_ON"),
        "Y",
        array("checkbox")
      ),
      Loc::getMessage("DAMIR_BUTTONUP_OPTIONS_TAB_APPEARANCE"),
      array(
        "width",
        Loc::getMessage("DAMIR_BUTTONUP_OPTIONS_TAB_WIDTH"),
        "50",
        array("text", 5)
      ),
      array(
        "height",
        Loc::getMessage("DAMIR_BUTTONUP_OPTIONS_TAB_HEIGHT"),
        "50",
        array("text", 5)
      ),
      array(
        "radius",
        Loc::getMessage("DAMIR_BUTTONUP_OPTIONS_TAB_RADIUS"),
        "50",
        array("text", 5)
      ),
      array(
        "color",
        Loc::getMessage("DAMIR_BUTTONUP_OPTIONS_TAB_COLOR"),
        "#bf3030",
        array("text", 5)
      ),
      Loc::getMessage("DAMIR_BUTTONUP_OPTIONS_TAB_POSITION_ON_PAGE"),
      array(
        "side",
        Loc::getMessage("DAMIR_BUTTONUP_OPTIONS_TAB_SIDE"),
        "left",
        array("selectbox", array(
          "left"  => Loc::getMessage("DAMIR_BUTTONUP_OPTIONS_TAB_SIDE_LEFT"),
          "right" => Loc::getMessage("DAMIR_BUTTONUP_OPTIONS_TAB_SIDE_RIGHT")
        ))
      ),
      array(
        "indent_bottom",
        Loc::getMessage("DAMIR_BUTTONUP_OPTIONS_TAB_INDENT_BOTTOM"),
        "10",
        array("text", 5)
      ),
      array(
        "indent_side",
          Loc::getMessage("DAMIR_BUTTONUP_OPTIONS_TAB_INDENT_SIDE"),
        "10",
        array("text", 5)
      ),
      Loc::getMessage("DAMIR_BUTTONUP_OPTIONS_TAB_ACTION"),
        array(
        "speed",
        Loc::getMessage("DAMIR_BUTTONUP_OPTIONS_TAB_SPEED"),
        "normal",
        array("selectbox", array(
          "slow"   => Loc::getMessage("DAMIR_BUTTONUP_OPTIONS_TAB_SPEED_SLOW"),
          "normal" => Loc::getMessage("DAMIR_BUTTONUP_OPTIONS_TAB_SPEED_NORMAL"),
          "fast"   => Loc::getMessage("DAMIR_BUTTONUP_OPTIONS_TAB_SPEED_FAST")
        ))
      )
    )
  )
);

// Код для сохранения настроек
// В обработчике мы также используем массив с параметрами для удобства сохранения,
// хотя это один из способов, в конце, конечно, нужно сделать редирект.
// Все настройки мы сохраняем при помощи статического метода set() у класса Option,
// который нужен именно для работы с настройками модуля.
if( $request->isPost() && check_bitrix_sessid()) {
  foreach($aTabs as $aTab){
    foreach($aTab["OPTIONS"] as $arOption) {
      if(!is_array($arOption)) {
        continue;
      }
      if($arOption["note"]) {
        continue;
      }
      if($request["apply"]) {
        $optionValue = $request->getPost($arOption[0]);
        if($arOption[0] == "switch_on") {
          if($optionValue == "") {
            $optionValue = "N";
          }
        }
        Option::set($module_id, $arOption[0], is_array($optionValue) ? implode(",", $optionValue) : $optionValue);
      } elseif($request["default"]) {
        Option::set($module_id, $arOption[0], $arOption[2]);
      }
    }
  }
  LocalRedirect($APPLICATION->GetCurPage()."?mid=".$module_id."&lang=".LANG);
}

// Отрисовываем форму: 
// - Создаём экземпляр класса CAdminTabControl,
// - Передаём в него массив с настройками.
$tabControl = new CAdminTabControl(
  "tabControl",
  $aTabs
);

$tabControl->Begin();
?>

<!-- Добавляем HTML-код формы -->
<form action="<? echo($APPLICATION->GetCurPage()); ?>?mid=<? echo($module_id); ?>&lang=<? echo(LANG); ?>" method="post">
  <?php
    foreach($aTabs as $aTab){
      if( $aTab["OPTIONS"] ) {
        $tabControl->BeginNextTab();
        __AdmSettingsDrawList($module_id, $aTab["OPTIONS"]);
      }
    }
    $tabControl->Buttons();
  ?>
  <input type="submit" name="apply" value="<? echo(Loc::GetMessage("DAMIR_BUTTONUP_OPTIONS_INPUT_APPLY")); ?>" class="adm-btn-save" />
  <input type="submit" name="default" value="<? echo(Loc::GetMessage("DAMIR_BUTTONUP_OPTIONS_INPUT_DEFAULT")); ?>" />
  <?php
    echo(bitrix_sessid_post());
  ?>
</form>

<?php
// Обозначим конец отрисовки формы:
$tabControl->End();

// Такой способ формирования формы один из самых простых,
// фактически, мы можем создать любой вариант форм, не пользуясь для этого Битриксовым функционалом.

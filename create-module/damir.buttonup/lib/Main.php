<?php
namespace Damir\Buttonup;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Page\Asset;

class Main {

  // public function debug() {
  //   \Bitrix\Main\Diag\Debug::writeToFile(
  //     defined("ADMIN_SECTION"),      // что записать, переменную или массив
  //     'ВЫВОД:',     // коментарий к записи, к переменной или массиву, можно поставить дату
  //     'local/log1.php' // путь куда записать
  //   );
  // }

  public static function appendScriptsToPage() {
    // \Bitrix\Main\Diag\Debug::writeToFile(
    //   "Hello",      // что записать, переменную или массив
    //   'ВЫВОД',     // коментарий к записи, к переменной или массиву, можно поставить дату
    //   'local/log.php' // путь куда записать
    // );
    // В методе мы определили, что код будет отрабатывать только на стороне клиента.
    // Далее сформировали HTML код с набором настроек в json формате для удобства и добавили его в HEAD документа.
    // В конце подключили скрипты и стили нашего Битрикс модуля кнопка наверх.
    // Вот и всё - параметры переданы, осталось их обработать на стороне клиента.
    if( !defined("ADMIN_SECTION") ) {
      $module_id = pathinfo(dirname(__DIR__))["basename"];

      Asset::getInstance()->addString(
        "<script id=\"" . str_replace(".", "_", $module_id)."-params\" data-params='".json_encode(
          array(
            "switch_on"     => Option::get($module_id, "switch_on",     "Y"),
            "width"         => Option::get($module_id, "width",         "50"),
            "height"        => Option::get($module_id, "height",        "50"),
            "radius"        => Option::get($module_id, "radius",        "50"),
            "color"         => Option::get($module_id, "color",         "#bf3030"),
            "side"          => Option::get($module_id, "side",          "left"),
            "indent_bottom" => Option::get($module_id, "indent_bottom", "10"),
            "indent_side"   => Option::get($module_id, "indent_side",   "10"),
            "speed"         => Option::get($module_id, "speed",         "normal")
          )
        )."'></script>",
        true
      );
      Asset::getInstance()->addJs("/bitrix/js/".$module_id."/jquery.min.js");
      Asset::getInstance()->addJs("/bitrix/js/".$module_id."/script.js");
      Asset::getInstance()->addCss("/bitrix/css/".$module_id."/style.css");
    }
    return false;
  }
}

// $a = new Main();
// $a->appendScriptsToPage();

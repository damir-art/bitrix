# Стили и скрипты
Подключение стилей и скриптов

    // Old school $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/js/fix.js");
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/styles/fix.css");
    $APPLICATION->AddHeadString("<link href='http://fonts.googleapis.com/css?family=PT+Sans:400&subset=cyrillic' rel='stylesheet' type='text/css'>");

    // D7
    use Bitrix\Main\Page\Asset;

    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/fix.js");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/styles/fix.css");
    Asset::getInstance()->addString("<link href='http://fonts.googleapis.com/css?family=PT+Sans:400&subset=cyrillic' rel='stylesheet' type='text/css'>");
